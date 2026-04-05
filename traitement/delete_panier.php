<?php
session_start();

// pour supprimer un article du panier
if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
    $id_a_supprimer = $_GET['id'];
    
    if (isset($_SESSION['panier'][$id_a_supprimer])) {
        unset($_SESSION['panier'][$id_a_supprimer]);
    }
    
    header('Location: panier.php');
    exit();
}