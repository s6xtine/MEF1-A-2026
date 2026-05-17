<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['login'])) {
    $id_commande = $_POST['id_commande'] ?? '';
    $nom = trim($_POST['nom'] ?? '');
    $note = intval($_POST['note'] ?? 5);
    $commentaire = trim($_POST['commentaire'] ?? '');

    if (!empty($id_commande) && !empty($nom) && !empty($commentaire)) {
        
        $fichier_avis = 'data/avis.json';
        $liste_avis = [];
        if (file_exists($fichier_avis)) {
            $liste_avis = json_decode(file_get_contents($fichier_avis), true) ?? [];
        }
        
        $liste_avis[] = [
            "id_commande" => $id_commande,
            "nom" => $nom,
            "note" => $note,
            "commentaire" => $commentaire,
            "date" => date('d/m/Y H:i')
        ];
        file_put_contents($fichier_avis, json_encode($liste_avis, JSON_PRETTY_PRINT));

        $fichier_commandes = 'data/commandes.json';
        if (file_exists($fichier_commandes)) {
            $commandes = json_decode(file_get_contents($fichier_commandes), true) ?? [];
            foreach ($commandes as &$cmd) {
                if ($cmd['id_commande'] == $id_commande) {
                    $cmd['notee'] = true; // La commande est marquée comme évaluée !
                    break;
                }
            }
            file_put_contents($fichier_commandes, json_encode($commandes, JSON_PRETTY_PRINT));
        }

        header('Location: index.php?succes=avis');
        exit();
    }
}

header('Location: index.php');
exit();