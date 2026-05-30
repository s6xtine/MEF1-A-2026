<?php
// On cible votre fichier JSON
$fichier = 'data/utilisateur.json';

if (file_exists($fichier)) {
    $utilisateurs = json_decode(file_get_contents($fichier), true);
    $comptes_modifies = 0;

    // On parcourt tous les utilisateurs
    foreach ($utilisateurs as &$user) {
        // Un mot de passe haché par PHP commence toujours par "$2y$"
        // Si ce n'est pas le cas, ça veut dire qu'il est en clair !
        if (isset($user['mdp']) && substr($user['mdp'], 0, 4) !== '$2y$') {
            // On le hache
            $user['mdp'] = password_hash($user['mdp'], PASSWORD_DEFAULT);
            $comptes_modifies++;
        }
    }

    // On sauvegarde le fichier avec les nouveaux mots de passe hachés
    file_put_contents($fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT));

    echo "<h1>Migration terminée !</h1>";
    echo "<p><strong>$comptes_modifies comptes</strong> ont été mis à jour avec des mots de passe sécurisés.</p>";
    echo "<p>⚠️ <b>IMPORTANT :</b> Supprimez maintenant ce fichier `migration_mdp.php` de votre projet !</p>";
} else {
    echo "Fichier utilisateur.json introuvable.";
}
?>