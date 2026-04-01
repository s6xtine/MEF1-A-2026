<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'restaurateur') {
    header('Location: connexion.php');
    exit();
}

?>

<section class="gestion-menu">
    <h2>Gestion de la Carte</h2>
    
    <form action="submit_menu.php" method="POST" class="form-ajout">
        <h3>Ajouter un nouveau plat</h3>
    
        <select name="prefixe_id" required>
            <option value="B">Boisson Chaude</option>
            <option value="S">Boisson Froide</option>
            <option value="P">Plat Salé</option>
            <option value="D">Dessert Sucré</option>
        </select>

        <select name="categorie" required>
            <option value="boissons">boissons</option>
            <option value="sale">sale</option>
            <option value="sucre">sucre</option>
        </select>

        <input type="text" name="nom" placeholder="NOM DU PLAT" required>
    
        <textarea name="description" placeholder="Description complète..." required></textarea>
    
        <input type="number" step="0.01" name="prix" placeholder="Prix (ex: 15.00)" required>
    
        <input type="text" name="allergenes" placeholder="Allergènes (ex: gluten, oeuf, lactose)">
    
        <input type="url" name="image" placeholder="URL de l'image (https://...)">

        <button type="submit" name="action" value="ajouter">Ajouter à la carte</button>
    </form>

    <hr>

    <table>
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
                <td><?= htmlspecialchars($plat['nom']) ?></td>
                <td><?= $plat['prix'] ?> €</td>
                <td><?= htmlspecialchars($plat['description']) ?></td>
                <td>
                    <a href="modif_menu.php?id=<?= $plat['id'] ?>" class="btn-edit">Modifier</a>
                    
                    <form action="submit_menu.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $plat['id'] ?>">
                        <button type="submit" name="action" value="supprimer" onclick="return confirm('Supprimer ce plat ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

