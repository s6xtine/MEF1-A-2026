//gère si un utilisateur est bloqué ou pas, et met à jour le fichier json en conséquence
<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_cible'])) {
    
    $login_cible = $_POST['login_cible'];
    $chemin_fichier = '../data/utilisateur.json';
    
    if (file_exists($chemin_fichier)) {
        $utilisateurs = json_decode(file_get_contents($chemin_fichier), true);
        $trouve = false;
        $nouvel_etat = false;

        foreach ($utilisateurs as &$user) {
            if ($user['login'] === $login_cible) {
                // Si la clé n'existe pas, on la crée
                if (!isset($user['bloque'])) {
                    $user['bloque'] = false;
                }
                
                // On inverse l'état (Si bloqué -> Débloqué, etc.)
                $user['bloque'] = !$user['bloque'];
                $nouvel_etat = $user['bloque'];
                $trouve = true;
                break;
            }
        }

        if ($trouve) {
            file_put_contents($chemin_fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT));
            // On répond au JS que tout s'est bien passé
            echo json_encode(['success' => true, 'est_bloque' => $nouvel_etat]);
            exit();
        }
    }
}

//Si il y a un problème
echo json_encode(['success' => false]);
exit();
?>