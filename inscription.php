<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sip & Spill - Inscription</title>
</head>
<body>

    <header class="form-header">
        <a href="index.html" class="logo-mini">Sip & Spill</a>
    </header>

    <h1 class="titre-centre">Inscription</h1>

    <main>
        <form action="submit_inscription.php" method="post">

            <fieldset>
                <legend>Informations personnelles</legend>

                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" autocomplete="family-name" required>

                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" autocomplete="given-name" required>

                <label for="telephone">Numéro de téléphone :</label>
                <input type="tel" id="telephone" name="telephone" autocomplete="tel" required>
            </fieldset>

            <fieldset>
                <legend>Informations de connexion</legend>

                <label for="email">Adresse e-mail :</label>
                <input type="email" id="email" name="email" autocomplete="email" required>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" autocomplete="new-password" required>
            </fieldset>

            <fieldset>
                <legend>Détails de livraison</legend>

                <label for="adresse">Adresse postale :</label>
                <input type="text" id="adresse" name="adresse" autocomplete="street-address" required>

                <label for="interphone">Code Interphone :</label>
                <input type="text" id="interphone" name="interphone">

                <label for="etage">Étage / Appartement :</label>
                <input type="text" id="etage" name="etage">

                <label for="commentaires">Instructions particulières :</label>
                <textarea id="commentaires" name="commentaires" rows="5" placeholder="Ex: Bâtiment au fond de la cour..."></textarea>
            </fieldset>

            <button type="submit">S'inscrire</button>

            <p>Déjà membre ? <a href="connexion.php">Connectez-vous ici</a></p>
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