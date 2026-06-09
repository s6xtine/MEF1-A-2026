<?php
session_start();
require('getapikey.php');

// Récupération des paramètres de retour envoyés par l'API CYBank (via GET)
$transaction = $_GET['transaction'] ?? '';
$montant = $_GET['montant'] ?? '';
$vendeur = $_GET['vendeur'] ?? '';
$statut = $_GET['status'] ?? '';
$control_banque = $_GET['control'] ?? '';

//Sécurité d'intégrité. On recalcule la clé MD5 localement avec notre clé secrète pour vérifier que la requête provient bien de CYBank et n'a pas été falsifiée en route.
$api_key = getAPIKey($vendeur);
$control_local = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $statut . "#");


$est_modification = isset($_SESSION['modif_id_commande']);

// Sécurité : on recalcule le montant total du panier côté serveur pour éviter toute manipulation côté client
$vrai_total_panier = 0.0;
if (isset($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $article) {
        $vrai_total_panier += (float)$article['prix'] * (int)$article['quantite'];
    }
}

// On retire ce qui a déjà été payé (si c'est une modification) et les remises de fidélité
$deja_paye = $est_modification ? (float)$_SESSION['modif_montant_initial'] : 0.0;
$points_utilises = isset($_SESSION['points_utilises']) ? (float)$_SESSION['points_utilises'] : 0.0;

// On calcule ce que le client aurait dû payer (max évite un result négatif)
$montant_attendu = max(0.0, $vrai_total_panier - $deja_paye - $points_utilises);

// On formate les prix à 2 décimales pour les comparer au centime près
$montant_attendu_str = number_format($montant_attendu, 2, '.', '');
$montant_paye_str = number_format((float)$montant, 2, '.', '');

// Paiement classique valide que si le prix payé est strictement égal au prix attendu
$paiement_valide = ($control_local === $control_banque && $statut === 'accepted' && $montant_paye_str === $montant_attendu_str);

// Mode gratuit valide que si le montant attendu est réellement de 0.00€
$gratuit_valide = ($est_modification && $statut === 'gratuit' && $montant_attendu_str === '0.00');
// fin de la sécu

if ($paiement_valide || $gratuit_valide) {
    
    $fichier_commandes = '../data/commandes.json';
    $toutes_les_commandes = [];
    
    if (file_exists($fichier_commandes)) {
        $contenu = file_get_contents($fichier_commandes);
        if (!empty($contenu)) {
            $toutes_les_commandes = json_decode($contenu, true);
        }
    }

    if ($est_modification) {
        // si on veut faire une modif on va chercher la commande a modifier 
        $id_a_modifier = $_SESSION['modif_id_commande'];
        
        foreach ($toutes_les_commandes as $index => $cmd) {
            if ($cmd['id_commande'] === $id_a_modifier) {
                // met à jour les articles, le nouveau total et la date de modif
                $toutes_les_commandes[$index]['articles'] = $_SESSION['panier'];
                
                // Si c'est gratuit/moins cher, on met a jour le total à ce qui reste à payer (ou 0 si c'est gratuit). Si c'est plus cher, on rajoute la différence à ce qui a déjà été payé
                if ($statut === 'gratuit') {
                    $toutes_les_commandes[$index]['total'] = $vrai_total_panier;
                } else {
                    // Si on a payé une différence, le nouveau total c'est l'ancien + la différence payée
                    $toutes_les_commandes[$index]['total'] = (float)$_SESSION['modif_montant_initial'] + (float)$montant;
                }
                
                $toutes_les_commandes[$index]['date_modification'] = date('Y-m-d H:i:s');
                break;
            }
        }
        

    } else {
        // cas d'une nouvelle commande 
        $nouvelle_commande = [
            "id_commande" => "CMD_" . $transaction,
            "client" => $_SESSION['prenom'] . " " . $_SESSION['nom'],
            "login_client" => $_SESSION['login'], 
            "telephone" => $_SESSION['telephone'] ?? 'Non renseigné', 
            "adresse" => $_SESSION['adresse'] ?? 'À récupérer sur place', 
            "date" => date('Y-m-d H:i:s'),
            "date_souhaitee" => $_SESSION['date_retrait'] ?? date('Y-m-d'), 
            "heure_souhaitee" => $_SESSION['heure_retrait'] ?? date('H:i'),
            "articles" => $_SESSION['panier'],
            "total" => (float)$montant,
            "statut" => "payé" 
        ];
        $toutes_les_commandes[] = $nouvelle_commande;
    }
    
    // On sauvegarde le fichier JSON
    file_put_contents($fichier_commandes, json_encode($toutes_les_commandes, JSON_PRETTY_PRINT));

    if (isset($_SESSION['login'])) {
        // On va chercher l'utilisateur dans le JSON pour mettre à jour ses points de fidélité
        $variation_points = 0;

        // Déduction des points s'ils ont été utilisés pour payer
        $pts_utilises_actuel = isset($_SESSION['points_utilises']) ? (float)$_SESSION['points_utilises'] : 0.0;
        if ($pts_utilises_actuel > 0) {
            $variation_points -= $pts_utilises_actuel;
        }

        // Mise à jour dans le JSON et la session si les points ont changé
        if ($variation_points != 0) {
            $fichier_users = '../data/utilisateur.json';
            if (file_exists($fichier_users)) {
                $utilisateurs = json_decode(file_get_contents($fichier_users), true);
                if (is_array($utilisateurs)) {
                    foreach ($utilisateurs as &$user) {
                        if ($user['login'] === $_SESSION['login']) {
                            // On applique la variation (en + ou en -)
                            $user['points'] = ($user['points'] ?? 0) + $variation_points;
                            
                            // Sécurité : on empêche d'avoir un solde négatif
                            if ($user['points'] < 0) {
                                $user['points'] = 0;
                            }
                            
                            // Mise à jour en direct de la session pour la page profil
                            $_SESSION['points'] = $user['points']; 
                            break;
                        }
                    }
                    file_put_contents($fichier_users, json_encode($utilisateurs, JSON_PRETTY_PRINT));
                }
            }
        }
    }
    // On réinitialise les points utilisés pour la prochaine commande
    unset($_SESSION['points_utilises']);
    if ($est_modification) {       
        unset($_SESSION['modif_id_commande']);
        unset($_SESSION['modif_montant_initial']);
    }

    // On vide le panier
    unset($_SESSION['panier']);
    
    header('Location: ../index.php?succes=commande');
    exit();
} else {
    header('Location: ../panier.php?erreur=paiement_refuse');
}
exit();
// Ce fichier reçoit la requête de retour de CYBank après le paiement. Il vérifie l'intégrité de la requête, valide le paiement, met à jour les commandes et les points de fidélité, puis redirige l'utilisateur avec un message de succès ou d'erreur.
?>