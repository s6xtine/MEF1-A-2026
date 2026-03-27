<?php
session_start();

// Protection : si pas connecté, retour à la connexion
if (!isset($_SESSION['login'])) {
    header('Location: connexion.php');
    exit();
}

// On récupère les données de la session pour plus de lisibilité
$nom = $_SESSION['nom'] ?? 'Non renseigné';
$prenom = $_SESSION['prenom'] ?? 'Non renseigné';
$email = $_SESSION['login'] ?? 'Non renseigné';
$tel = $_SESSION['telephone'] ?? 'Non renseigné';
$adresse = $_SESSION['adresse'] ?? 'Non renseigné';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sip & Spill - Mon Profil</title>
</head>

<body>
    <header class="site-header">
        <h1 class="titre-page">Sip & Spill</h1>
    </header>

    <nav class="main-nav">
        <a href="index.php">Accueil</a></li>
        <a href="deconnexion.php">Déconnexion</a></li> 
    </nav>

    <main class="profile-container">
    <h2 class="titre-section">Mon Profil</h2><br>

    <section class="infos-personnelles">
        <h3 class="sub-titre">Mes Informations</h3>
        <div class="info-item">
            <p><strong>Nom :</strong> <?php echo htmlspecialchars($nom); ?> <span>✏️</span></p>
            <p><strong>Prénom :</strong> <?php echo htmlspecialchars($prenom); ?> <span>✏️</span></p>
            <p><strong>Email :</strong> <?php echo htmlspecialchars($email); ?> <span>✏️</span></p>
            <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($tel); ?> <span>✏️</span></p>
        </div>
        <br>

        <h3 class="sub-titre">Adresse de livraison</h3>
        <div class="info-item">
            <p><?php echo htmlspecialchars($adresse); ?> <span>✏️</span></p>
            <p><strong>Interphone :</strong> B123 <span>✏️</span></p>
            <p><strong>Étage :</strong> 2ème <span>✏️</span></p>
        </div>
    </section>

        <section class="fidelite">
            <h3 class="sub-titre">Mon Compte Fidélité</h3>
            <p>Vous avez actuellement : <strong>150 points</strong></p>
            <p><i>Prochaine remise à 200 points !</i></p>
        </section>

        <section class="commandes-passees">
            <h3 class="sub-titre">Historique de mes commandes</h3>
            <table class="custom-table">
                    <tr>
                        <th>Date</th>
                        <th>N° Commande</th>
                        <th>Montant</th>
                        <th>Statut</th>
                    </tr>
                    <tr>
                        <td>23/12/2025</td>
                        <td>#7876</td>
                        <td>24.50 €</td>
                        <td>Livrée</td>
                    </tr>
                    <tr>
                        <td>05/02/2026</td>
                        <td>#9541</td>
                        <td>18.00 €</td>
                        <td>Livrée</td>
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