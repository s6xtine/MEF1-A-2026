<?php
session_start();

// Protection : redirection si non connecté
if (!isset($_SESSION['role'])) {
    header('Location: connexion.php');
    exit();
}

// Extraction des données de session pour éviter les erreurs "Undefined variable"
$nom = $_SESSION['nom'] ?? 'Non renseigné';
$prenom = $_SESSION['prenom'] ?? 'Non renseigné';
$email = $_SESSION['login'] ?? 'Non renseigné';
$tel = $_SESSION['telephone'] ?? 'Non renseigné';
$adresse = $_SESSION['adresse'] ?? 'Non renseigné';
$points = $_SESSION['points'] ?? 0; // Donnée récupérée du JSON à la connexion
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <title>Sip & Spill - Mon Profil</title>
</head>
<body>
    <header class="site-header">
        <h1 class="titre-page">Sip & Spill</h1>
    </header>

    <nav class="main-nav">
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="deconnexion.php">Déconnexion</a></li>
        </ul>
    </nav>

    <main class="profile-container">
        <h2 class="titre-section">Mon Profil</h2>

        <section class="infos-personnelles">
            <h3 class="sub-titre">Mes Informations</h3>
            <div class="info-item">
                <p><strong>Nom :</strong> <?= htmlspecialchars($nom) ?> <span>✏️</span></p>
                <p><strong>Prénom :</strong> <?= htmlspecialchars($prenom) ?> <span>✏️</span></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($email) ?> <span>✏️</span></p>
                <p><strong>Téléphone :</strong> <?= htmlspecialchars($tel) ?> <span>✏️</span></p>
            </div>
            
            <h3 class="sub-titre">Adresse de livraison</h3>
            <div class="info-item">
                <p><?= htmlspecialchars($adresse) ?> <span>✏️</span></p>
            </div>
        </section>

        <section class="fidelite">
            <h3 class="sub-titre">Mon Compte Fidélité (Lecture seule)</h3>
            <p>Vous avez actuellement : <strong><?= $points ?> points</strong></p>
            <p><i>Statut : <?= htmlspecialchars($_SESSION['role']) ?></i></p>
        </section>

        <section class="commandes-passees">
            <h3 class="sub-titre">Historique de mes commandes</h3>
            <table class="custom-table">
                <tr>
                    <th>Date</th>
                    <th>N° Commande</th>
                    <th>Statut</th>
                </tr>
                <tr>
                    <td>23/12/2025</td>
                    <td>#7876</td>
                    <td>Livrée</td>
                </tr>
            </table>
        </section>
    </main>
</body>
</html>