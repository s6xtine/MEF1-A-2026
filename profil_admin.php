<?php
session_start();
if ($_SESSION['role'] !== 'admin') { header('Location: index.php'); exit(); }

$id_cible = $_GET['id'] ?? null;
$utilisateurs = json_decode(file_get_contents('data/utilisateur.json'), true);
$u = null;


foreach ($utilisateurs as $user) {
    if ($user['id'] == $id_cible) { $u = $user; break; }
}

if (!$u) { echo "Utilisateur introuvable"; exit(); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Admin - Gestion Utilisateur</title>
</head>
<body>
    <main class="admin-container">
        <h2>Gestion de l'utilisateur : <?= htmlspecialchars($u['nom']) ?></h2>

        <form action="save_admin_changes.php" method="POST">
            <input type="hidden" name="id" value="<?= $u['id'] ?>">

            <section class="admin-actions">
                <h3>Pouvoirs Administrateur ⚡</h3>
                
                <p><strong>Changer le Statut/Rôle :</strong>
                    <select name="role">
                        <option value="client" <?= $u['role'] == 'client' ? 'selected' : '' ?>>Client</option>
                        <option value="livreur" <?= $u['role'] == 'livreur' ? 'selected' : '' ?>>Livreur</option>
                        <option value="restaurateur" <?= $u['role'] == 'restaurateur' ? 'selected' : '' ?>>Restaurateur</option>
                        <option value="admin" <?= $u['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="bloque" <?= $u['role'] == 'bloque' ? 'selected' : '' ?>>🚫 BLOQUÉ</option>
                    </select>
                </p>

                <p><strong>Points de Fidélité :</strong>
                    <input type="number" name="points" value="<?= $u['points'] ?? 0 ?>">
                </p>

                <p><strong>Bon d'achat (€) :</strong>
                    <input type="text" name="bon_achat" placeholder="Ex: BIENVENUE10">
                </p>
            </section>

            <section class="infos-base">
                <h3>Informations de base</h3>
                <p>Email : <input type="email" name="login" value="<?= htmlspecialchars($u['login']) ?>"></p>
                <button type="submit" style="background-color: #d4a373;">Appliquer les changements</button>
            </section>
        </form>
        
        <br><a href="administrateur.php">⬅ Retour à la liste</a>
    </main>
</body>
</html>