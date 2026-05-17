<?php
session_start();
$total_panier = 0; 
$mode_modification = isset($_SESSION['modif_id_commande']);
$deja_paye = 0;
$difference = 0;
$reste_a_payer = 0;

if ($mode_modification) {
    $deja_paye = (float)$_SESSION['modif_montant_initial'];
}
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
        
        <h2>🛍️ Mon Panier</h2>

        <?php if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])): ?>
            <div class="panier-vide">
                <p>Votre panier est vide pour le moment. </p>
                <a href="menu.php" class="btn-gossip btn-xs">Voir la carte</a>
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
                                <a href="traitement/retirer_panier.php?id=<?= htmlspecialchars($id) ?>"  title="Retirer du panier">❌</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div>
                <?php if ($mode_modification): ?>
                    <?php 
                        $difference = $total_panier - $deja_paye;
                        $reste_a_payer = $difference > 0 ? $difference : 0;
                    ?>
                    <p>Déjà réglé initialement : <?= number_format($deja_paye, 2, ',', ' ') ?> €</p>
                    <p>Nouveau total du panier : <?= number_format($total_panier, 2, ',', ' ') ?> €</p>
                    
                    <?php if ($difference > 0): ?>
                        <p>Reste à payer (différence) : <strong><?= number_format($reste_a_payer, 2, ',', ' ') ?> €</strong></p>
                    <?php elseif ($difference < 0): ?>
                        <p>✨ Commande moins chère ! Un bon d'achat de <strong><?= number_format(abs($difference), 2, ',', ' ') ?> €</strong> vous sera offert.</p>
                    <?php else: ?>
                        <p>Le montant est identique. Validez pour enregistrer.</p>
                    <?php endif; ?>

                <?php else: ?>
                    Total à régler : <strong><?= number_format($total_panier, 2, ',', ' ') ?> €</strong>
                <?php endif; ?>
            </div>

            <div>
            
                <?php 
                require('traitement/getapikey.php');

                $vendeur = "MEF-1_A";
                $transaction = strtoupper(bin2hex(random_bytes(6)));
                $montant = $mode_modification ? number_format($reste_a_payer, 2, '.', '') : number_format($total_panier, 2, '.', '');
                
                
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

                    <section>
                        <h3> Quand souhaitez-vous votre commande ?</h3>
                        <div class="info-item">
                            <label for="date_retrait">Date :</label>
                            <input type="date" name="date_retrait" id="date_retrait" 
                                min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" required>
                            
                            <label for="heure_retrait">Heure :</label>
                            <input type="time" name="heure_retrait" id="heure_retrait" required>
                            
                            <p>Note : Pour une préparation immédiate, choisissez l'heure actuelle.</p>
                        </div>
                    </section>
                    <?php if ($mode_modification && $reste_a_payer == 0): ?>
                        <a href="traitement/valide_modif.php" class="btn-gossip btn-large">
                            💾 Enregistrer les modifications
                        </a>
                    <?php else: ?>
                        <button type="submit" class="btn-gossip btn-large">💳 Payer la différence avec CYBank</button>
                    <?php endif; ?>

                </form>

                <a href="menu.php" class="btn-discret">⬅ Continuer mes achats</a>
                
            </div>
        <?php endif; ?>

    </main>

    <script>
        document.querySelector('.form-cybank').addEventListener('submit', function(e) {
            // 1. On bloque temporairement l'envoi vers CYBank
            e.preventDefault();
            
            const dateR = document.getElementById('date_retrait').value;
            const heureR = document.getElementById('heure_retrait').value;
            const form = this;

            // 2. On sauvegarde en arrière-plan proprement dans ta session
            fetch('traitement/sauvegarde_creneau.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'date=' + encodeURIComponent(dateR) + '&heure=' + encodeURIComponent(heureR)
            }).then(() => {
                // 3. Une fois la session enregistrée à coup sûr, on envoie le formulaire à CYBank
                form.submit();
            }).catch(() => {
                // Au cas où le réseau bugue, on l'envoie quand même
                form.submit();
            });
        });
    </script>
</body>
</html>