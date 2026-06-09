<?php
session_start();
$fichier_avis = 'data/avis.json';
$les_avis = [];

if (file_exists($fichier_avis)) {
    $contenu = file_get_contents($fichier_avis);
    if (!empty($contenu)) {
        //On transforme la chaîne JSON en un tableau PHP associatif (grâce au paramètre 'true')
        $les_avis = json_decode($contenu, true);
        if (is_array($les_avis)) {
            $les_avis = array_reverse($les_avis); // array_reverse replace les derniers éléments du tableau en premier
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> <!-- encodage des caractères -->
    <title>Sip & Spill - Vos avis</title>
    <link rel="stylesheet" href="style.css?v=13">
</head>
<body>

    <header class="form-header">
        <a href="index.php" class="logo-mini">Sip & Spill</a>
    </header>
    <?php include 'nav.php'; ?>

    <h1 class="titre-centre">Vos avis</h1>

    <main class="admin-container">

        <?php if (empty($les_avis)): ?>
            <p class="text-center">Aucun avis n'a encore été laissé.</p>
        <?php else: ?>
            
            <?php foreach ($les_avis as $av): ?>
                <div class="info-item">
                    
                    <h3>
                        <?= htmlspecialchars($av['nom'] ?? 'Client Anonyme') ?>
                        <?= str_repeat('⭐', (int)($av['note'] ?? 5)) ?>
                    </h3>
                    
                    <blockquote>
                        "<?= htmlspecialchars($av['commentaire'] ?? '') ?>"
                    </blockquote>
                    
                    <p>
                        <small>Publié le <?= htmlspecialchars($av['date'] ?? date('d/m/Y')) ?></small>
                    </p>
                    
                </div>
            <?php endforeach; ?>
            
        <?php endif; ?>

    </main>

    <?php include 'footer.php'; ?>
</body>
</html>