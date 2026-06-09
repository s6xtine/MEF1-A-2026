<?php
session_start();

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
                // Sécurité : Vérification du mdp haché
                if($user['login'] === $email_saisi && password_verify($mdp_saisi, $user['mdp'])) { // password_verify pour comparer le mdp saisi avec le mdp haché dans le JSON
                    $utilisateur_trouve = $user;
                    break;
                }
            }
        }

        if($utilisateur_trouve) {
            // Vérification du statut de blocage
            if (isset($utilisateur_trouve['bloque']) && $utilisateur_trouve['bloque'] === true) {
                header('Location: ../connexion.php?erreur=bloque');
                exit();
            }

            // Vérification de l'anniversaire pour les clients
            if ($utilisateur_trouve['role'] === 'client' && !empty($utilisateur_trouve['naissance'])) {
                $aujourdhui = date('m-d');
                $annee_actuelle = date('Y');
                
                // On extrait le mois et le jour de la date de naissance de l'utilisateur
                $anniv_user = date('m-d', strtotime($utilisateur_trouve['naissance']));

                // Si c'est son anniversaire ET qu'on ne lui a pas encore offert son cadeau cette année
                if ($anniv_user === $aujourdhui && (!isset($utilisateur_trouve['dernier_anniv_offert']) || $utilisateur_trouve['dernier_anniv_offert'] !== $annee_actuelle)) {
                    
                    $points_menu_gratuit = 30;
                    
                    // 1. On lui crédite les points
                    $utilisateur_trouve['points'] = ($utilisateur_trouve['points'] ?? 0) + $points_menu_gratuit;
                    
                    // 2. On marque que le cadeau de cette année est donné
                    $utilisateur_trouve['dernier_anniv_offert'] = $annee_actuelle;
                    
                    // 3. On active le message pop-up
                    $_SESSION['anniv_bonus'] = true; 

                    // 4. On met à jour le tableau des utilisateurs et on sauvegarde le fichier JSON
                    foreach ($utilisateurs as $key => $u) {
                        if ($u['login'] === $utilisateur_trouve['login']) {
                            $utilisateurs[$key] = $utilisateur_trouve;
                            break;
                        }
                    }
                    file_put_contents($chemin_fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT));
                }
            }

            // Stockage des informations de l'utilisateur dans la session
            $_SESSION['id'] = $utilisateur_trouve['id'];
            $_SESSION['nom'] = $utilisateur_trouve['nom'];
            $_SESSION['prenom'] = $utilisateur_trouve['prenom'];
            $_SESSION['login'] = $utilisateur_trouve['login'];
            $_SESSION['role'] = $utilisateur_trouve['role'];
            $_SESSION['statut'] = $utilisateur_trouve['statut'] ?? 'Regular'; 
            $_SESSION['telephone'] = $utilisateur_trouve['telephone'] ?? 'Non renseigné';
            $_SESSION['adresse'] = $utilisateur_trouve['adresse'] ?? 'Non renseignée';
            $_SESSION['points'] = $utilisateur_trouve['points'] ?? 0;
            
            // Redirection en fonction du rôle de l'utilisateur
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
        }else {
            header("Location: ../connexion.php?erreur=1");
            exit();
        }
    }
}
//Ce fichier lit les identifiants, fouille le fichier JSON et utilise password_verify pour vérifier l'empreinte du mot de passe + gestion du blocage et du bonus d'anniversaire
?>