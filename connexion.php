<?php
session_start()
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sip & Spill - Connexion</title>
</head>

<body>
    <header class="form-header">
        <a href="index.php" class="logo-mini">Sip & Spill</a>
    </header>

    <h1 class="titre-centre">Connexion</h1>

    <main>
        <form action="traitement/submit_connexion.php" method="post">
            
            <fieldset>
                <legend>Vos identifiants</legend>

                <label for="email">Adresse e-mail:</label>
                <input type="email" id="email" name="login" autocomplete="email" required>

                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="mdp" autocomplete="current-password" required>
                <button type="button" id="btn-oeil">👁️</button>
                <p id="password-counter"></p>
            </fieldset>
            
            
            <button type="submit" classe="btn-large">Se connecter</button>

            <p>Nouveau chez Sip & Spill ? <a href="inscription.php">Créez un compte ici</a></p>
            <p><a href="index.php">Retour au menu principal</a></p>

        </form>
    </main>
    
    <?php
        $load_validation = true; 
        include 'footer.php'; 
    ?>