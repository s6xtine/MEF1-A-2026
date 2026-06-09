<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - Sip & Spill</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="form-header">
        <a href="index.php" class="logo-mini">Sip & Spill</a>
    </header>

    <h1 class="titre-centre">Réservez votre table</h1>

    <main>
        <form action="submit_reservation.php" method="POST"> <!-- Ce fichier n'existe pas, la réservation n'est pas utilisable sur le site -->
            
            <fieldset>
                <legend>Formulaire de Réservation</legend>

                <label for="name">Nom :</label>
                <input type="text" id="name" name="name" placeholder="Votre nom" required>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" placeholder="Votre email" required>

                <label for="date">Date :</label>
                <input type="date" id="date" name="date" required>

                <label for="time">Heure :</label>
                <input type="time" id="time" name="time" required>

                <label for="guests">Nombre de personnes :</label>
                <input type="number" id="guests" name="guests" min="1" max="20" required>

                <label for="message">Message (optionnel) :</label>
                <textarea id="message" name="message" placeholder="Des demandes spéciales ?"></textarea>
            </fieldset>

            <button type="submit" class="btn-principal">Réserver</button>
            
            <p class="text-center"><a href="index.php">Retour au menu principal</a></p>
        </form>
    </main>

    <?php include 'footer.php'; ?>
    <!-- formulaire de réservation de table pour les clients -->
</body>
</html>