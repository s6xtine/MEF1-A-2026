<?php
session_start();
$total_panier = 0; // On prépare une tirelire vide pour calculer le total
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier - Sip & Spill</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="main-header">
        <div class="logo">
            <h1 class="logo-text">Sip & Spill</h1>
        </div>
        <nav class="main-nav">
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="menu.php">Notre Carte</a></li>
                <li><a href="panier.php" style="color: var(--red-gossip); font-weight: bold;">🛒 Mon Panier</a></li>
            </ul>
        </nav>
    </header>

    <main style="max-width: 800px; margin: 50px auto; background-color: var(--pink-light); padding: 40px; border-radius: 20px; border: 2px solid var(--pink-border); box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        
        <h2 style="color: var(--bordeaux-chic); text-align: center; margin-bottom: 30px;">🛍️ Mon Panier</h2>

        <?php 
        // Si le panier n'existe pas ou qu'il est vide
        if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])): 
        ?>
            <div style="text-align: center; font-size: 1.2rem; color: var(--bordeaux-chic);">
                <p>Votre panier est vide pour le moment. 🥺</p>
                <a href="menu.php" class="btn-order" style="display: inline-block; margin-top: 20px; text-decoration: none;">Voir la carte</a>
            </div>

        <?php 
        // Si le panier contient des articles
        else: 
        ?>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--bordeaux-chic); color: var(--bordeaux-chic); text-align: left;">
                        <th style="padding: 10px;">Plat / Boisson</th>
                        <th style="padding: 10px; text-align: center;">Prix unitaire</th>
                        <th style="padding: 10px; text-align: center;">Qté</th>
                        <th style="padding: 10px; text-align: right;">Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['panier'] as $id => $article): ?>
                        <?php 
                            // On calcule le prix pour cet article (prix * quantité)
                            $sous_total = $article['prix'] * $article['quantite'];
                            // On l'ajoute à la tirelire globale
                            $total_panier += $sous_total; 
                        ?>
                        <tr style="border-bottom: 1px solid var(--pink-border);">
                            <td style="padding: 15px 10px;"><strong><?= htmlspecialchars($article['nom']) ?></strong></td>
                            <td style="padding: 15px 10px; text-align: center;"><?= number_format($article['prix'], 2, ',', ' ') ?> €</td>
                            <td style="padding: 15px 10px; text-align: center;">
                                <span style="background: white; padding: 5px 15px; border-radius: 10px; border: 1px solid var(--pink-border); font-weight: bold;">
                                    <?= $article['quantite'] ?>
                                </span>
                            </td>
                            <td style="padding: 15px 10px; text-align: right; color: var(--red-gossip); font-weight: bold;">
                                <?= number_format($sous_total, 2, ',', ' ') ?> €
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="text-align: right; font-size: 1.5rem; color: var(--bordeaux-chic); margin-bottom: 30px;">
                Total à régler : <strong><?= number_format($total_panier, 2, ',', ' ') ?> €</strong>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <a href="menu.php" style="color: var(--bordeaux-chic); text-decoration: underline;">Continuer mes achats</a>
                
                <form action="valider_commande.php" method="POST">
                    <button type="submit" class="btn-geant" style="margin: 0; padding: 15px 30px; font-size: 1.2rem;">
                        💳 Payer et Valider
                    </button>
                </form>
            </div>

        <?php endif; ?>

    </main>

</body>
</html>