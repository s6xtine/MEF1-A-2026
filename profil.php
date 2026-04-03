<?php
session_start();

if (!isset($_SESSION['role'])) {
    header('Location: connexion.php');
    exit();
}

$edit_mode = isset($_GET['edit']) && $_GET['edit'] == 1;

$nom = $_SESSION['nom'] ?? '';
$prenom = $_SESSION['prenom'] ?? '';
$email = $_SESSION['login'] ?? '';
$tel = $_SESSION['telephone'] ?? 'Non renseigné';
$adresse = $_SESSION['adresse'] ?? 'Non renseignée';
$points = $_SESSION['points'] ?? 0;
$statut = $_SESSION['statut'] ?? 'Regular';
 //lecture anciennes commmandes du client 
$mes_commandes = [];
$fichier_commandes = 'data/commandes.json';

if (file_exists($fichier_commandes)) {
    $toutes_les_commandes = json_decode(file_get_contents($fichier_commandes), true);
    
    if (is_array($toutes_les_commandes)) {
       
        $toutes_les_commandes = array_reverse($toutes_les_commandes); 
        
        
        $mon_identite = trim($prenom . ' ' . $nom);
        
        foreach ($toutes_les_commandes as $cmd) {
            
            if (trim($cmd['client']) === $mon_identite) {
                $mes_commandes[] = $cmd;
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
                        <th>Détail de la commande</th>
                        <th>Total</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mes_commandes)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 15px;">Vous n'avez pas encore passé de commande.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mes_commandes as $cmd): ?>
                            <tr>
                                <td style="padding: 10px;"><?= date('d/m/Y', strtotime($cmd['date'])) ?></td>
                                
                                <td style="padding: 10px;">
                                    <ul style="margin: 0; padding-left: 20px; list-style-type: square;">
                                        <?php foreach ($cmd['articles'] as $article): ?>
                                            <li><?= $article['quantite'] ?>x <?= htmlspecialchars($article['nom']) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <span style="font-size: 0.8em; color: gray; margin-left: 20px;">Réf: <?= htmlspecialchars($cmd['id_commande']) ?></span>
                                </td>

                                <td style="padding: 10px; font-weight: bold;">
                                    <?= number_format($cmd['total'], 2, ',', ' ') ?> €
                                </td>

                                <td style="padding: 10px; font-weight: bold; color: <?= $cmd['statut'] === 'Livrée' ? 'green' : 'var(--bordeaux-chic)' ?>;">
                                    <?= htmlspecialchars($cmd['statut']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>