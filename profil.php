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

//lecture anciennes commandes du client 
$mes_commandes = [];
$fichier_commandes = 'data/commandes.json';

if (file_exists($fichier_commandes)) {
    $toutes_les_commandes = json_decode(file_get_contents($fichier_commandes), true);
    
    if (is_array($toutes_les_commandes)) {
        $toutes_les_commandes = array_reverse($toutes_les_commandes); 
        $mon_identite = trim($prenom . ' ' . $nom);
        
       foreach ($toutes_les_commandes as $cmd) {
            $client_commande = trim($cmd['client']);
            
            if ($client_commande === $mon_identite || $client_commande === $email) {
                $mes_commandes[] = $cmd;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="style.css?v=8">
    <meta charset="UTF-8">
    <title>Sip & Spill - Mon Profil</title>
</head>
<body>
    <header class="site-header">
        <h1 class="titre-page">Sip & Spill</h1>
    </header>

    <?php include 'nav.php'; ?>

    <main class="profile-container">
        <h2 class="titre-section text-center">Mon Profil</h2>

        <form action="traitement/update_profil.php" method="POST">
            <section class="infos-personnelles">
                
                <h3 class="sub-titre text-center">Mes Informations</h3>

                <div class="info-item text-center">
                    <p><strong>Nom :</strong> 
                        <?php if ($edit_mode): ?>
                            <input type="text" name="nom" value="<?= htmlspecialchars($nom) ?>" class="center-input">
                        <?php else: ?>
                            <?= htmlspecialchars($nom) ?>
                        <?php endif; ?>
                    </p>

                    <p><strong>Prénom :</strong> 
                        <?php if ($edit_mode): ?>
                            <input type="text" name="prenom" value="<?= htmlspecialchars($prenom) ?>" class="center-input">
                        <?php else: ?>
                            <?= htmlspecialchars($prenom) ?>
                        <?php endif; ?>
                    </p>

                    <p><strong>Téléphone :</strong> 
                        <?php if ($edit_mode): ?>
                            <input type="text" name="tel" value="<?= htmlspecialchars($tel) ?>" class="center-input">
                        <?php else: ?>
                            <?= htmlspecialchars($tel) ?>
                        <?php endif; ?>
                    </p>
                </div>
            
                <h3 class="sub-titre text-center">Adresse de livraison</h3>
                <div class="info-item text-center">
                    <?php if ($edit_mode): ?>
                        <textarea name="adresse" rows="3" class="center-input"><?= htmlspecialchars($adresse) ?></textarea>
                    <?php else: ?>
                        <p><?= nl2br(htmlspecialchars($adresse)) ?></p>
                    <?php endif; ?>
                </div>

                <div class="text-center">
                    <br>
                    <?php if (!$edit_mode): ?>
                        <a href="profil.php?edit=1" class="btn-promo">Modifier mes informations ✏️</a>
                    <?php else: ?>
                        <button type="submit" class="btn-geant">Enregistrer ✅</button>
                        <br><br>
                        <a href="profil.php" class="btn-discret">Annuler ❌</a>
                    <?php endif; ?>
                </div>

            </section>
        </form>

        <?php if ($_SESSION['role'] === 'client'): ?>
            <section class="fidelite text-center">
                <h3 class="sub-titre">Mon Compte Fidélité</h3>
                <p>Vous avez actuellement : <span class="points-fidelite"><?= $points ?> points</span></p>
                <p><i>Statut : <?= htmlspecialchars($statut) ?></i></p>
            </section>

            <section class="commandes-passees">
                <h3 class="sub-titre text-center">Historique de mes commandes</h3>
                <table class="table-panier">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Détail de la commande</th>
                            <th>Total</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($mes_commandes)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Vous n'avez pas encore passé de commande.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($mes_commandes as $cmd): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($cmd['date'])) ?></td>
                                    
                                    <td>
                                        <ul class="liste-articles-livraison">
                                            <?php foreach ($cmd['articles'] as $article): ?>
                                                <li><?= $article['quantite'] ?>x <?= htmlspecialchars($article['nom']) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <span class="item-desc">Réf: <?= htmlspecialchars($cmd['id_commande']) ?></span>
                                    </td>

                                    <td class="prix"> <?= number_format($cmd['total'], 2, ',', ' ') ?> €</td>        

                                    <td class="<?= $cmd['statut'] === 'Livrée' ? 'statut-valide' : '' ?>">
                                        <?= htmlspecialchars($cmd['statut']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        <?php endif; ?>

    </main>

    <?php include 'footer.php'; ?>