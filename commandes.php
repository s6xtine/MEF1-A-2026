<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'restaurateur') {
    header('Location: connexion.php');
    exit();
}
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


$commandes_payees = [];       // Modifiables par le client
$commandes_en_prepa = [];     // En cuisine (Bloquées pour le client)
$commandes_pretes = [];       // ici qu'on choisit le livreur
$commandes_livrees = [];      //quand le livreur lui a validé la livraison

// donc la juste le tri
foreach ($toutes_les_commandes as $cmd) {
    if ($cmd['statut'] === 'payé' || $cmd['statut'] === 'En préparation') {
        $commandes_payees[] = $cmd;
    } elseif ($cmd['statut'] === 'en préparation') {
        $commandes_en_prepa[] = $cmd;
    } elseif ($cmd['statut'] === 'prêt') {
        $commandes_pretes[] = $cmd;
    } elseif ($cmd['statut'] === 'livré') {
        $commandes_livrees[] = $cmd; 
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

        <section id="commandes-payees">
            <h4 class="sub-titre">🌸 Nouvelles Commandes (Payées - Modifiables par le client)</h4>
            <table>
                <tr>
                    <th>N° Commande</th>
                    <th>Détails des plats</th>
                    <th>Prévu pour</th>
                    <th>Action</th>
                </tr>
                <?php if (empty($commandes_payees)): ?>
                    <tr><td colspan="4" class="text-center">Aucune nouvelle commande en attente.</td></tr>
                <?php else: ?>
                    <?php foreach ($commandes_payees as $cmd): ?>
                        <tr>
                            <td><?= htmlspecialchars($cmd['id_commande']) ?></td>
                            <td class="text-left">
                                <?php foreach ($cmd['articles'] as $article) echo '<strong>' . (int)$article['quantite'] . 'x</strong> ' . htmlspecialchars($article['nom']) . '<br>'; ?>
                            </td>
                            <td><strong><?= $cmd['heure_souhaitee'] ?? '' ?></strong></td>
                            <td>
                                <form class="form-sans-boite" action="traitement/update_statut.php" method="POST">
                                    <input type="hidden" name="id_commande" value="<?= $cmd['id_commande'] ?>">
                                    <input type="hidden" name="nouveau_statut" value="en préparation">
                                    <button type="submit" class="btn-gossip btn-xs">👨‍🍳 Lancer en cuisine</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </section>

        <section id="en-cuisine">
            <h4 class="sub-titre">🍳 En cours de préparation</h4>
            <table >
                <tr>
                    <th>N° Commande</th>
                    <th>Détails</th>
                    <th>Action</th>
                </tr>
                <?php if (empty($commandes_en_prepa)): ?>
                    <tr><td colspan="3" class="text-center">Rien en cuisine pour le moment.</td></tr>
                <?php else: ?>
                    <?php foreach ($commandes_en_prepa as $cmd): ?>
                        <tr>
                            <td><?= htmlspecialchars($cmd['id_commande']) ?></td>
                            <td class="text-left">
                                <?php foreach ($cmd['articles'] as $article) echo '<strong>' . (int)$article['quantite'] . 'x</strong> ' . htmlspecialchars($article['nom']) . '<br>'; ?>
                            </td>
                            <td>
                                <form class="form-sans-boite" action="traitement/update_statut.php" method="POST">
                                    <input type="hidden" name="id_commande" value="<?= $cmd['id_commande'] ?>">
                                    <input type="hidden" name="nouveau_statut" value="prêt">
                                    <button type="submit" class="btn-gossip btn-small">🛎️ Prêt ! Appeler livreur</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </section>

        <section id="attente-livreur">
            <h4 class="sub-titre">📦 Commandes Prêtes (Assigner un Livreur)</h4>
            <table >
                <tr>
                    <th>N° Commande</th>
                    <th>Détails</th>
                    <th>Attribuer à :</th>
                </tr>
                <?php if (empty($commandes_pretes)): ?>
                    <tr><td colspan="3" class="text-center">Aucune commande en attente de transport.</td></tr>
                <?php else: ?>
                    <?php foreach ($commandes_pretes as $cmd): ?>
                        <tr>
                            <td><?= htmlspecialchars($cmd['id_commande']) ?></td>
                            <td class="text-left">
                                <?php foreach ($cmd['articles'] as $article) echo '<strong>' . (int)$article['quantite'] . 'x</strong> ' . htmlspecialchars($article['nom']) . '<br>'; ?>
                            </td>
                            <td>
                                <form action="traitement/update_statut.php" method="POST" class="form-sans-boite">
                                    <input type="hidden" name="id_commande" value="<?= $cmd['id_commande'] ?>">
                                    <input type="hidden" name="nouveau_statut" value="En livraison"> <select name="id_livreur" class="select-chic" required>
                                        <option value="">Choisir un livreur</option>
                                        <?php foreach ($liste_livreurs as $livreur): ?>
                                            <option value="<?= htmlspecialchars($livreur['prenom'] . ' ' . $livreur['nom']) ?>">
                                                <?= htmlspecialchars($livreur['prenom'] . " " . $livreur['nom']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn-gossip btn-xs">🚀 Confier et Expédier</button> 
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </section>

        <section id="terminees" class="section-historique">
            <h4 class="sub-titre">✅ Historique : Commandes Terminées</h4>
            <table >
                <tr>
                    <th>N° Commande</th>
                    <th>Livreur ayant livré</th>
                    <th>Statut</th>
                </tr>
                <?php if (empty($commandes_livrees)): ?>
                    <tr><td colspan="3" class="text-center">Aucune commande terminée pour le moment.</td></tr>
                <?php else: ?>
                    <?php foreach ($commandes_livrees as $cmd): ?>
                        <tr>
                            <td><?= htmlspecialchars($cmd['id_commande']) ?></td>
                            <td>👤 <?= htmlspecialchars($cmd['livreur'] ?? 'Non renseigné') ?></td>
                            <td class="statut-valide">✅ Livrée</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>