<?php
session_start();

$fichier_carte = 'data/carte.json';
$plats = [];
$menus_brunch = [];

if (file_exists($fichier_carte)) {
    $contenu = file_get_contents($fichier_carte);
    $donnees = json_decode($contenu, true);
    if (isset($donnees['plats']))  $plats = $donnees['plats'];
    if (isset($donnees['menus']))  $menus_brunch = $donnees['menus'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Carte · SIP AND SPILL</title>
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<header class="form-header">
    <a href="index.php" class="logo-mini">Sip & Spill</a>
</header>

<?php include 'nav.php'; ?>

<?php if (isset($_GET['ajout']) && $_GET['ajout'] === 'succes'): ?>
    <div class="msg-succes">✨ Ajouté au panier avec succès !</div>
<?php endif; ?>

<section class="menu-hero">
    <img src="https://offloadmedia.feverup.com/parissecret.com/wp-content/uploads/2021/07/15044118/10-1024x576.png" alt="Notre Carte" class="menu-hero-img">
    <div class="menu-hero-overlay">
        <p>Découvrez</p>
        <h1>Notre Carte</h1>
        <p>Fait maison · Avec amour · Chaque jour</p>
    </div>
</section>

<main class="menu">

    <!-- BARRE FILTRES + TRIS -->
    <div class="filtres-bar">

        <div class="filtres-groupe">
            <span >Catégorie</span>
            <div class="filtres-btns">
                <button class="btn-gossip btn-xs" data-type="categorie" data-val="">Tout</button>
                <button class="btn-gossip btn-xs" data-type="categorie" data-val="boissons">☕ Boissons</button>
                <button class="btn-gossip btn-xs" data-type="categorie" data-val="sale">🥑 Salé</button>
                <button class="btn-gossip btn-xs" data-type="categorie" data-val="sucre">🧁 Sucré</button>
            </div>
        </div>

        <div class="filtres-groupe">
            <span>Régime</span>
            <div class="filtres-btns">
                <button class="btn-gossip btn-xs" data-type="tag" data-val="vegetarien">🌿 Végétarien</button>
                <button class="btn-gossip btn-xs" data-type="tag" data-val="vegan">🌱 Vegan</button>
                <button class="btn-gossip btn-xs" data-type="tag" data-val="halal">Halal</button>
                <button class="btn-gossip btn-xs" data-type="tag" data-val="sans_gluten">Sans gluten</button>
                <button class="btn-gossip btn-xs" data-type="tag" data-val="sans_lactose">Sans lactose</button>
            </div>
        </div>

        <div class="filtres-groupe">
            <span>Goût</span>
            <div class="filtres-btns">
                <button class="btn-gossip btn-xs" data-type="gout" data-val="">Tous</button>
                <button class="btn-gossip btn-xs" data-type="gout" data-val="sale">🧂 Salé</button>
                <button class="btn-gossip btn-xs" data-type="gout" data-val="sucre">🍬 Sucré</button>
                <button class="btn-gossip btn-xs" data-type="gout" data-val="epice">🌶️ Épicé</button>
            </div>
        </div>

        <div class="filtres-groupe">
            <span>Trier par</span>
            <div class="filtres-btns">
                <button class="btn-gossip btn-xs" data-tri="defaut">Par défaut</button>
                <button class="btn-gossip btn-xs" data-tri="prix_asc">Prix ↑</button>
                <button class="btn-gossip btn-xs" data-tri="prix_desc">Prix ↓</button>
                <button class="btn-gossip btn-xs" data-tri="populaire">⭐ Populaires</button>
            </div>
        </div>

    </div>

    <!-- RÉSULTATS FILTRÉS (caché par défaut) -->
    <div id="resultats-filtres" class="menu-section" style="display:none;">
        <h2 id="resultats-titre">Résultats</h2>
        <ul class="menu-list" id="liste-resultats"></ul>
    </div>

    <!-- SECTIONS NORMALES -->
    <div id="sections-normales">

     
        <section id="menus" class="menu-section">
            <h2>✨ Les Formules</h2>
            <ul class="menu-list">
                <?php foreach ($menus_brunch as $menu): ?>
                    <li>
                        <div class="plat-infos">
                            <span class="item-nom"><?= htmlspecialchars($menu['nom']) ?></span> 
                            <span class="item-desc"><?= htmlspecialchars($menu['description']) ?></span>
                            
                            <form action="traitement/ajoute_panier.php" method="POST" class="form-sans-boite">
                                <input type="hidden" name="id_produit" value="<?= htmlspecialchars($menu['id']) ?>">
                                <input type="hidden" name="nom" value="<?= htmlspecialchars($menu['nom']) ?>">
                                <input type="hidden" name="prix" value="<?= htmlspecialchars($menu['prix_total']) ?>">
                                
                                <div class="panier-controls">
                                    
                                    <input type="number" name="quantite" value="1" min="1" max="10" class="input-qte">
                                </div>

                                <button type="submit" class="btn-gossip btn-xs">
                                    <?= number_format($menu['prix_total'], 2, ',', ' ') ?> €
                                </button>
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

                                <form action="traitement/ajoute_panier.php" method="POST" class="form-sans-boite">
                                    <input type="hidden" name="id_produit" value="<?= htmlspecialchars($plat['id']) ?>">
                                    <input type="hidden" name="nom" value="<?= htmlspecialchars($plat['nom']) ?>">
                                    <input type="hidden" name="prix" value="<?= htmlspecialchars($plat['prix']) ?>">
                                    
                                    <div class="panier-controls">
                                        
                                        <input type="number" name="quantite" value="1" min="1" max="10" class="input-qte">
                                    </div>

                                    <button type="submit" class="btn-gossip btn-xs">
                                        <?= number_format($plat['prix'], 2, ',', ' ') ?> €
                                    </button>
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
                                
                                <form action="traitement/ajoute_panier.php" method="POST" class="form-sans-boite">
                                    <input type="hidden" name="id_produit" value="<?= htmlspecialchars($plat['id']) ?>">
                                    <input type="hidden" name="nom" value="<?= htmlspecialchars($plat['nom']) ?>">
                                    <input type="hidden" name="prix" value="<?= htmlspecialchars($plat['prix']) ?>">
                                    
                                    <div class="panier-controls">
                                        <input type="number" name="quantite" value="1" min="1" max="10" class="input-qte">
                                    </div>

                                    <button type="submit" class="btn-gossip btn-xs">
                                        <?= number_format($plat['prix'], 2, ',', ' ') ?> €
                                    </button>
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
                                
                                <form action="traitement/ajoute_panier.php" method="POST" class="form-sans-boite">
                                    <input type="hidden" name="id_produit" value="<?= htmlspecialchars($plat['id']) ?>">
                                    <input type="hidden" name="nom" value="<?= htmlspecialchars($plat['nom']) ?>">
                                    <input type="hidden" name="prix" value="<?= htmlspecialchars($plat['prix']) ?>">
                                    
                                    <div class="panier-controls">
                                        <input type="number" name="quantite" value="1" min="1" max="10" class="input-qte">
                                    </div>
                                    

                                    <button type="submit" class="btn-gossip btn-xs">
                                        <?= number_format($plat['prix'], 2, ',', ' ') ?> €
                                    </button>
                                </form>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </section>

    </div><!-- fin #sections-normales -->

    <p class="alerte-allergie">🌸 Dis-nous si t'as des allergies, on s'adapte !</p>
</main>

<?php include 'footer.php'; ?>
<script src="js/menu.js"></script>
</body>
</html>