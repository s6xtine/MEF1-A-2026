<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_commande = $_POST['id_commande'] ?? '';
    $nouveau_statut = $_POST['nouveau_statut'] ?? '';
    // On récupère le nom du livreur choisi dans le select
    $nom_livreur = $_POST['id_livreur'] ?? ''; 

    if (!empty($id_commande)) {
        $fichier_commandes = '../data/commandes.json';

        if (file_exists($fichier_commandes)) {
            $commandes = json_decode(file_get_contents($fichier_commandes), true);

            foreach ($commandes as $index => $cmd) {
                if ($cmd['id_commande'] === $id_commande) {
                    
                    // On change le statut pour faire avancer la commande
                    if (!empty($nouveau_statut)) {
                        $commandes[$index]['statut'] = $nouveau_statut;
                    }
                    
                    
                    if (!empty($nom_livreur)) {
                        $commandes[$index]['livreur'] = $nom_livreur;
                    }
                    break; 
                }
            }
            file_put_contents($fichier_commandes, json_encode($commandes, JSON_PRETTY_PRINT));
        }
    }
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../index.php'));
exit();