<?php
session_start();

// 1. Sécurité anti-intrus : on vérifie que c'est bien l'admin !
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// 2. Si on a bien reçu le formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On récupère les infos envoyées par le formulaire
    $id_cible = $_POST['id'] ?? '';
    $nouveau_statut = $_POST['statut'] ?? 'Regular';
    $nouveaux_points = (int)($_POST['points'] ?? 0);
    $nouveau_bon = trim($_POST['bon_achat'] ?? '');

    if (!empty($id_cible)) {
        // On remonte d'un cran pour trouver le dossier data
        $chemin_json = '../data/utilisateur.json';

        if (file_exists($chemin_json)) {
            $utilisateurs = json_decode(file_get_contents($chemin_json), true);

            if (is_array($utilisateurs)) {
                // On cherche notre utilisateur dans la liste
                foreach ($utilisateurs as $index => $user) {
                    if ((string)$user['id'] === (string)$id_cible) {
                        // BINGO ! On met à jour ses infos
                        $utilisateurs[$index]['statut'] = $nouveau_statut;
                        $utilisateurs[$index]['points'] = $nouveaux_points;
                        $utilisateurs[$index]['bon_achat'] = $nouveau_bon;
                        break; // On a fini, on arrête de chercher
                    }
                }

                // On sauvegarde le fichier JSON mis à jour
                file_put_contents($chemin_json, json_encode($utilisateurs, JSON_PRETTY_PRINT));
            }
        }
    }
}

// 3. On ramène l'admin sur son tableau de bord
header('Location: ../administrateur.php');
exit();
?>