<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_saisi = $_POST['login'];
    $mdp_saisi = $_POST['mdp'];

    $chemin_fichier = '../data/utilisateur.json';

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
            $_SESSION['id'] = $utilisateur_trouve['id'];
            $_SESSION['nom'] = $utilisateur_trouve['nom'];
            $_SESSION['prenom'] = $utilisateur_trouve['prenom'];
            $_SESSION['login'] = $utilisateur_trouve['login'];
            $_SESSION['role'] = $utilisateur_trouve['role'];
            $_SESSION['statut'] = $utilisateur_trouve['statut'] ?? 'Regular'; 
            $_SESSION['telephone'] = $utilisateur_trouve['telephone'] ?? 'Non renseigné';
            $_SESSION['adresse'] = $utilisateur_trouve['adresse'] ?? 'Non renseignée';
            $_SESSION['points'] = $utilisateur_trouve['points'] ?? 0;
            
            switch($utilisateur_trouve['role']) {
                case 'admin':
                    header('Location: ../administrateur.php');
                    break;
                case 'livreur':
                    header('Location: ../livraison.php');
                    break;
                case 'restaurateur':
                    header('Location: ../commandes.php');
                    break;
                default:
                        header('Location: ../index.php');
                        break;
            }
            exit();
        } else {
            header("Location: ../connexion.php?erreur=1");
            exit();
        }
    }
}
?>