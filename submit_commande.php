<?php
session_start();

// 1. Sécurité : si le panier est vide, on renvoie au panier
if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    header('Location: panier.php');
    exit();
}

// 2. Calcul du total
$total_commande = 0;
foreach ($_SESSION['panier'] as $article) {
    $total_commande += $article['prix'] * $article['quantite'];
}

// 3. Récupération du client (ou Anonyme si non connecté)
if (isset($_SESSION['prenom']) && isset($_SESSION['nom'])) {
    $client_identite = $_SESSION['prenom'] . ' ' . $_SESSION['nom'];
} else {
    $client_identite = 'Client Anonyme';
}

// 4. Création du "ticket" de commande
// 4. Création du "ticket" de commande
$nouvelle_commande = [
    "id_commande" => uniqid('CMD_'),
    "client" => $client_identite, 
    "telephone" => $_SESSION['telephone'] ?? 'Non renseigné', // NOUVEAU
    "adresse" => $_SESSION['adresse'] ?? 'Adresse non renseignée', // NOUVEAU
    "instructions" => "À remettre en main propre", // (On met ça par défaut pour l'instant)
    "date" => date('Y-m-d H:i:s'),
    "articles" => $_SESSION['panier'],
    "total" => $total_commande,
    "statut" => "En préparation"
];

// 5. Lecture et écriture dans le fichier JSON
$fichier_commandes = 'data/commandes.json';
$toutes_les_commandes = [];

if (file_exists($fichier_commandes)) {
    $contenu = file_get_contents($fichier_commandes);
    if (!empty($contenu)) {
        $toutes_les_commandes = json_decode($contenu, true);
        // Sécurité si le fichier est mal formaté
        if (!is_array($toutes_les_commandes)) {
            $toutes_les_commandes = []; 
        }
    }
}

// On ajoute la nouvelle commande à la liste
$toutes_les_commandes[] = $nouvelle_commande;

// On sauvegarde le tout dans le fichier
file_put_contents($fichier_commandes, json_encode($toutes_les_commandes, JSON_PRETTY_PRINT));

// 6. LE PLUS IMPORTANT : On vide le panier du client !
unset($_SESSION['panier']);

// 7. Redirection vers l'accueil avec un paramètre de succès
header('Location: index.php?succes=commande');
exit();
?>