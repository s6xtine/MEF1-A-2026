<?php
session_start();
$total_panier = 0; 
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

    <main class="panier-container">
        
        <h2 class="panier-titre">🛍️ Mon Panier</h2>

        <?php if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])): ?>
            <div class="panier-vide">
                <p>Votre panier est vide pour le moment. </p>
                <a href="menu.php" class="btn-order" style="display: inline-block; margin-top: 20px;">Voir la carte</a>
            </div>

        <?php else: ?>
            <table class="table-panier">
                <thead>
                    <tr>
                        <th>Plat / Boisson</th>
                        <th>Prix unitaire</th>
                        <th>Qté</th>
                        <th>Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['panier'] as $id => $article): ?>
                        <?php 
                            $sous_total = $article['prix'] * $article['quantite'];
                            $total_panier += $sous_total; 
                        ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($article['nom']) ?></strong></td>
                            <td><?= number_format($article['prix'], 2, ',', ' ') ?> €</td>
                            <td><span class="qte-badge"><?= $article['quantite'] ?></span></td>
                            <td><?= number_format($sous_total, 2, ',', ' ') ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="panier-total">
                Total à régler : <strong><?= number_format($total_panier, 2, ',', ' ') ?> €</strong>
            </div>

            <div class="panier-actions">
                <a href="menu.php" class="lien-continuer">Continuer mes achats</a>
            
            // CYBank
              <?php 
                require('getapikey.php');

                $vendeur = "MEF-1_A";
                $transaction = strtoupper(bin2hex(random_bytes(6)));
                $montant = number_format($total_panier, 2, '.', '');
                $retour = "http://localhost/MEF1-A-2026/traitement_paiement.php";

                $api_key = getAPIKey($vendeur);
                $control = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#");
                ?>

                <form action='https://www.plateforme-smc.fr/cybank/index.php' method='POST'>
                    <input type='hidden' name='transaction' value='<?= $transaction ?>'>
                    <input type='hidden' name='montant' value='<?= $montant ?>'>
                    <input type='hidden' name='vendeur' value='<?= $vendeur ?>'>
                    <input type='hidden' name='retour' value='<?= $retour ?>'>
                    <input type='hidden' name='control' value='<?= $control ?>'>
                    <button type="submit" class="btn-geant">💳 Payer avec CYBank</button>
                </form>
            </div>
        <?php endif; ?>

    </main>
</body>
</html>