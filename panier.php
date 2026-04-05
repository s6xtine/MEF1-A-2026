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
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
</head>
<body>

    <header class="form-header">
        <a href="index.php" class="logo-mini">Sip & Spill</a>
    </header>

    <?php include 'nav.php'; ?>

    <main class="panier-container">
        
        <h2 class="panier-titre">🛍️ Mon Panier</h2>

        <?php if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])): ?>
            <div class="panier-vide">
                <p>Votre panier est vide pour le moment. </p>
                <a href="menu.php" class="btn-promo">Voir la carte</a>
            </div>

        <?php else: ?>
            <table class="table-panier">
                <thead>
                    <tr>
                        <th>Plat / Boisson</th>
                        <th>Prix unitaire</th>
                        <th>Qté</th>
                        <th>Sous-total</th>
                        <th>Retirer</th> 
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
                            
                            <td>
                                <a href="traitement/retirer_panier.php?id=<?= htmlspecialchars($id) ?>" class="btn-retirer" title="Retirer du panier">❌</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="panier-total">
                Total à régler : <strong><?= number_format($total_panier, 2, ',', ' ') ?> €</strong>
            </div>

            <div class="panier-actions-colonne">
            
                <?php 
                require('traitement/getapikey.php');

                $vendeur = "MEF-1_A";
                $transaction = strtoupper(bin2hex(random_bytes(6)));
                $montant = number_format($total_panier, 2, '.', '');
                $retour = "http://localhost/MEF1-A-2026/traitement/traitement_paiement.php";

                $api_key = getAPIKey($vendeur);
                $control = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#");
                ?>

                <form action='https://www.plateforme-smc.fr/cybank/index.php' method='POST' class="form-cybank">
                    <input type='hidden' name='transaction' value='<?= $transaction ?>'>
                    <input type='hidden' name='montant' value='<?= $montant ?>'>
                    <input type='hidden' name='vendeur' value='<?= $vendeur ?>'>
                    <input type='hidden' name='retour' value='<?= $retour ?>'>
                    <input type='hidden' name='control' value='<?= $control ?>'>

                    <section class="choix-creneau">
                        <h3 class="sub-titre">📅 Quand souhaitez-vous votre commande ?</h3>
                        <div class="info-item">
                            <label for="date_retrait">Date :</label>
                            <input type="date" name="date_retrait" id="date_retrait" 
                                min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" required>
                            
                            <label for="heure_retrait">Heure :</label>
                            <input type="time" name="heure_retrait" id="heure_retrait" required>
                            
                            <p><small><i>Note : Pour une préparation immédiate, choisissez l'heure actuelle.</i></small></p>
                        </div>
                    </section>
                    <button type="submit" class="btn-geant">💳 Payer avec CYBank</button>
                </form>

                <a href="menu.php" class="btn-discret">⬅ Continuer mes achats</a>
                
            </div>
        <?php endif; ?>

    </main>
    <script>

    document.querySelector('.form-cybank').addEventListener('submit', function() {
        const dateR = document.getElementById('date_retrait').value;
        const heureR = document.getElementById('heure_retrait').value;
        
        
        fetch('traitement/sauvegarde_creneau.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'date=' + encodeURIComponent(dateR) + '&heure=' + encodeURIComponent(heureR)
        });
    });
</script>
</body>
</html>