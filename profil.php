<?php
session_start();

if (!isset($_SESSION['role'])) {
    header('Location: connexion.php');
    exit();
}

$nom = $_SESSION['nom'] ?? '';
$prenom = $_SESSION['prenom'] ?? '';
$email = $_SESSION['login'] ?? '';
$tel = $_SESSION['telephone'] ?? 'Non renseigné';
$adresse = $_SESSION['adresse'] ?? 'Non renseignée';
$points = $_SESSION['points'] ?? 0;
$statut = $_SESSION['statut'] ?? 'Regular';

$mes_commandes = [];
$fichier_commandes = 'data/commandes.json';

if (file_exists($fichier_commandes)) {
    $toutes_les_commandes = json_decode(file_get_contents($fichier_commandes), true);
    
    if (is_array($toutes_les_commandes)) {
        $toutes_les_commandes = array_reverse($toutes_les_commandes); 
        $mon_identite = trim($prenom . ' ' . $nom);
        
       foreach ($toutes_les_commandes as $cmd) {
            $client_commande = trim($cmd['client']);
            
            if ($client_commande === $mon_identite || $client_commande === $email) {
                $mes_commandes[] = $cmd;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="style.css?v=9">
    <meta charset="UTF-8">
    <title>Sip & Spill - Mon Profil</title>
</head>
<body>
    <header class="site-header">
        <h1 class="titre-page">Sip & Spill</h1>
        <h2 class="titre-section">Mon Profil</h2>
    </header>

    <?php include 'nav.php'; ?>

    <main class="profile-container">
        
        <form action="traitement/update_profil.php" method="POST" class="form-discret" id="form-profil">
            <section>
                
                <h3 class="sub-titre text-center">Mes Informations</h3>

                <div class="info-item text-center">
                    <p><label for="nom"><strong>Nom :</strong></label> 
                        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom) ?>" class="input-qte" readonly>
                    </p>

                    <p><label for="prenom"><strong>Prénom :</strong></label> 
                        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($prenom) ?>" class="input-qte" readonly>
                    </p>

                    <p><label for="tel"><strong>Téléphone :</strong></label> 
                        <input type="text" id="tel" name="tel" value="<?= htmlspecialchars($tel) ?>" class="input-qte" readonly>
                    </p>
                </div>
            
                <h3 class="sub-titre text-center">Adresse de livraison</h3>
                <div class="info-item text-center">
                    <textarea name="adresse" id="adresse" rows="3" class="input-qte" readonly><?= htmlspecialchars($adresse) ?></textarea>
                </div>

                <div class="text-center">
                    <br>
                    <p id="msg-retour"></p>
                    <button type="button" class="btn-promo" id="btn-modifier">Modifier mes informations ✏️</button>
                    <button type="submit" class="btn-geant cache" id="btn-sauvegarder">Enregistrer ✅</button>
                </div>

            </section>
        </form>

        <?php if ($_SESSION['role'] === 'client'): ?>
            <section class="fidelite text-center">
                <h3 class="sub-titre">Mon Compte Fidélité</h3>
                <p>Vous avez actuellement : <span class="points-fidelite"><?= $points ?> points</span></p>
            </section>

            <section class="commandes-passees">
                <h3 class="sub-titre text-center">Historique de mes commandes</h3>
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Détail de la commande</th>
                            <th>Total</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($mes_commandes)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Vous n'avez pas encore passé de commande.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($mes_commandes as $cmd): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($cmd['date'])) ?></td>
                                    
                                    <td class="col-detail">
                                        <ul class="liste-articles-livraison">
                                            <?php foreach ($cmd['articles'] as $article): ?>
                                                <li><?= $article['quantite'] ?>x <?= htmlspecialchars($article['nom']) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <span class="item-desc">Réf: <?= htmlspecialchars($cmd['id_commande']) ?></span>
                                    </td>

                                    <td class="prix-table"> 
                                        <?= number_format($cmd['total'], 2, ',', ' ') ?> € 
                                    </td>        

                                    <td class="<?= $cmd['statut'] === 'Livrée' ? 'statut-valide' : '' ?>">
                                        <strong><?= htmlspecialchars($cmd['statut']) ?></strong>
                                        
                                        <?php if ($cmd['statut'] === 'Livrée'): ?>
                                            <br>
                                            <a href="notation.php?id=<?= $cmd['id_commande'] ?>" class="btn-avis">Donner mon avis ⭐</a>
                                        <?php endif; ?>

                                        <?php if ($cmd['statut'] === 'payé'): ?>
                                            <br><br>
                                            <a href="traitement/update_commande.php?id=<?= $cmd['id_commande'] ?>" >
                                                ✏️ Modifier
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        <?php endif; ?>

    </main>
    <?php include 'footer.php'; ?>

    <script src="js/modif-profil.js"></script>
</body>
</html>