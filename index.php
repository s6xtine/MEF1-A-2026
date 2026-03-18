<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sip & Spill - Accueil</title>
</head>

<body>
    <!-- En-tête du site -->
    <header class="site-header">
        <h1 class="titre-page">Sip & Spill</h1>
        <p>Vous savez que vous nous adorez, alors installez-vous et laissez-vous séduire.</p>
        

        <div class="user-menu">
            <button type="button" class="profile-btn">Mon Profil</button>
            <ul class="deroulant">
                <li><a href="connexion.php">Connexion</a></li> 
                <li><a href="inscription.php">S'inscrire</a></li> 
                <li><a href="administrateur.php">Administration</a></li> 
            </ul>
        </div>
    </header>


    <!-- Menu de navigation -->
    <nav class="main-nav">
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="menu.php">Notre Carte</a></li>
            <li><a href="reservation.php">Réservation</a></li>
            <li><a href="notation.php">Laissez un avis</a></li>
        </ul>
    </nav>
    
    <!-- Contenu principal -->
    <main>
        <section class="search-area">
            <div class="search-content">
                <h2 class="titre-section">Spotted: Un plat en vue ?</h2>
                <form class="search-form">
                    <input type="text" placeholder="Rechercher un ragot... ou un pancake">
                    <button type="submit" class="btn-principal">Chercher</button>
                </form>
            </div>
        
            <div class="search-image-wrapper">
                <img src="cafe.jpg" alt="Ambiance" class="full-image">
            </div>
        </section>

        <!-- Section Présentation -->
        <section class="presentation-section">
            <h2 class="titre-section">Présentation</h2>
            <p>Le repaire confidentiel où la gourmandise flirte avec l'interdit. Chez Sip & Spill, nous croyons que chaque cocktail mérite sa confession et que chaque dessert doit être aussi croustillant que les dernières rumeurs. Installez-vous, le spectacle est dans la salle, mais le plaisir est définitivement dans l'assiette.
            </p>
        </section>

        <!-- Section Plat Du Jour -->
        <section class="daily-special">
            <h2 class="titre-section">Plat Du Jour</h2>
            <p>Découvrez notre plat du jour, concocté avec amour et une pincée de mystère.</p>
        </section>

        <!-- Section Les Adorés -->
        <section class="favorites-section">
            <h2 class="titre-section">Les Favoris de l'Upper East Side</h2>
            <div class="dish-card">
                <h3>Avocado Toast "Spotted"</h3>
                <p>Le favori de B.W., avec une pincée de piment.</p>
            </div>
        </section>

        <section class="cta-reservation">
            <h2 class="titre-section">Envie de nous rejoindre ?</h2>
            <a href="reservation.php" class="btn-geant">Réservez Maintenant</a>
        </section>
    </main>

    <!-- Pied de page -->
    <footer>
        <div class="footer-container">
            <!-- Section Contact -->
            <section class="footer-contact">
                <h4 class="titre-footer">Contact the Gossip</h4>
                <p>📍 123 Rue du vice, 95000 Cergy</p>
                <p>📞 01 23 45 67 89</p>
                <p>✉️ <a href="mailto:caryl.le-breton1@cyu.fr">hello@gossipbrunch.fr</a></p>
            </section>

            <!-- Section Auteurs -->
            <section class="footer-authors">
                <h4 class="titre-footer">Projet Creative-Yumland</h4>
                <p>Filière : préING2 </p>
                <p>Année : 2025-2026 </p>
                <p>Auteurs : C.LE BRETON & R.GRIGNON </p>
            </section>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Spotted: The Brunch - XOXO</p>
        </div>
    </footer>
</body>

</html>