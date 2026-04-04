<?php
session_start();

$fichier_commandes = 'data/commandes.json';
$commandes_en_livraison = [];

if (file_exists($fichier_commandes)) {
    $contenu = file_get_contents($fichier_commandes);
    if (!empty($contenu)) {
        $toutes_les_commandes = json_decode($contenu, true);
        
        foreach ($toutes_les_commandes as $cmd) {
            if ($cmd['statut'] === 'En livraison' || $cmd['statut'] === 'En route') {
                $commandes_en_livraison[] = $cmd;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sip & Spill - Livraison</title>
</head>

<body>
    <header class="site-header">
        <h1 class="titre-page">Sip & Spill</h1>
    </header>

    <nav class="main-nav">
        <a href="index.php">Accueil</a> |
        <a href="deconnexion.php">Déconnexion</a>
    </nav>

    <main>
        <?php if (empty($commandes_en_livraison)): ?>
            <h2 class="titre-section">Aucune livraison en cours</h2>
            <p class="msg-vide">Prenez une pause café ! ☕</p>
        <?php else: ?>

            <?php foreach ($commandes_en_livraison as $cmd): ?>
                
                <h2 class="titre-section">Commande <?= htmlspecialchars($cmd['id_commande']) ?> à livrer</h2>
                <p class="client-livraison">Client : <?= htmlspecialchars($cmd['client']) ?></p>

                <section id="adresse-client">
                    <h3 class="sub-titre">Destination</h3>
                    <p><?= htmlspecialchars($cmd['adresse'] ?? 'Adresse non renseignée') ?></p>
                    
                    <a href="https://maps.google.com/?q=<?= urlencode($cmd['adresse'] ?? '') ?>" target="_blank" class="bouton-nav">
                        Itinéraire (Maps)
                    </a> 
                </section>

                <section id="contact">
                    <h3 class="sub-titre">Contact Client</h3>
                    <p>Téléphone : <?= htmlspecialchars($cmd['telephone'] ?? 'Non renseigné') ?></p> 
                    <a href="tel:<?= htmlspecialchars($cmd['telephone'] ?? '') ?>" class="bouton-appel">Appeler le client</a>
                </section>

                <section id="instructions">
                    <h3 class="sub-titre">Contenu & Instructions</h3>
                    
                    <ul class="liste-articles-livraison">
                        <?php foreach ($cmd['articles'] as $article): ?>
                            <li>🛒 <?= $article['quantite'] ?>x <?= htmlspecialchars($article['nom']) ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <blockquote>"<?= htmlspecialchars($cmd['instructions'] ?? 'Aucune instruction particulière') ?>"</blockquote> 
                </section>

                <br><br>

                <form action="update_statut.php" method="POST" class="form-centre">
                    <input type="hidden" name="id_commande" value="<?= $cmd['id_commande'] ?>">
                    <input type="hidden" name="nouveau_statut" value="Livrée">
                    <button type="submit" id="valider-livraison" class="btn-livreur">Livraison terminée ✅</button>
                </form>

                <hr class="separateur-livraison">
            <?php endforeach; ?>

        <?php endif; ?>
    </main>

    <br>
    
    <footer>
        <div class="footer-bottom">
            <p>© 2026 SIP AND SPILL · brunch de 9h à 16h</p>
            <p>Sip & Spill - Interface Livreur</p>
        </div>
    </footer>
</body>
</html>