<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sip & Spill - Gestion des Commandes</title>
</head>
<body>
    <header class="site-header">
        <h1 class="titre-page">Sip & Spill - Espace Restaurateur</h1>
        <h2 class="titre-section">Espace Restaurateur</h2>
    </header>

    <nav class="main-nav">
        <a href="index.html">Accueil</a> | 
        <a href="administrateur.html">Admin</a> |
        <a href="connexion.html">Déconnexion</a>
    </nav>

    <main class="admin-container">
        <h3 class="sub-titre">Gestion des commandes</h3>

        <section id="a-preparer">
            <h4 class="sub-titre">Commandes à préparer</h4>
            <table border="1">
                <tr>
                    <th>N° Commande</th>
                    <th>Détails des plats</th>
                    <th>Heure de commande</th>
                    <th>Action</th>
                </tr>
                <tr>
                    <td>#1001</td>
                    <td>Cappuccino</td>
                    <td>12:15</td>
                    <td>
                    <button type="button">Passer en livraison</button> 
                    </td>
                </tr>
                <tr>
                    <td>#1002</td>
                    <td>Brioche perdue</td>
                    <td>12:20</td>
                    <td>
                    <button type="button">Passer en livraison</button> 
                    </td>
                </tr>

            </table>
        </section>

        <section id="en-livraison">
            <h4 class="sub-titre">Commandes en cours de livraison</h4>
            <table border="1">
                <tr>
                    <th>N° Commande</th>
                    <th>Livreur</th>
                    <th>Destination</th>
                    <th>Statut</th>
                </tr>
                <tr>
                    <td>#0998</td>
                    <td>Marc L.</td>
                    <td>Cergy - Avenue du Parc</td>
                    <td>En route</td>
                </tr>
            </table>
        </section>
    </main>

    <footer>
        <div class="footer-bottom">
            <p>© 2026 SIP AND SPILL · brunch de 9h à 16h</p>
        </div>
    </footer>
</body>
</html>