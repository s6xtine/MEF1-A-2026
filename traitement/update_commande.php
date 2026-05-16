<?php
session_start();

// vérifie qu'on a bien reçu l'id de la commande à modifier
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: profil.php'); // Renvoie vers son profil s'il n'y a pas d'ID
    exit();
}

$id_commande_a_modifier = $_GET['id'];
$fichier_commandes = '../data/commandes.json';

if (file_exists($fichier_commandes)) {
    $commandes = json_decode(file_get_contents($fichier_commandes), true);
    
    if (is_array($commandes)) {
        foreach ($commandes as $cmd) {
            // on cherche la bonne commande
            if ($cmd['id_commande'] === $id_commande_a_modifier) {
                if ($cmd['statut'] !== 'payé') { //si la commande n'est pas encore payé on peut la modifier 
                    header('Location: profil.php?erreur=deja_en_cuisine');
                    exit();
                }

                
                // On écrase le panier actuel avec les articles de cette commande
                $_SESSION['panier'] = $cmd['articles'];
                
                
                $_SESSION['modif_id_commande'] = $cmd['id_commande'];
                $_SESSION['modif_montant_initial'] = $cmd['total'];
                
                // on revnoie vers le menu pour faire d,'autres modifications
                header('Location: ../menu.php?mode=modification');
                exit();
            }
        }
    }
}

header('Location: profil.php?erreur=commande_introuvable');
exit();