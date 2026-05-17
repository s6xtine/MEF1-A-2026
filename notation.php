<?php
session_start();

// 1. ON VÉRIFIE SI LE CLIENT EST CONNECTÉ
$est_connecte = isset($_SESSION['login']);
$commandes_a_noter = [];

if ($est_connecte) {
    $fichier_commandes = 'data/commandes.json';
    $mon_identifiant = $_SESSION['login']; 
    $mon_nom_complet = ($_SESSION['prenom'] ?? '') . " " . ($_SESSION['nom'] ?? '');

    if (file_exists($fichier_commandes)) {
        $contenu = file_get_contents($fichier_commandes);
        if (!empty($contenu)) {
            $toutes_les_commandes = json_decode($contenu, true);
            if (is_array($toutes_les_commandes)) {
                foreach ($toutes_les_commandes as $cmd) {
                    // On filtre les commandes qui : Appartiennent au ce client, sont au statut "Livrée", n'ont pas encore l'indicateur 'notee' à true
                    $appartient_au_client = ($cmd['client'] === $mon_identifiant || $cmd['client'] === $mon_nom_complet);
                    $est_livree = isset($cmd['statut']) && $cmd['statut'] === 'Livrée';
                    $deja_notee = isset($cmd['notee']) && $cmd['notee'] === true;

                    if ($appartient_au_client && $est_livree && !$deja_notee) {
                        $commandes_a_noter[] = $cmd;
                    }
                }
            }
        }
    }
}
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
        
        <?php if (!$est_connecte): ?>
            <section class="info-item text-center">
                <h2 class="sub-titre">Connexion requise</h2>
                <p class="msg-vide">Vous devez être connecté à votre compte pour pouvoir évaluer vos commandes livrées.</p>
                <br>
                <a href="connexion.php" class="btn-gossip">Se connecter</a>
            </section>

        <?php elseif (empty($commandes_a_noter)): ?>
            <section class="info-item text-center">
                <h2 class="sub-titre">Aucune commande à noter pour le moment</h2>
                <p class="msg-vide">Vous n'avez aucune commande livrée en attente d'avis. Dès qu'un livreur terminera une course pour vous, elle apparaîtra ici !</p>
                <br>
                <a href="index.php" class="link-back">Retour à l'accueil</a>
            </section>

        <?php else: ?>
            <form action="submit_notation.php" method="POST">
                
                <fieldset>
                    <legend>Notez votre expérience</legend>

                    <label for="id_commande">Sélectionnez la commande concernée :</label>
                    <select name="id_commande" id="id_commande" class="input-qte" required>
                        <?php foreach ($commandes_a_noter as $cmd): ?>
                            <option value="<?= htmlspecialchars($cmd['id_commande']) ?>">
                                Commande N°<?= htmlspecialchars($cmd['id_commande']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="nom">Votre prénom / pseudo :</label>
                    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($_SESSION['prenom'] ?? '') ?>" required>

                    <label for="note">Note (sur 5 étoiles) :</label>
                    <input type="number" id="note" name="note" min="1" max="5" placeholder="5" required>

                    <label for="commentaire">Votre commentaire :</label>
                    <textarea id="commentaire" name="commentaire" rows="5" placeholder="Qu'avez-vous pensé de notre brunch ? L'ambiance, les plats..." required></textarea>
                </fieldset>

                <button type="submit" class="btn-gossip">Envoyer mon avis</button>
                
                <p><a href="index.php" class="link-back">Retour au menu principal</a></p>
            </form>
        <?php endif; ?>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>