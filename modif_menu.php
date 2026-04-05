<?php
session_start();

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'restaurateur' && $_SESSION['role'] !== 'admin')) {
    header('Location: connexion.php');
    exit();
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
            <h2 class="sub-titre text-center">Gestion de la Carte</h2>
            
            <form action="submit_menu.php" method="POST">
                <fieldset>
                    <legend>Ajouter un nouveau plat</legend>
                
                    <label>Catégorie (Préfixe ID) :</label>
                    <select name="prefixe_id" class="select-chic" required>
                        <option value="B">Boisson Chaude</option>
                        <option value="S">Boisson Froide</option>
                        <option value="P">Plat Salé</option>
                        <option value="D">Dessert Sucré</option>
                    </select>

                    <label>Type :</label>
                    <select name="categorie" class="select-chic" required>
                        <option value="boissons">Boissons</option>
                        <option value="sale">Salé</option>
                        <option value="sucre">Sucré</option>
                    </select>

                    <label>Nom du plat :</label>
                    <input type="text" name="nom" placeholder="NOM DU PLAT" required>
                
                    <label>Description :</label>
                    <textarea name="description" placeholder="Description complète..." required></textarea>
                
                    <label>Prix (€) :</label>
                    <input type="number" step="0.01" name="prix" placeholder="Prix (ex: 15.00)" required>
                
                    <label>Allergènes :</label>
                    <input type="text" name="allergenes" placeholder="Allergènes (ex: gluten, oeuf, lactose)">
                
                    <label>Image :</label>
                    <input type="url" name="image" placeholder="URL de l'image (https://...)">

                    <button type="submit" name="action" value="ajouter" class="btn-geant">Ajouter à la carte</button>
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
                    <?php 
                    $data = json_decode(file_get_contents('data/carte.json'), true);
                    $plats = $data['plats'] ?? [];
                    foreach ($plats as $index => $plat) : 
                    ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($plat['nom']) ?></strong></td>
                        <td><?= number_format($plat['prix'], 2, ',', ' ') ?> €</td>
                        <td class="desc-plat"><?= htmlspecialchars($plat['description']) ?></td>
                        <td>
                            <a href="modif_menu.php?id=<?= $plat['id'] ?>" class="btn-edit">Modifier</a>
                            
                            <form action="submit_menu.php" method="POST" class="form-action-rapide">
                                <input type="hidden" name="id" value="<?= $plat['id'] ?>">
                                <button type="submit" name="action" value="supprimer" class="btn-edit btn-supprimer" onclick="return confirm('Supprimer ce plat ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <div class="footer-bottom">
            <p class="footer-mentions">&copy; 2026 SIP AND SPILL</p>
        </div>
    </footer>
</body>
</html>