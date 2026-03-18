<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_saisi = $_POST['email'];
    $mdp_saisi = $_POST['password'];

    $chemin_fichier = 'data/utilisateur.json';

    if(file_exists($chemin_fichier)) {
        $contenu_json = file_get_contents($chemin_fichier);
        $utilisateurs = json_decode($contenu_json, true);

        if (is_array($utilisateurs)) {
            $utilisateur_trouve = null;

            foreach($utilisateurs as $user) {
                if($user['login'] === $email_saisi && $user['mdp'] === $mdp_saisi) {
                    $utilisateur_trouve = $user;
                    break;
                }
            }
        }

        if($utilisateur_trouve) {
            $_SESSION['nom'] = $utilisateur_trouve['nom'];
            $_SESSION['role'] = $utilisateur_trouve['role'];
            
            switch($utilisateur_trouve['role']) {
                case 'admin':
                    header('Location: administrateur.php');
                    break;
                case 'livreur':
                    header('Location: livraison.php');
                    break;
                case 'restaurateur':
                    header('Location: commandes.php');
                    break;
                default:
                        header('Location: index.php');
                        break;
            }
            exit();
        } else {
            header("Location: connexion.php?erreur=1");
            exit();
        }
    }
}
?>