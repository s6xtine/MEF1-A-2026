<?php
session_start();

// vérifie qu'on est bien en mode modification
if (!isset($_SESSION['modif_id_commande'])) {
    header('Location: ../profil.php');
    exit();
}

$id_a_modifier = $_SESSION['modif_id_commande'];
$fichier_commandes = '../data/commandes.json'; 
$toutes_les_commandes = [];

if (file_exists($fichier_commandes)) {
    $contenu = file_get_contents($fichier_commandes);
    if (!empty($contenu)) {
        $toutes_les_commandes = json_decode($contenu, true);
    }
}

if (is_array($toutes_les_commandes)) {
    foreach ($toutes_les_commandes as $index => $cmd) {
        if ($cmd['id_commande'] === $id_a_modifier) {
            
            //  remplace les anciens articles par ceux du panier actuel
            $toutes_les_commandes[$index]['articles'] = $_SESSION['panier'];
            
            // recalcule le montant de la commande modifiée 
            $nouveau_total = 0;
            foreach ($_SESSION['panier'] as $article) {
                $nouveau_total += $article['prix'] * $article['quantite'];
            }
            $toutes_les_commandes[$index]['total'] = (float)$nouveau_total;
            
            // On ajoute une trace de la date de modification
            $toutes_les_commandes[$index]['date_modification'] = date('Y-m-d H:i:s');
            
            break; 
        }
    }

    //  sauvegarde les modifications dans le fichier JSON
    file_put_contents($fichier_commandes, json_encode($toutes_les_commandes, JSON_PRETTY_PRINT));
}


unset($_SESSION['modif_id_commande']);
unset($_SESSION['modif_montant_initial']);
unset($_SESSION['panier']);

header('Location: ../profil.php?succes=modification_enregistree');
exit();
?>