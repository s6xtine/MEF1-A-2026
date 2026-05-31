<?php
session_start();
$fichier_avis = 'data/avis.json';
$les_avis = [];

if (file_exists($fichier_avis)) {
    $contenu = file_get_contents($fichier_avis);
    if (!empty($contenu)) {
        $les_avis = json_decode($contenu, true);
        if (is_array($les_avis)) {
            $les_avis = array_reverse($les_avis); // Les plus récents en premier
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sip & Spill - Vos avis</title>
    <link rel="stylesheet" href="style.css?v=13">
</head>
<body>
    <?php include 'nav.php'; ?>

    <main class="admin-container">
        
        <header class="site-header" style="text-align: center; margin-bottom: 40px;">
            <h1 class="titre-page">Vos avis ✨</h1>
        </header>

        <?php if (empty($les_avis)): ?>
            <p style="text-align: center;">Aucun avis n'a encore été laissé.</p>
        <?php else: ?>
            
            <?php foreach ($les_avis as $av): ?>
                <div style="border-bottom: 1px solid var(--pink-border); padding-bottom: 20px; margin-bottom: 30px;">
                    
                    <h3>
                        <?= htmlspecialchars($av['nom'] ?? 'Client Anonyme') ?>
                        <span style="float: right; font-size: 1.2rem;">
                            <?= str_repeat('⭐', (int)($av['note'] ?? 5)) ?>
                        </span>
                    </h3>
                    
                    <p style="font-style: italic; font-size: 1.1rem; color: #444;">
                        "<?= htmlspecialchars($av['commentaire'] ?? '') ?>"
                    </p>
                    
                    <p style="text-align: right; margin: 0;">
                        <small style="color: #888;">Publié le <?= htmlspecialchars($av['date'] ?? date('d/m/Y')) ?></small>
                    </p>
                    
                </div>
            <?php endforeach; ?>
            
        <?php endif; ?>

    </main>

    <?php include 'footer.php'; ?>
</body>
</html>