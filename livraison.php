<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Sip & Spill - Livraison</title>
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
            <p style="text-align: center; margin-top: 20px;">Prenez une pause café ! ☕</p>
        <?php else: ?>

            <?php foreach ($commandes_en_livraison as $cmd): ?>
                
                <h2 class="titre-section">Commande <?= htmlspecialchars($cmd['id_commande']) ?> à livrer</h2>
                <p style="text-align: center; font-weight: bold; color: var(--red-gossip);">Client : <?= htmlspecialchars($cmd['client']) ?></p>

                <section id="adresse-client">
                    <h3 class="sub-titre">Destination</h3>
                    <p>123 Rue de la Soif, 95000 Cergy</p>
                    <a href="https://maps.google.com/?q=123+Rue+de+la+Soif+Cergy" target="_blank" class="bouton-nav">
                        Itinéraire (Maps)
                    </a> 
                </section>

                <section id="contact">
                    <h3 class="sub-titre">Contact Client</h3>
                    <p>Téléphone : 06 01 02 03 04</p> 
                    <a href="tel:0601020304" class="bouton-appel">Appeler le client</a>
                </section>

                <section id="instructions">
                    <h3 class="sub-titre">Contenu & Instructions</h3>
                    
                    <ul style="list-style-type: none; padding: 0; text-align: center; margin-bottom: 15px;">
                        <?php foreach ($cmd['articles'] as $article): ?>
                            <li>🛒 <?= $article['quantite'] ?>x <?= htmlspecialchars($article['nom']) ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <blockquote>                                  "Le bâtiment est au fond de la cour"
                    </blockquote> 
                </section>

                <section id="acces-immeuble">
                    <h3 class="sub-titre">Codes et Accès</h3>
                    <p>Code interphone : 123</p> 
                    <p>Étage : 2ème étage</p> 
                </section>

                <br><br>

                <form action="update_statut.php" method="POST" style="text-align: center;">
                    <input type="hidden" name="id_commande" value="<?= $cmd['id_commande'] ?>">
                    <input type="hidden" name="nouveau_statut" value="Livrée">
                    <button type="submit" id="valider-livraison" class="btn-livreur">Livraison terminée ✅</button>
                </form>

                <hr style="margin: 50px 0; border: 1px dashed var(--pink-border);">
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