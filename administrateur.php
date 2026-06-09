<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: connexion.php');
    exit();
}
$chemin_fichier = 'data/utilisateur.json';
$utilisateurs = [];

if (file_exists($chemin_fichier)) {
    $contenu_json = file_get_contents($chemin_fichier);
    $utilisateurs = json_decode($contenu_json, true);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sip & Spill - Administration</title>
</head>
<body>
    <header class="site-header">
        <h1 class="titre-page">Sip & Spill</h1>
        <h2 class="titre-section">Espace Administrateur</h2>
    </header>

    <?php include 'nav.php'; ?>

    <main>
        <section class="admin-section">
            <h3 class="sub-titre">Gestion des utilisateurs</h3>
        </section>


        <table class="table-panier">
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email (Login)</th>
            <th>Rôle</th> 
            <th>Actions</th>
        </tr>

        <?php if (!empty($utilisateurs)): ?>
            <?php foreach ($utilisateurs as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['nom']); ?></td>
                    <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($user['login']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <a href="profil_admin.php?id=<?php echo htmlspecialchars($user['id']); ?>" class="btn-edit">Voir le profil</a>
                        <button type="button" class="btn-gossip btn-xs btn-statut" data-login="<?php echo htmlspecialchars($user['login']); ?>">
                            <?php echo (isset($user['bloque']) && $user['bloque']) ? 'Débloquer 🟢' : 'Bloquer 🔴'; ?>
                        </button>
                    </td> 
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">Aucun utilisateur trouvé dans le fichier.</td>
            </tr>
        <?php endif; ?>
        </table>
    </main>

    <?php include 'footer.php'; ?>
    <script src="js/admin.js"></script>
    <!-- Page d'administration pour gérer les utilisateurs (bloquer/débloquer) et accéder aux profils individuels -->
</body>
</html>