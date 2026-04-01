<?php
session_start();


// 2. On vérifie qu'on a bien reçu les données du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_commande = $_POST['id_commande'] ?? '';
    $nouveau_statut = $_POST['nouveau_statut'] ?? '';

    // Si on a bien un ID et un statut
    if (!empty($id_commande) && !empty($nouveau_statut)) {
        
        $fichier_commandes = 'data/commandes.json';

        // 3. On ouvre le carnet de commandes
        if (file_exists($fichier_commandes)) {
            $commandes = json_decode(file_get_contents($fichier_commandes), true);

            if (is_array($commandes)) {
                // 4. On fouille dans toutes les commandes pour trouver la bonne
                foreach ($commandes as $index => $cmd) {
                    if ($cmd['id_commande'] === $id_commande) {
                        // BINGO ! On modifie le statut de cette commande précise
                        $commandes[$index]['statut'] = $nouveau_statut;
                        break; // On arrête de fouiller, on a trouvé
                    }
                }

                // 5. On referme le carnet et on sauvegarde
                file_put_contents($fichier_commandes, json_encode($commandes, JSON_PRETTY_PRINT));
            }
        }
    }
}

// 6. Quoi qu'il arrive, on renvoie le restaurateur sur son tableau de bord
header('Location: commandes.php');
exit();
?>