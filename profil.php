<?php
session_start();

if (!isset($_SESSION['role'])) {
    header('Location: connexion.php');
    exit();
}

// On vérifie si l'utilisateur a cliqué sur "Modifier"
$edit_mode = isset($_GET['edit']) && $_GET['edit'] == 1;

$nom = $_SESSION['nom'] ?? '';
$prenom = $_SESSION['prenom'] ?? '';
$email = $_SESSION['login'] ?? '';
$tel = $_SESSION['telephone'] ?? 'Non renseigné';
$adresse = $_SESSION['adresse'] ?? 'Non renseignée';
$points = $_SESSION['points'] ?? 0;
$statut = $_SESSION['statut'] ?? 'Regular';
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

        <form action="update_profil.php" method="POST">
            <section class="infos-personnelles">
                <div class="header-section">
                    <h3 class="sub-titre">Mes Informations</h3>
                    <?php if (!$edit_mode): ?>
                        <a href="profil.php?edit=1" class="btn-edit">Modifier mes informations ✏️</a>
                    <?php endif; ?>
                </div>

                <div class="info-item">
                    <p><strong>Nom :</strong> 
                        <?php if ($edit_mode): ?>
                            <input type="text" name="nom" value="<?= htmlspecialchars($nom) ?>">
                        <?php else: ?>
                            <?= htmlspecialchars($nom) ?>
                        <?php endif; ?>
                    </p>

                    <p><strong>Prénom :</strong> 
                        <?php if ($edit_mode): ?>
                            <input type="text" name="prenom" value="<?= htmlspecialchars($prenom) ?>">
                        <?php else: ?>
                            <?= htmlspecialchars($prenom) ?>
                        <?php endif; ?>
                    </p>

                    <p><strong>Téléphone :</strong> 
                        <?php if ($edit_mode): ?>
                            <input type="text" name="tel" value="<?= htmlspecialchars($tel) ?>">
                        <?php else: ?>
                            <?= htmlspecialchars($tel) ?>
                        <?php endif; ?>
                    </p>
                </div>
            
                <h3 class="sub-titre">Adresse de livraison</h3>
                <div class="info-item">
                    <?php if ($edit_mode): ?>
                        <textarea name="adresse" rows="3"><?= htmlspecialchars($adresse) ?></textarea>
                    <?php else: ?>
                        <p><?= nl2br(htmlspecialchars($adresse)) ?></p>
                    <?php endif; ?>
                </div>

                <?php if ($edit_mode): ?>
                    <div class="actions-edit">
                        <button type="submit" class="btn-save">Enregistrer ✅</button>
                        <a href="profil.php" class="btn-cancel">Annuler ❌</a>
                    </div>
                <?php endif; ?>
            </section>
        </form>

        <section class="fidelite">
            <h3 class="sub-titre">Mon Compte Fidélité</h3>
            <p>Vous avez actuellement : <strong><?= $points ?> points</strong></p>
            <p><i>Statut : <?= htmlspecialchars($statut) ?></i></p>
        </section>
        <section class="commandes-passees">
            <h3 class="sub-titre">Historique de mes commandes</h3>
            <table class="custom-table" border="1" style="width: 100%; text-align: left; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f8f8f8;">
                        <th>Date</th>
                        <th>N° Commande</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>23/12/2025</td>
                        <td>#7876</td>
                        <td>Livrée</td>
                    </tr>
                    <tr>
                        <td>05/02/2026</td>
                        <td>#9541</td>
                        <td>Livrée</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>