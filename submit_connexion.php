<?php

session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_saisi = $_POST['email'];
    $mdp_saisi = $_POST['password'];

    $chemin_fichier = 'data/utilisateurs.json';

    if(file_exists($chemin_fichier)) {
        $contenu_json = file_get_contents($chemin_fichier);
        $utilisateurs = json_decode($contenu_json, true);

        foreach($utilisateurs as $user) {
            if($user['login'] === $email_saisi && $user['mdp'] === $mdp_saisi) {
                $utilisateur_trouve = $user;
                break;
            }
        }

        if($utilisateur_trouve) {
            $_SESSION['nom'] = $utilisateur_trouve['nom'];
            $_SESSION['role'] = $utilisateur_trouve['role'];
            
            switch($utilisateur_trouve['role']) {
                case 'admin':
                    header('Location: admin.php');
                    break;
                case 'restaurateur':
                    header('Location: restaurateur.php');
                    break;
                case 'livreur':
                    header('Location: livreur.php');
                    break;
                case 'client':
                    header('Location: index.php');
                    break;
            }
            exit();
        } else {
            header("Location: connexion.php?erreur=1");
        }
    }
}
?>