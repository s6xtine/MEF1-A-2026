<?php
    session_start(); //ouverture session
    session_unset(); //vider les variables de session ($_SESSION)
    session_destroy(); //détruit la session côté serveur
    header("Location: index.php");
    exit();
    //Ce fichier détruit la session de l'utilisateur pour le déconnecter
?>