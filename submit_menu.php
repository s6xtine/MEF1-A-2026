<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'restaurateur') {
    header('Location: connexion.php');
    exit();
}

$file = 'data/carte.json';
$data = json_decode(file_get_contents($file), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'ajouter') {
    
    
    $allergenesArray = [];
    if (!empty($_POST['allergenes'])) {
        $allergenesArray = array_map('trim', explode(',', $_POST['allergenes']));
    }

    
    $prefixe = $_POST['prefixe_id'];
    $compteur = 1;
    foreach ($data['plats'] as $p) {
        if (strpos($p['id'], $prefixe) === 0) $compteur++;
    }
    $newId = $prefixe . str_pad($compteur, 2, "0", STR_PAD_LEFT);

    
    $nouveauPlat = [
        "id" => $newId,
        "categorie" => $_POST['categorie'],
        "nom" => mb_strtoupper(htmlspecialchars($_POST['nom'])), // Force majuscules comme tes exemples
        "description" => htmlspecialchars($_POST['description']),
        "prix" => (float)$_POST['prix'],
        "allergenes" => $allergenesArray,
        "image" => filter_var($_POST['image'], FILTER_SANITIZE_URL)
    ];

    
    $data['plats'][] = $nouveauPlat;

    
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    header('Location: modif_menu.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'supprimer') {
    $idASupprimer = $_POST['id'];

    foreach ($data['plats'] as $index => $plat) {
        if ($plat['id'] === $idASupprimer) {
            unset($data['plats'][$index]);
            break;
        }
    }

    $data['plats'] = array_values($data['plats']);

    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    header('Location: modif_menu.php');
    exit();
}

?>