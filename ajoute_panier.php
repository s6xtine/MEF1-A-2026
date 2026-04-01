<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produit = $_POST['id_produit'];
    $nom = $_POST['nom'];
    $prix = (float)$_POST['prix'];
    $quantite = (int)$_POST['quantite'];

    
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

   
    if (isset($_SESSION['panier'][$id_produit])) {
        $_SESSION['panier'][$id_produit]['quantite'] += $quantite;
    } else {
       
        $_SESSION['panier'][$id_produit] = [
            'nom' => $nom,
            'prix' => $prix,
            'quantite' => $quantite
        ];
    }

    
    header('Location: menu.php?ajout=succes');
    exit();
} else {
    
    header('Location: index.php');
    exit();
}
?>