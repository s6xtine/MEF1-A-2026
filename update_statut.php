<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_commande = trim($_POST['id_commande'] ?? '');
    $nouveau_statut = trim($_POST['nouveau_statut'] ?? '');
    $nom_livreur = trim($_POST['id_livreur'] ?? 'Non assigné');

    if (!empty($id_commande) && !empty($nouveau_statut)) {
        
        $fichier_commandes = 'data/commandes.json';

        if (file_exists($fichier_commandes)) {
            $commandes = json_decode(file_get_contents($fichier_commandes), true);

            if (is_array($commandes)) {
                
                foreach ($commandes as $index => $cmd) {
                    if (trim($cmd['id_commande']) === $id_commande) {
                        // On modifie le statut et le livreur
                        $commandes[$index]['statut'] = $nouveau_statut;
                        $commandes[$index]['livreur'] = $nom_livreur;
                        break; 
                    }
                }

                // On sauvegarde !
                file_put_contents($fichier_commandes, json_encode($commandes, JSON_PRETTY_PRINT));
            }
        }
    }
}

// On retourne sur le tableau de bord
$page_precedente = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header('Location: ' . $page_precedente);
exit();
?>