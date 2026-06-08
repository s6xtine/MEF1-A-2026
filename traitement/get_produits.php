<?php
header('Content-Type: application/json');

$fichier_carte = '../data/carte.json';

if (!file_exists($fichier_carte)) {
    echo json_encode([]);
    exit;
}

$donnees = json_decode(file_get_contents($fichier_carte), true);
$plats = $donnees['plats'] ?? [];

// Récupération des filtres
$filtre_categorie = $_GET['categorie'] ?? '';
$filtre_tag       = $_GET['tag']       ?? '';   // ← tag au lieu de tags
$filtre_gout      = $_GET['gout']      ?? '';

// Application des filtres
$resultats = array_filter($plats, function($plat) use ($filtre_categorie, $filtre_tag, $filtre_gout) {

    // Filtre catégorie
    if ($filtre_categorie !== '' && $plat['categorie'] !== $filtre_categorie) {
        return false;
    }

    // Filtre goût
    if ($filtre_gout !== '' && $plat['gout'] !== $filtre_gout) {
        return false;
    }

    // Filtre tag (un seul tag)
    if ($filtre_tag !== '') {
        if (!isset($plat['tags']) || !in_array($filtre_tag, $plat['tags'])) {
            return false;
        }
    }

    return true;
});

echo json_encode(array_values($resultats));