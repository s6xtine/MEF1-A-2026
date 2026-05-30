<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header('Location: index.php'); 
    exit(); 
}

$id_cible = $_GET['id'] ?? null;
$chemin_json = 'data/utilisateur.json';
$utilisateurs = json_decode(file_get_contents($chemin_json), true);
$u = null;

foreach ($utilisateurs as $user) {
    if ($user['id'] == $id_cible) { 
        $u = $user; 
        break; 
    }
}

if (!$u) { echo "Utilisateur introuvable"; exit(); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
    <meta charset="UTF-8">
    <title>Admin - Gestion de <?= htmlspecialchars($u['nom']) ?></title>
</head>
<body>
    <header class="site-header">
        <h1 class="titre-page">Sip & Spill</h1>
        <h2 class="titre-section">Panneau de Contrôle Admin</h2>
    </header>

    <main class="admin-container">
        <h2>Modifier le profil de : <?= htmlspecialchars($u['prenom']) . " " . htmlspecialchars($u['nom']) ?></h2>

        <form action="traitement/save_admin_change.php" method="POST" class="form-sans-boite">
            <input type="hidden" name="id" value="<?= $u['id'] ?>">

            <section class=" info-item">
                <h3 >Pouvoirs Administrateur ⚡</h3>
                
                <p><strong>Changer le Statut :</strong>
                    <select name="statut" class="input-qte">
                        <option value="Regular" <?= ($u['statut'] ?? '') == 'Regular' ? 'selected' : '' ?>>Regular</option>
                        <option value="Premium" <?= ($u['statut'] ?? '') == 'Premium' ? 'selected' : '' ?>>Premium</option>
                        <option value="VIP" <?= ($u['statut'] ?? '') == 'VIP' ? 'selected' : '' ?>>VIP</option>
                    </select>
                </p>

                <p><strong>Points de Fidélité :</strong>
                    <input type="number" name="points" value="<?= $u['points'] ?? 0 ?>" class="input-qte">
                </p>

                <p><strong>Bon d'achat :</strong>
                    <input type="text" name="bon_achat" value="<?= htmlspecialchars($u['bon_achat'] ?? '') ?>" placeholder="Ex: CADEAU5" class="input-qte">
                </p>
            </section>

            <section class=" info-item">
                <h3>Informations du compte </h3>
                <p><strong>Email / Login :</strong> <?= htmlspecialchars($u['login']) ?></p>
                <p><strong>Date d'inscription :</strong> <?= htmlspecialchars($u['date_inscription'] ?? 'Inconnue') ?></p>
                
                <button type="submit" class="btn-gossip btn-small" style="margin-top: 20px;">Enregistrer les modifications</button>
            </section>
        </form>
        
        <a href="administrateur.php" class="link-back">⬅ Retour à la liste des utilisateurs</a>
    </main>

    <?php include 'footer.php'; ?>