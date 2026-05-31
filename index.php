<?php
session_start()
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sip & Spill - Accueil</title>
</head>
<?php if (isset($_GET['succes']) && $_GET['succes'] === 'commande'): ?>
    <div class="msg-succes">
        🎉 Merci pour votre commande ! Nos chefs se mettent aux fourneaux.
    </div>
<?php endif; ?>

<body>
    
    <header class="site-header">
        <h1 class="titre-page">Sip & Spill</h1>
        <p>Vous savez que vous nous adorez, alors installez-vous et laissez-vous séduire.</p>
    </header>

    <?php include 'nav.php'; ?>
    
    
    <main>
        <?php if (isset($_SESSION['anniv_bonus']) && $_SESSION['anniv_bonus'] === true): ?>
            <div class="msg-succes">
            🎂 Joyeux Anniversaire ! Sip & Spill vous offre 30 points de fidélité pour commander un menu gratuit ! 🎉
            </div>
            <?php unset($_SESSION['anniv_bonus']); // On supprime la variable pour que le message disparaisse au prochain rechargement ?>  
        <?php endif; ?>

        <section class="search-area">
            <div class="search-content">
                <h2 class="titre-section">Spotted: Un plat en vue ?</h2>
                <form class="search-form">
                    <input type="text" placeholder="Rechercher un ragot... ou un pancake">
                    <button type="submit" class="btn-gossip btn-small">Chercher</button>
                </form>
            </div>
        
            <div class="search-image-wrapper">
                <img src="images/cafe.jpg" alt="Ambiance" class="full-image">
            </div>
        </section>

        
        <section class="presentation-section">
            <h2 class="titre-section">Présentation</h2>
            <p>Le repaire confidentiel où la gourmandise flirte avec l'interdit. Chez Sip & Spill, nous croyons que chaque cocktail mérite sa confession et que chaque dessert doit être aussi croustillant que les dernières rumeurs. Installez-vous, le spectacle est dans la salle, mais le plaisir est définitivement dans l'assiette.
            </p>
        </section>

        
        <section class="daily-special">
            <h2 class="titre-section">Plat Du Jour</h2>
            <p>Découvrez notre plat du jour, concocté avec amour et une pincée de mystère.</p>
        </section>

        
        <section class="favorites-section">
            <h2 class="titre-section">Les Favoris de l'Upper East Side</h2>
            <div class="dish-card">
                <h3>Avocado Toast "Spotted"</h3>
                <p>Le favori de B.W., avec une pincée de piment.</p>
            </div>
        </section>

        <section class="cta-reservation">
            <h2 class="titre-section">Envie de nous rejoindre ?</h2>
            <a href="reservation.php" class="btn-gossip btn-large">Réservez Maintenant</a>
        </section>
    </main>

    
    <?php 
    $is_index = true; 
    include 'footer.php'; 
    ?>

    