<?php
    session_start(); // pour regarder qui déconnecter
    session_unset(); // pour vider les variables de session
    session_destroy(); // pour "détruire" la session
    header("Location: index.php");
    exit();
?>