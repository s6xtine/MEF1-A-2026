<?php
session_start();
require('getapikey.php');

$transaction = $_GET['transaction'] ?? '';
$montant = $_GET['montant'] ?? '';
$vendeur = $_GET['vendeur'] ?? '';
$statut = $_GET['status'] ?? '';
$control_banque = $_GET['control'] ?? '';

$api_key = getAPIKey($vendeur);
$control_local = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $statut . "#");


$est_modification = isset($_SESSION['modif_id_commande']);

// Sécurité : on recalcule le montant total du panier côté serveur pour éviter toute manipulation côté client
$vrai_total_panier = 0.0;
if (isset($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $article) {
        // On recalcule le vrai prix depuis la session côté serveur
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
                
                // Si c'est gratuit/moins cher, le total ne bouge pas 
                if ($statut === 'gratuit') {
                    $toutes_les_commandes[$index]['total'] = (float)$_SESSION['modif_montant_initial']; 
                } else {
                    // Si on a payé une différence, le nouveau total c'est l'ancien + la différence payée
                    $toutes_les_commandes[$index]['total'] = (float)$_SESSION['modif_montant_initial'] + (float)$montant;
                }
                
                $toutes_les_commandes[$index]['date_modification'] = date('Y-m-d H:i:s');
                break;
            }
        }
        
        
        unset($_SESSION['modif_id_commande']);
        unset($_SESSION['modif_montant_initial']);

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

    // on debite les points de fidélité utilisés par le client pour cette commande
    $points_utilises = isset($_SESSION['points_utilises']) ? (float)$_SESSION['points_utilises'] : 0.0;
    if ($points_utilises > 0) {
        $fichier_users = '../data/utilisateur.json';
        if (file_exists($fichier_users)) {
            $utilisateurs = json_decode(file_get_contents($fichier_users), true);
            if (is_array($utilisateurs)) {
                foreach ($utilisateurs as &$user) {
                    if ($user['login'] === $_SESSION['login']) {
                        $user['points'] = max(0, ($user['points'] ?? 0) - $points_utilises);
                        $_SESSION['points'] = $user['points']; // Mise à jour en direct
                        break;
                    }
                }
                file_put_contents($fichier_users, json_encode($utilisateurs, JSON_PRETTY_PRINT));
            }
        }
    }
    // On réinitialise les points utilisés pour la prochaine commande
    unset($_SESSION['points_utilises']); 
    
    // On vide le panier
    unset($_SESSION['panier']);
    
    header('Location: ../index.php?succes=commande');
    exit();
} else {
    header('Location: ../panier.php?erreur=paiement_refuse');
}
exit();