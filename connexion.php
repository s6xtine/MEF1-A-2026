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
        <a href="index.html" class="logo-mini">Sip & Spill</a>
    </header>

    <h1 class="titre-centre">Connexion</h1>

    <main>
        <form action="submit_connexion.php" method="post">
            
            <fieldset>
                <legend>Vos identifiants</legend>

                <label for="email">Adresse e-mail:</label>
                <input type="email" id="email" name="email" autocomplete="email" required>

                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>
            </fieldset>

            <button type="submit">Se connecter</button>

            <p>Nouveau chez Sip & Spill ? <a href="inscription.html">Créez un compte ici</a></p>
            <p><a href="index.html">Retour au menu principal</a></p>

        </form>
    </main>
    
    <footer>
        <div class="footer-bottom">
            <p>© 2026 SIP AND SPILL · brunch de 9h à 16h</p>
        </div>
    </footer>
</body>
</html>