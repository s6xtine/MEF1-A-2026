<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['login'])) {
    $chemin_fichier = '../data/utilisateur.json';
    
    
    if (file_exists($chemin_fichier)) {
        $utilisateurs = json_decode(file_get_contents($chemin_fichier), true);
        $success = false;

        
        foreach ($utilisateurs as &$user) {
            if ($user['login'] === $_SESSION['login']) {
                
                
                $user['nom'] = $_POST['nom'];
                $user['prenom'] = $_POST['prenom'];
                $user['telephone'] = $_POST['tel']; 
                $user['adresse'] = $_POST['adresse'];

                
                $_SESSION['nom'] = $_POST['nom'];
                $_SESSION['prenom'] = $_POST['prenom'];
                $_SESSION['telephone'] = $_POST['tel'];
                $_SESSION['adresse'] = $_POST['adresse'];

                $success = true;
                break;
            }
        }

        if ($success) {
          
            file_put_contents($chemin_fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT));
            
            
            header('Location: ../profil.php');
            exit();
        }
    }
}


header('Location: ../profil.php?erreur=1');
exit();