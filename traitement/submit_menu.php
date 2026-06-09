<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'restaurateur' && $_SESSION['role'] !== 'admin') {
    header('Location: ../connexion.php');
    exit();
}

$file = '../data/carte.json';
$data = json_decode(file_get_contents($file), true);

// Ajouter un nouveau plat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'ajouter') {
    
    // On transforme la chaîne d'allergènes en tableau propre
    $allergenesArray = [];
    if (!empty($_POST['allergenes'])) {
        $allergenesArray = array_map('trim', explode(',', $_POST['allergenes']));
    }

    //on parcourt les plats existants pour trouver le nombre de plats déjà présents avec le même préfixe d'ID (B, S, D, etc.) et on incrémente ce nombre pour créer un nouvel ID unique
    $prefixe = $_POST['prefixe_id'];
    $compteur = 1;
    foreach ($data['plats'] as $p) {
        if (strpos($p['id'], $prefixe) === 0) $compteur++;
    }
    $newId = $prefixe . str_pad($compteur, 2, "0", STR_PAD_LEFT);

    $nouveauPlat = [
        "id" => $newId,
        "categorie" => $_POST['categorie'],
        "nom" => mb_strtoupper(htmlspecialchars($_POST['nom'])),
        "description" => htmlspecialchars($_POST['description']),
        "prix" => (float)$_POST['prix'],
        "allergenes" => $allergenesArray,
        "image" => filter_var($_POST['image'], FILTER_SANITIZE_URL)
    ];

    $data['plats'][] = $nouveauPlat;

    // Sauvegarde dans le fichier JSON
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: ../modif_menu.php');
    exit();
}

// Modifier un plat existant
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'modifier') {
    $idAModifier = $_POST['id'];

    // On transforme la chaîne d'allergènes en tableau propre
    $allergenesArray = [];
    if (!empty($_POST['allergenes'])) {
        $allergenesArray = array_map('trim', explode(',', $_POST['allergenes']));
    }

    // On cherche le plat par son ID et on remplace ses valeurs
    foreach ($data['plats'] as $index => $plat) {
        if ($plat['id'] === $idAModifier) {
            $data['plats'][$index] = [
                "id" => $idAModifier, // L'ID ne change pas
                "categorie" => $_POST['categorie'],
                "nom" => mb_strtoupper(htmlspecialchars($_POST['nom'])),
                "description" => htmlspecialchars($_POST['description']),
                "prix" => (float)$_POST['prix'],
                "allergenes" => $allergenesArray,
                "image" => filter_var($_POST['image'], FILTER_SANITIZE_URL)
            ];
            break;
        }
    }

    // Sauvegarde dans le fichier JSON
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    // On redirige vers la page principale nettoyée de l'ID de modification
    header('Location: ../modif_menu.php');
    exit();
}

// Supprimer un plat existant
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'supprimer') {
    $idASupprimer = $_POST['id'];

    foreach ($data['plats'] as $index => $plat) {
        if ($plat['id'] === $idASupprimer) {
            // On supprime le plat du tableau
            unset($data['plats'][$index]);
            break;
        }
    }

    //array_values pour réindexer le tableau des plats après suppression (pour éviter les trous dans les index et préserver la stucture de liste JSON)
    $data['plats'] = array_values($data['plats']);

    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: ../modif_menu.php');
    exit();
}
// Ce fichier gère les soumissions du formulaire de modification de la carte (espace restaurateur)
?>