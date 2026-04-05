<?php
session_start();
if (isset($_POST['date']) && isset($_POST['heure'])) {
    $_SESSION['date_retrait'] = $_POST['date'];
    $_SESSION['heure_retrait'] = $_POST['heure'];
}
?>