<?php
session_start();

// verif l'id de l'article qu'on veut supprimer 
if (isset($_GET['id']) && $_GET['id'] !== '') {
    $id_article = $_GET['id'];

    
    if (isset($_SESSION['panier'][$id_article])) {
        unset($_SESSION['panier'][$id_article]);
    }
}

//  renvoie le client vers le panier pour qu'il voie le résultat
$page_precedente = $_SERVER['HTTP_REFERER'] ?? '../panier.php';
header('Location: ' . $page_precedente);
exit();