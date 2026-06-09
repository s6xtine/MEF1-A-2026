<?php
// Dit au navigateur que la réponse est du JSON et pas du HTML
header('Content-Type: application/json');

$fichier_carte = '../data/carte.json';

if (!file_exists($fichier_carte)) {
    echo json_encode([]);
    exit;
}
// Lit le fichier et convertit le JSON en tableau PHP
// true = tableau associatif, sans true ce serait un objet
$donnees = json_decode(file_get_contents($fichier_carte), true);
$plats = $donnees['plats'] ?? [];


// RÉCUPÉRATION DES FILTRES ENVOYÉS PAR LE JS
// $_GET récupère les paramètres dans l'URL
// ex: ?categorie=boissons&tag=vegan&gout=sucre
// ?? '' = si le paramètre n'est pas dans l'URL, on met une chaîne vide
$filtre_categorie = $_GET['categorie'] ?? '';
$filtre_tag       = $_GET['tag']       ?? '';   // ← tag au lieu de tags
$filtre_gout      = $_GET['gout']      ?? '';

// ==========================================
// APPLICATION DES FILTRES
// array_filter = parcourt tous les plats et garde seulement ceux qui passent les conditions
// La fonction retourne true = le plat est gardé, false = il est supprimé
// use() = permet d'utiliser les variables de filtres à l'intérieur de la fonction
// ==========================================
$resultats = array_filter($plats, function($plat) use ($filtre_categorie, $filtre_tag, $filtre_gout) {

    // Filtre catégorie
    // Si un filtre est actif ET que le plat n'a pas la bonne catégorie → on l'exclut
    if ($filtre_categorie !== '' && $plat['categorie'] !== $filtre_categorie) {
        return false;
    }

    // Filtre goût
    if ($filtre_gout !== '' && $plat['gout'] !== $filtre_gout) {
        return false;
    }

    // Filtre tag (un seul tag)
    //in_array = vérifie que le tag demandé est bien dans le tableau tags du plat
    // !isset = sécurité si un plat n'a pas de champ tags dans le JSON
    if ($filtre_tag !== '') {
        if (!isset($plat['tags']) || !in_array($filtre_tag, $plat['tags'])) {
            return false;
        }
    }

    return true;
});
// Convertit le tableau PHP en JSON et l'envoie au JavaScript
echo json_encode(array_values($resultats));