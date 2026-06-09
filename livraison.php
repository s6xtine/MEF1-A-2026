<?php
session_start();

// On autorise le livreur ET l'admin
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'livreur' && $_SESSION['role'] !== 'admin')) {
    header('Location: connexion.php');
    exit();
}
$fichier_commandes = 'data/commandes.json';
$commandes_en_livraison = [];

$mon_nom_livreur = $_SESSION['prenom'] . " " . $_SESSION['nom'];
$est_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (file_exists($fichier_commandes)) {
    $contenu = file_get_contents($fichier_commandes);
    if (!empty($contenu)) {
        $toutes_les_commandes = json_decode($contenu, true);
        
        foreach ($toutes_les_commandes as $cmd) {
            $est_pour_moi = isset($cmd['livreur']) && $cmd['livreur'] === $mon_nom_livreur;
            
            if ($cmd['statut'] === 'En livraison' && ($est_pour_moi || $est_admin)) {
                $commandes_en_livraison[] = $cmd;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sip & Spill - Livraison</title>
    <link rel="stylesheet" href="style.css?v=9">
</head>

<body>
    <header class="site-header">
        <h1 class="titre-page">Sip & Spill</h1>
        <h2 class="titre-section">Espace Livreur</h2>
    </header>

    <?php include 'nav.php'; ?>

    <main class="main-livreur">
        <?php if (empty($commandes_en_livraison)): ?>
            <h2 class="sub-titre">Aucune livraison en cours</h2>
            <p class="msg-vide">Prenez une pause café ! ☕</p>
        <?php else: ?>

            <?php foreach ($commandes_en_livraison as $cmd): ?>
                <div class="commande-livraison">
                
                    <h2 class="sub-titre">Commande <?= htmlspecialchars($cmd['id_commande']) ?></h2>
                    <h2 ><?= htmlspecialchars($cmd['client']) ?></h2>

                    <section class="info-item">
                        
                        <p class="gros"><?= htmlspecialchars($cmd['adresse'] ?? 'Adresse non renseignée') ?></p>
                        <a href="https://maps.google.com/?q=<?= urlencode($cmd['adresse'] ?? '') ?>" target="_blank" class="btn-gossip btn-small">
                            Itinéraire (Maps)
                        </a> 
                    </section>

                    <section class="info-item">
                        
                        <p class="gros">Téléphone : <?= htmlspecialchars($cmd['telephone'] ?? 'Non renseigné') ?></p> 
                        <a href="tel:<?= htmlspecialchars($cmd['telephone'] ?? '') ?>" class="btn-gossip btm-small">Appeler le client</a>
                    </section>

                    <section id="instructions" class="info-item">
                        
                        <ul>
                            <?php foreach ($cmd['articles'] as $article): ?>
                                <li><?= $article['quantite'] ?>x <?= htmlspecialchars($article['nom']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <blockquote>"<?= htmlspecialchars($cmd['instructions'] ?? 'Aucune instruction particulière') ?>"</blockquote> 
                    </section>

                    <form action="traitement/update_statut.php" method="POST" class="form-sans-boite">
                        <input type="hidden" name="id_commande" value="<?= $cmd['id_commande'] ?>">
                        <input type="hidden" name="nouveau_statut" value="Livrée">
                        <button type="submit" id="valider-livraison" class="btn-gossip btn-large">Livraison terminée </button>
                        <button type="submit" id="abandon-livraison" class="btn-gossip btn-large">Abandon </button>
                    </form>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </main>
    
    <?php include 'footer.php'; ?>
</body>
</html>