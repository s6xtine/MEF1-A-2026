<?php
session_start();
require('getapikey.php');

//on récupère les données envoyées pas CYBank
$transaction = $_GET['transaction'] ?? '';
$montant = $_GET['montant'] ?? '';
$vendeur = $_GET['vendeur'] ?? '';
$statut = $_GET['status'] ?? '';
$control_banque = $_GET['control'] ?? '';

// on vérifie que les données viennent bien de la banque
$api_key = getAPIKey($vendeur);
$control_local = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $statut . "#");

if ($control_local === $control_banque && $statut === 'accepted') {
    
    $fichier_commandes = '../data/commandes.json';
    $toutes_les_commandes = [];
    
    if (file_exists($fichier_commandes)) {
        $contenu = file_get_contents($fichier_commandes);
        if (!empty($contenu)) {
            $toutes_les_commandes = json_decode($contenu, true);
        }
    }

    $nouvelle_commande = [
        "id_commande" => "CMD_" . $transaction,
        "client" => $_SESSION['prenom'] . " " . $_SESSION['nom'],
        "login_client" => $_SESSION['login'], // Utile pour retrouver le compte
        "telephone" => $_SESSION['telephone'] ?? 'Non renseigné', // 📞 IMPORTANT
        "adresse" => $_SESSION['adresse'] ?? 'À récupérer sur place', // 📍 IMPORTANT
        "date" => date('Y-m-d H:i:s'),
        "date_souhaitee" => $_SESSION['date_retrait'] ?? date('Y-m-d'), 
        "heure_souhaitee" => $_SESSION['heure_retrait'] ?? date('H:i'),
        "articles" => $_SESSION['panier'],
        "total" => (float)$montant,
        "statut" => "En préparation"
    ];

    $toutes_les_commandes[] = $nouvelle_commande;
    file_put_contents($fichier_commandes, json_encode($toutes_les_commandes, JSON_PRETTY_PRINT));
    
    // On vide le panier
    unset($_SESSION['panier']);
    
    header('Location: ../index.php?succes=commande');
    exit();
} else {
    header('Location: ../panier.php?erreur=paiement_refuse');
}
exit();
?>