<?php
session_start();

$fichier_commandes = 'data/commandes.json';
$toutes_les_commandes = [];

if (file_exists($fichier_commandes)) {
    $contenu = file_get_contents($fichier_commandes);
    if (!empty($contenu)) {
        $toutes_les_commandes = json_decode($contenu, true);
        // On inverse pour avoir les plus récentes en premier
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

foreach ($toutes_les_commandes as $cmd) {
    if ($cmd['statut'] === 'En préparation') {
        $commandes_a_preparer[] = $cmd;
    } elseif ($cmd['statut'] === 'En livraison' || $cmd['statut'] === 'En route') {
        $commandes_en_livraison[] = $cmd;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sip & Spill - Gestion des Commandes</title>
</head>
<body>
    <header class="site-header">
        <h1 class="titre-page">Sip & Spill</h1>
        <h2 class="titre-section">Espace Restaurateur</h2>
    </header>

    <nav class="main-nav">
        <a href="index.php">Accueil</a> | 
        <a href="modif_menu.php">Modifier la carte</a> |
        <a href="deconnexion.php">Déconnexion</a>
    </nav>

    <main class="admin-container">
        <h3 class="sub-titre">Gestion des commandes</h3>

        <section id="a-preparer">
            <h4 class="sub-titre">Commandes à préparer</h4>
            <table border="1" style="width: 100%; text-align: left;">
                <tr>
                    <th>N° Commande</th>
                    <th>Détails des plats</th>
                    <th>Heure de commande</th>
                    <th>Action</th>
                </tr>
                
                <?php if (empty($commandes_a_preparer)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">Aucune commande à préparer.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($commandes_a_preparer as $cmd): ?>
                        <tr>
                            <td><?= htmlspecialchars($cmd['id_commande']) ?></td>
                            <td>
                                <?php 
                                
                                $details_plats = [];
                                foreach ($cmd['articles'] as $article) {
                                    $details_plats[] = $article['quantite'] . 'x ' . $article['nom'];
                                }
                                echo htmlspecialchars(implode(', ', $details_plats));
                                ?>
                            </td>
                            <td><?= date('H:i', strtotime($cmd['date'])) ?></td>
                            <td>
                                <form action="update_statut.php" method="POST">
                                    <input type="hidden" name="id_commande" value="<?= $cmd['id_commande'] ?>">
                                    <input type="hidden" name="nouveau_statut" value="En livraison">

                                    <select name="id_livreur" style="margin-bottom: 10px; display: block;" required>
                                        <option value="">-- Choisir un livreur --</option>
                                        <?php foreach ($liste_livreurs as $livreur): ?>
                                            <option value="<?= $livreur['id'] ?>">
                                                <?= htmlspecialchars($livreur['prenom'] . " " . $livreur['nom']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <button type="submit">Passer en livraison</button> 
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                
            </table>
        </section>

        <section id="en-livraison">
            <h4 class="sub-titre">Commandes en cours de livraison</h4>
            <table border="1" style="width: 100%; text-align: left;">
                <tr>
                    <th>N° Commande</th>
                    <th>Livreur</th>
                    <th>Destination</th>
                    <th>Statut</th>
                </tr>
                
                <?php if (empty($commandes_en_livraison)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">Aucune commande en livraison.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($commandes_en_livraison as $cmd): ?>
                        <tr>
                            <td><?= htmlspecialchars($cmd['id_commande']) ?></td>
                            <td>Non assigné</td> <td><?= htmlspecialchars($cmd['client']) ?></td>
                            <td><?= htmlspecialchars($cmd['statut']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

            </table>
        </section>
    </main>

    <footer>
        <div class="footer-bottom">
            <p>© 2026 SIP AND SPILL · brunch de 9h à 16h</p>
        </div>
    </footer>
</body>
</html>