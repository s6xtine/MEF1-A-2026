<?php
session_start();
$total_panier = 0.0; 
//si la clé modif_commande existe alors il faut modifier une commande deja payée, et c'est pas une nouvelle commande 
$mode_modification = isset($_SESSION['modif_id_commande']); 
$deja_paye = 0.0;
$difference = 0.0;
$reste_a_payer = 0.0;

//si on est en mod modif, on récup le montant deja payé 
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

        <!-- 1; panier vide ou qui n'exsite pas -->
        <?php if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])): ?>
            <div class="panier-vide">
                <p>Votre panier est vide pour le moment. </p>
                <a href="menu.php" class="btn-gossip btn-xs">Voir la carte</a>
            </div>
        <!-- 2; affichage panier -->
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
                    <!-- on parcour le panier pour afficher articles par articles -->
                    <?php foreach ($_SESSION['panier'] as $id => $article): ?>
                        <?php 
                            $sous_total = (float)$article['prix'] * $article['quantite'];
                            $total_panier += $sous_total; 
                        ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($article['nom']) ?></strong></td>
                            <td><?= number_format((float)$article['prix'], 2, ',', ' ') ?> €</td>
                            <td><span class="qte-badge"><?= $article['quantite'] ?></span></td>
                            
                            <td><?= number_format((float)$sous_total, 2, ',', ' ') ?> €</td>
                            
                            <td>
                                <!-- pour supprimer un article, ça nous renvoi vers retirer_panier -->
                                <a href="traitement/retirer_panier.php?id=<?= htmlspecialchars($id) ?>"  title="Retirer du panier">❌</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php
            // on récupère les points de fid du client 
            $points_disponibles = isset($_SESSION['points']) ? (float)$_SESSION['points'] : 0.0;
            // et le nombre qu'il a choisi d'utiliser
            $points_utilises = isset($_SESSION['points_utilises']) ? (float)$_SESSION['points_utilises'] : 0.0;

            //Calcul du montant de base avant application des points
            $montant_base = $mode_modification ? (float)($total_panier - $deja_paye) : (float)$total_panier;

            // Sécurité : on limite les points aux capacités du client et au prix de la commande
            if ($montant_base > 0) {
                $points_utilises = (float)min($points_utilises, $points_disponibles, $montant_base);
            } else {
                $points_utilises = 0.0;
            }

            // Calcul du montant final envoyé à la banque
            $montant_final = (float)$montant_base - $points_utilises;
            if ($montant_final < 0){
                    $montant_final = 0.0;
            }
            ?>


            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'client' && $montant_base > 0 && $points_disponibles > 0): ?>
                <div>
                    <h3>✨ Utiliser mes points de fidélité</h3>
                    <p>Vous avez <strong><?= number_format((float)$points_disponibles, 2, ',', ' ') ?></strong> points en réserve (1 point = 1 €).</p>
                    <!-- formulaire pour appliquer les points si il y a une différence positice -->
                    <form action="traitement/appliquer_points.php" method="POST">
                        <label for="pts">Points à déduire :</label>
                        <input type="number" id="pts" name="points_a_utiliser" min="0" step="0.01" max="<?= min((float)$points_disponibles, (float)$montant_base) ?>" value="<?= (float)$points_utilises ?>">
                        <button type="submit" class="btn-gossip btn-xs">Appliquer</button>
                        <?php if ($points_utilises > 0): ?>
                            <a href="traitement/appliquer_points.php?annuler=1">❌ Retirer</a>
                        <?php endif; ?>
                    </form>
                </div>
            <?php endif; ?>

            <div>
                <?php if ($mode_modification): ?>
                    <p>Déjà réglé initialement : <?= number_format((float)$deja_paye, 2, ',', ' ') ?> €</p>
                    <p>Nouveau total du panier : <?= number_format((float)$total_panier, 2, ',', ' ') ?> €</p>
                    <p>Différence brute : <?= number_format((float)$montant_base, 2, ',', ' ') ?> €</p>
                    <?php if ($points_utilises > 0): ?>
                        <p>Remise Fidélité : -<?= number_format((float)$points_utilises, 2, ',', ' ') ?> €</p>
                    <?php endif; ?>
                    
                    <?php if ($montant_base < 0): ?>
                        <p>✨ Commande moins chère !</p>
                        <p><strong>Reste à payer net : <span>0,00 €</span></strong></p>
                        <p><small>(Conformément à nos CGV, la différence n'est pas remboursée)</small></p>
                    <?php else: ?>
                        <p><strong>Reste à payer net : <span><?= number_format((float)$montant_final, 2, ',', ' ') ?> €</span></strong></p>
                    <?php endif; ?>

                <?php else: ?>
                    <p>Total du panier : <?= number_format((float)$total_panier, 2, ',', ' ') ?> €</p>
                    <?php if ($points_utilises > 0): ?>
                        <p>Remise Fidélité : -<?= number_format((float)$points_utilises, 2, ',', ' ') ?> €</p>
                    <?php endif; ?>
                    <p><strong>Total à régler net : <span><?= number_format((float)$montant_final, 2, ',', ' ') ?> €</span></strong></p>
                <?php endif; ?>
            </div>
            
            <div>
                <?php 
                require('traitement/getapikey.php');

                $vendeur = "MEF-1_A";
                $transaction = strtoupper(bin2hex(random_bytes(6)));
                $montant = number_format((float)$montant_final, 2, '.', '');
                
                $retour = "http://localhost/MEF1-A-2026/traitement/traitement_paiement.php";

                $api_key = getAPIKey($vendeur);
                $control = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#");
                ?>
                <!-- si montant =0 alors on n'envoie pas le client sur cy bank pour rien, simule un paiement gratuit -->
                <?php if ($montant_final == 0): ?>
                    <form action='traitement/traitement_paiement.php' method='GET' class="form-cybank">
                        <input type='hidden' name='status' value='gratuit'>
                        <input type='hidden' name='montant' value='0.00'>
                <?php else: ?>
                    <form action='https://www.plateforme-smc.fr/cybank/index.php' method='POST' class="form-cybank">
                        <input type='hidden' name='transaction' value='<?= $transaction ?>'>
                        <input type='hidden' name='montant' value='<?= $montant ?>'>
                        <input type='hidden' name='vendeur' value='<?= $vendeur ?>'>
                        <input type='hidden' name='retour' value='<?= $retour ?>'>
                        <input type='hidden' name='control' value='<?= $control ?>'>
                <?php endif; ?>

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

                    <div>
                        <button type="submit" class="btn-gossip btn-large">
                            <?php if ($montant_final == 0): ?>
                                ✅ Valider via CYBank (Gratuit - 0,00 €)
                            <?php elseif (!$mode_modification): ?>
                                💳 Payer avec CYBank (<?= number_format((float)$montant_final, 2, ',', ' ') ?> €)
                            <?php else: ?>
                                💳 Payer la différence (<?= number_format((float)$montant_final, 2, ',', ' ') ?> €)
                            <?php endif; ?>
                        </button>
                    </div>

                </form>
            </div>

            <div>
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