<?php
session_start();

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'restaurateur' && $_SESSION['role'] !== 'admin')) {
    header('Location: connexion.php');
    exit();
}

// On charge la carte existante
$fichier_carte = 'data/carte.json';
$data = json_decode(file_get_contents($fichier_carte), true);
$plats = $data['plats'] ?? [];

// On cherche si on a cliqué sur "MODIFIER" (si un ID est dans l'URL)
$plat_a_modifier = null;
if (isset($_GET['id'])) {
    foreach ($plats as $p) {
        if ($p['id'] === $_GET['id']) {
            $plat_a_modifier = $p;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sip & Spill - Modifier la Carte</title>
    <link rel="stylesheet" href="style.css?v=7">
</head>
<body>

    <header class="site-header">
        <h1 class="titre-page">Sip & Spill</h1>
        <h2 class="titre-section">Espace Restaurateur</h2>
    </header>

    <?php include 'nav.php'; ?>

    <main class="admin-container">
        <section class="gestion-menu">
            
            <h2 class="sub-titre text-center">
                <?= $plat_a_modifier ? "Modifier le plat : " . htmlspecialchars($plat_a_modifier['nom']) : "Gestion de la Carte" ?>
            </h2>
            
            <form action="traitement/submit_menu.php" method="POST">
                
                <?php if ($plat_a_modifier): ?>
                    <input type="hidden" name="id" value="<?= $plat_a_modifier['id'] ?>">
                <?php endif; ?>

                <fieldset>
                    <legend><?= $plat_a_modifier ? "Modifier les informations" : "Ajouter un nouveau plat" ?></legend>
                
                    <label>Catégorie (Préfixe ID) :</label>
                    <select name="prefixe_id" class="select-chic" required>
                        <option value="B" <?= ($plat_a_modifier && strpos($plat_a_modifier['id'], 'B') === 0) ? 'selected' : '' ?>>Boisson Chaude</option>
                        <option value="S" <?= ($plat_a_modifier && strpos($plat_a_modifier['id'], 'S') === 0) ? 'selected' : '' ?>>Boisson Froide</option>
                        <option value="P" <?= ($plat_a_modifier && strpos($plat_a_modifier['id'], 'P') === 0) ? 'selected' : '' ?>>Plat Salé</option>
                        <option value="D" <?= ($plat_a_modifier && strpos($plat_a_modifier['id'], 'D') === 0) ? 'selected' : '' ?>>Dessert Sucré</option>
                    </select>

                    <label>Type :</label>
                    <select name="categorie" class="select-chic" required>
                        <option value="boissons" <?= ($plat_a_modifier && isset($plat_a_modifier['categorie']) && $plat_a_modifier['categorie'] === 'boissons') ? 'selected' : '' ?>>Boissons</option>
                        <option value="sale" <?= ($plat_a_modifier && isset($plat_a_modifier['categorie']) && $plat_a_modifier['categorie'] === 'sale') ? 'selected' : '' ?>>Salé</option>
                        <option value="sucre" <?= ($plat_a_modifier && isset($plat_a_modifier['categorie']) && $plat_a_modifier['categorie'] === 'sucre') ? 'selected' : '' ?>>Sucré</option>
                    </select>

                    <label>Nom du plat :</label>
                    <input type="text" name="nom" placeholder="NOM DU PLAT" value="<?= $plat_a_modifier ? htmlspecialchars($plat_a_modifier['nom']) : '' ?>" required>
                
                    <label>Description :</label>
                    <textarea name="description" placeholder="Description complète..." required><?= $plat_a_modifier ? htmlspecialchars($plat_a_modifier['description']) : '' ?></textarea>
                
                    <label>Prix (€) :</label>
                    <input type="number" step="0.01" name="prix" placeholder="Prix (ex: 15.00)" value="<?= $plat_a_modifier ? $plat_a_modifier['prix'] : '' ?>" required>
                
                    <label>Allergènes :</label>
                    <input type="text" name="allergenes" placeholder="Allergènes (ex: gluten, oeuf, lactose)" value="<?= ($plat_a_modifier && isset($plat_a_modifier['allergenes'])) ? htmlspecialchars(implode(', ', (array)$plat_a_modifier['allergenes'])) : '' ?>">
                
                    <label>Image :</label>
                    <input type="url" name="image" placeholder="URL de l'image (https://...)" value="<?= $plat_a_modifier ? htmlspecialchars($plat_a_modifier['image']) : '' ?>">

                    <?php if ($plat_a_modifier): ?>
                        <button type="submit" name="action" value="modifier" class="btn-gossip">Enregistrer les modifications</button>
                        <a href="modif_menu.php" class="btn-gossip text-center" style="display:block; margin-top:10px; text-decoration:none;">Annuler la modification</a>
                    <?php else: ?>
                        <button type="submit" name="action" value="ajouter" class="btn-gossip">Ajouter à la carte</button>
                    <?php endif; ?>
                </fieldset>
            </form>

            <hr class="separateur-chic">

            <h3 class="sub-titre text-center">Plats actuellement à la carte</h3>
            <table class="table-panier">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($plats as $plat) : ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($plat['nom']) ?></strong></td>
                        <td><?= number_format($plat['prix'], 2, ',', ' ') ?> €</td>
                        <td class="desc-plat"><?= htmlspecialchars($plat['description']) ?></td>
                        <td>
                            <a href="modif_menu.php?id=<?= $plat['id'] ?>" class="btn-gossip btn-xs">Modifier</a>
                            
                            <form action="traitement/submit_menu.php" method="POST" class="form-sans-boite">
                                <input type="hidden" name="id" value="<?= $plat['id'] ?>">
                                <button type="submit" name="action" value="supprimer" class="btn-gossip btn-xs" onclick="return confirm('Supprimer ce plat ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <?php include 'footer.php'; ?>
    <!-- Le code sert à modifier la carte du restaurant : il permet de modifier les informations d'un plat existant ou d'en ajouter un nouveau ou de supprimer des plats existants -->
</body>
</html>