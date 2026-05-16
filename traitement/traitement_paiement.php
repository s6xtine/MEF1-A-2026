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


if (($control_local === $control_banque && $statut === 'accepted') || ($est_modification && $statut === 'gratuit')) {
    
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
    
    // On vide le panier
    unset($_SESSION['panier']);
    
    header('Location: ../index.php?succes=commande');
    exit();
} else {
    header('Location: ../panier.php?erreur=paiement_refuse');
}
exit();