<?php
session_start();

$fichier_commandes = 'data/commandes.json';
$toutes_les_commandes = [];

if (file_exists($fichier_commandes)) {
    $contenu = file_get_contents($fichier_commandes);
    if (!empty($contenu)) {
        $toutes_les_commandes = json_decode($contenu, true);
        $toutes_les_commandes = array_reverse($toutes_les_commandes);
    }
}

$fichier_users = 'data/utilisateur.json';
$liste_livreurs = [];
if (file_exists($fichier_users)) {
    $users = json_decode(file_get_contents($fichier_users), true);
    foreach ($users as $u) {
        if (isset($u['role']) && $u['role'] === 'livreur') {
            $liste_livreurs[] = $u;
        }
    }
}

$commandes_a_preparer = [];
$commandes_en_livraison = [];
$commandes_terminees = []; 

foreach ($toutes_les_commandes as $cmd) {
    if ($cmd['statut'] === 'En préparation') {
        $commandes_a_preparer[] = $cmd;
    } elseif ($cmd['statut'] === 'En livraison' || $cmd['statut'] === 'En route') {
        $commandes_en_livraison[] = $cmd;
    } elseif ($cmd['statut'] === 'Livrée') {
        $commandes_terminees[] = $cmd; 
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="style.css?v=2">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sip & Spill - Gestion des Commandes</title>
</head>
<body>
    <header class="site-header">
        <h1 class="titre-page">Sip & Spill</h1>
        <h2 class="titre-section">Espace Restaurateur</h2>
    </header>

    <?php include 'nav.php'; ?>

    <main class="admin-container">
        <h3 class="sub-titre">Gestion des commandes</h3>

        <section id="a-preparer">
            <h4 class="sub-titre">Commandes à préparer</h4>
            <table class="custom-table"> <tr>
                    <th>N° Commande</th>
                    <th>Détails des plats</th>
                    <th>Prévu pour</th> <th>Action</th>
                </tr>
                
                <?php if (empty($commandes_a_preparer)): ?>
                    <tr>
                        <td colspan="4" class="text-center">Aucune commande à préparer.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($commandes_a_preparer as $cmd): ?>
                        <?php 
                           
                            $date_voulue = $cmd['date_souhaitee'] ?? date('Y-m-d');
                            $heure_voulue = $cmd['heure_souhaitee'] ?? date('H:i');
                            
                            $timestamp_voulu = strtotime("$date_voulue $heure_voulue");
                            $maintenant = time();
                            
                            
                            $est_urgent = ($timestamp_voulu - $maintenant) < 3600;
                        ?>
                        <tr class="<?= $est_urgent ? 'statut-urgent' : 'statut-differe' ?>">
                            <td><?= htmlspecialchars($cmd['id_commande']) ?></td>
                            <td class="text-left">
                                <?php 
                                $details_plats = [];
                                foreach ($cmd['articles'] as $article) {
                                    $details_plats[] = '<strong>' . $article['quantite'] . 'x</strong> ' . htmlspecialchars($article['nom']);
                                }
                                echo implode('<br>', $details_plats);
                                ?>
                            </td>
                            <td>
                                <strong><?= date('d/m', strtotime($date_voulue)) ?> à <?= $heure_voulue ?></strong>
                                <br>
                                <small><?= $est_urgent ? '🔥 PRIORITAIRE' : '⏳ À PRÉPARER PLUS TARD' ?></small>
                            </td>
                            <td>
                                <form action="traitement/update_statut.php" method="POST" class="form-discret">
                                    <input type="hidden" name="id_commande" value="<?= $cmd['id_commande'] ?>">
                                    <input type="hidden" name="nouveau_statut" value="En livraison">

                                    <select name="id_livreur" class="select-chic" required>
                                        <option value="">Choisir un livreur</option>
                                        <?php foreach ($liste_livreurs as $livreur): ?>
                                            <option value="<?= htmlspecialchars($livreur['prenom'] . ' ' . $livreur['nom']) ?>">
                                                <?= htmlspecialchars($livreur['prenom'] . " " . $livreur['nom']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <button type="submit" class="btn-edit">Lancer la livraison</button> 
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </section>

        <section id="en-livraison">
            <h4 class="sub-titre">Commandes en cours de livraison</h4>
            <table>
                <tr>
                    <th>N° Commande</th>
                    <th>Livreur</th>
                    <th>Destination</th>
                    <th>Statut</th>
                </tr>
                
                <?php if (empty($commandes_en_livraison)): ?>
                    <tr>
                        <td colspan="4" class="text-center">Aucune commande en livraison.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($commandes_en_livraison as $cmd): ?>
                        <tr>
                            <td><?= htmlspecialchars($cmd['id_commande']) ?></td>
                            <td><?= htmlspecialchars($cmd['livreur'] ?? 'Non assigné') ?></td> 
                            <td><?= htmlspecialchars($cmd['client']) ?></td>
                            <td><?= htmlspecialchars($cmd['statut']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

            </table>
        </section>

        <section id="terminees" class="section-historique">
            <h4 class="sub-titre">Historique : Commandes Terminées</h4>
            <table class="table-historique">
                <tr>
                    <th>N° Commande</th>
                    <th>Client</th>
                    <th>Livreur</th>
                    <th>Statut</th>
                </tr>
                
                <?php if (empty($commandes_terminees)): ?>
                    <tr>
                        <td colspan="4" class="text-center">Aucune commande terminée.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($commandes_terminees as $cmd): ?>
                        <tr>
                            <td><?= htmlspecialchars($cmd['id_commande']) ?></td>
                            <td><?= htmlspecialchars($cmd['client']) ?></td>
                            <td><?= htmlspecialchars($cmd['livreur'] ?? 'Non assigné') ?></td>
                            <td class="statut-valide">✅ <?= htmlspecialchars($cmd['statut']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

            </table>
        </section>
    </main>

    <?php include 'footer.php'; ?>