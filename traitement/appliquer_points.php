<?php
session_start();

// Si le client clique sur "Retirer", on efface sa sélection
if (isset($_GET['annuler']) && $_GET['annuler'] == 1) {
    unset($_SESSION['points_utilises']);
    header('Location: ../panier.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pts = isset($_POST['points_a_utiliser']) ? (float)$_POST['points_a_utiliser'] : 0.0;
    if ($pts >= 0) {
        $_SESSION['points_utilises'] = $pts;
    }
}

header('Location: ../panier.php');
exit();
?>