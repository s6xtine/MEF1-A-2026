<?php
session_start()
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laissez un avis - Sip & Spill</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="form-header">
        <a href="index.php" class="logo-mini">Sip & Spill</a>
    </header>

    <h1 class="titre-centre">Votre Avis</h1>

    <main>
        <form action="submit_notation.php" method="POST">
            
            <fieldset>
                <legend>Notez votre expérience</legend>

                <label for="nom">Votre prénom / pseudo :</label>
                <input type="text" id="nom" name="nom" placeholder="Ex: Serena V." required>

                <label for="note">Note (sur 5 étoiles) :</label>
                <input type="number" id="note" name="note" min="1" max="5" placeholder="5" required>

                <label for="commentaire">Votre commentaire :</label>
                <textarea id="commentaire" name="commentaire" rows="5" placeholder="Qu'avez-vous pensé de notre brunch ? L'ambiance, les plats..." required></textarea>
            </fieldset>

            <button type="submit">Envoyer mon avis</button>
            
            <p><a href="index.html">Retour au menu principal</a></p>

        </form>
    </main>

    <footer>
        <div class="footer-bottom">
            <p>&copy; 2026 SIP AND SPILL · brunch de 9h à 16h</p>
        </div>
    </footer>
</body>
</html>