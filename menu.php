<?php
session_start();

$fichier_carte = 'data/carte.json';
$plats = [];
$menus_brunch = [];

if (file_exists($fichier_carte)) {
    $contenu = file_get_contents($fichier_carte);
    $donnees = json_decode($contenu, true);
    
    if (isset($donnees['plats'])) {
        $plats = $donnees['plats'];
    }
    if (isset($donnees['menus'])) {
        $menus_brunch = $donnees['menus'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Carte · SIP AND SPILL</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <header class="form-header">
        <a href="index.php" class="logo-mini">Sip & Spill</a>
    </header>
    
    <nav class="main-nav">
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="menu.php" style="color: var(--red-gossip); font-weight: bold;">Notre Carte</a></li>
            <li><a href="panier.php">🛒 Mon Panier</a></li>
        </ul>
    </nav>

    <?php if (isset($_GET['ajout']) && $_GET['ajout'] === 'succes'): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 15px; text-align: center; font-weight: bold; border-bottom: 2px solid #c3e6cb;">
            ✨ Ajouté au panier avec succès !
        </div>
    <?php endif; ?>

    <h1 class="titre-centre">Notre Carte</h1>

    <main class="menu">

        <div class="menu-nav">
            <a href="#menus">✨ Menus</a>
            <a href="#boissons">☕ Boissons</a>
            <a href="#sale">🥑 Salé</a>
            <a href="#sucre">🧁 Sucré</a>
        </div>

        <section id="menus" class="menu-section">
            <h2>✨ Les Formules</h2>
            <ul class="menu-list">
                <?php foreach ($menus_brunch as $menu): ?>
                    <li>
                        <div class="plat-infos">
                            <span class="item-nom"><?= htmlspecialchars($menu['nom']) ?></span>
                            <span class="item-desc"><?= htmlspecialchars($menu['description']) ?></span>
                            <span class="prix"><?= number_format($menu['prix_total'], 2, ',', ' ') ?>€</span>
                            
                            <form action="ajoute_panier.php" method="POST" style="margin-top: 15px;">
                                <input type="hidden" name="id_produit" value="<?= htmlspecialchars($menu['id']) ?>">
                                <input type="hidden" name="nom" value="<?= htmlspecialchars($menu['nom']) ?>">
                                <input type="hidden" name="prix" value="<?= htmlspecialchars($menu['prix_total']) ?>">
                                
                                <label for="quantite_<?= $menu['id'] ?>">Qté :</label>
                                <input type="number" id="quantite_<?= $menu['id'] ?>" name="quantite" value="1" min="1" max="10" style="width: 50px; text-align: center; border-radius: 10px; border: 1px solid var(--pink-border);">
                                
                                <button type="submit" class="btn-order" style="margin-top: 10px; width: 100%;">Ajouter au panier</button>
                            </form>

                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section id="boissons" class="menu-section">
            <h2>☕ Boissons</h2>
            <ul class="menu-list">
                <?php foreach ($plats as $plat): ?>
                    <?php if ($plat['categorie'] === 'boissons'): ?>
                        <li class="plat-avec-image">
                            <img src="<?= htmlspecialchars($plat['image']) ?>" alt="<?= htmlspecialchars($plat['nom']) ?>" class="plat-image">
                            <div class="plat-infos">
                                <span class="item-nom"><?= htmlspecialchars($plat['nom']) ?></span> 
                                <span class="item-desc"><?= htmlspecialchars($plat['description']) ?></span>
                                <span class="prix"><?= number_format($plat['prix'], 2, ',', ' ') ?>€</span>
                                
                                <form action="ajoute_panier.php" method="POST" style="margin-top: 15px;">
                                    <input type="hidden" name="id_produit" value="<?= htmlspecialchars($plat['id']) ?>">
                                    <input type="hidden" name="nom" value="<?= htmlspecialchars($plat['nom']) ?>">
                                    <input type="hidden" name="prix" value="<?= htmlspecialchars($plat['prix']) ?>">
                                    
                                    <label for="quantite_<?= $plat['id'] ?>">Qté :</label>
                                    <input type="number" id="quantite_<?= $plat['id'] ?>" name="quantite" value="1" min="1" max="10" style="width: 50px; text-align: center; border-radius: 10px; border: 1px solid var(--pink-border);">
                                    
                                    <button type="submit" class="btn-order" style="margin-top: 10px; width: 100%;">Ajouter</button>
                                </form>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </section>

        <section id="sale" class="menu-section">
            <h2>🥑 Salé</h2>
            <ul class="menu-list">
                <?php foreach ($plats as $plat): ?>
                    <?php if ($plat['categorie'] === 'sale'): ?>
                        <li class="plat-avec-image">
                            <img src="<?= htmlspecialchars($plat['image']) ?>" alt="<?= htmlspecialchars($plat['nom']) ?>" class="plat-image">
                            <div class="plat-infos">
                                <span class="item-nom"><?= htmlspecialchars($plat['nom']) ?></span>
                                <span class="item-desc"><?= htmlspecialchars($plat['description']) ?></span>
                                <span class="prix"><?= number_format($plat['prix'], 2, ',', ' ') ?>€</span>
                                
                                <form action="ajoute_panier.php" method="POST" style="margin-top: 15px;">
                                    <input type="hidden" name="id_produit" value="<?= htmlspecialchars($plat['id']) ?>">
                                    <input type="hidden" name="nom" value="<?= htmlspecialchars($plat['nom']) ?>">
                                    <input type="hidden" name="prix" value="<?= htmlspecialchars($plat['prix']) ?>">
                                    
                                    <label for="quantite_<?= $plat['id'] ?>">Qté :</label>
                                    <input type="number" id="quantite_<?= $plat['id'] ?>" name="quantite" value="1" min="1" max="10" style="width: 50px; text-align: center; border-radius: 10px; border: 1px solid var(--pink-border);">
                                    
                                    <button type="submit" class="btn-order" style="margin-top: 10px; width: 100%;">Ajouter</button>
                                </form>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </section>

        <section id="sucre" class="menu-section">
            <h2>🧁 Sucré</h2>
            <ul class="menu-list">
                <?php foreach ($plats as $plat): ?>
                    <?php if ($plat['categorie'] === 'sucre'): ?>
                        <li class="plat-avec-image">
                            <img src="<?= htmlspecialchars($plat['image']) ?>" alt="<?= htmlspecialchars($plat['nom']) ?>" class="plat-image">
                            <div class="plat-infos">
                                <span class="item-nom"><?= htmlspecialchars($plat['nom']) ?></span>
                                <span class="item-desc"><?= htmlspecialchars($plat['description']) ?></span>
                                <span class="prix"><?= number_format($plat['prix'], 2, ',', ' ') ?>€</span>
                                
                                <form action="ajoute_panier.php" method="POST" style="margin-top: 15px;">
                                    <input type="hidden" name="id_produit" value="<?= htmlspecialchars($plat['id']) ?>">
                                    <input type="hidden" name="nom" value="<?= htmlspecialchars($plat['nom']) ?>">
                                    <input type="hidden" name="prix" value="<?= htmlspecialchars($plat['prix']) ?>">
                                    
                                    <label for="quantite_<?= $plat['id'] ?>">Qté :</label>
                                    <input type="number" id="quantite_<?= $plat['id'] ?>" name="quantite" value="1" min="1" max="10" style="width: 50px; text-align: center; border-radius: 10px; border: 1px solid var(--pink-border);">
                                    
                                    <button type="submit" class="btn-order" style="margin-top: 10px; width: 100%;">Ajouter</button>
                                </form>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </section>
        
        <p style="text-align:center; font-family:'Courier New', monospace; color:var(--red-gossip); margin-top:50px; font-weight:bold;">
            🌸 Dis-nous si t'as des allergies, on s'adapte !
        </p>
    </main>

    <footer>
        <div class="footer-bottom">
            <p style="text-align:center; padding: 20px; color: var(--pink-mid); background-color: var(--bordeaux-chic); margin: 0;">&copy; 2026 Spotted: The Brunch - XOXO</p>
        </div>
    </footer>
</body>
</html>