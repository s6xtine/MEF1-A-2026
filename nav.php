<?php
$role_nav = $_SESSION['role'] ?? 'visiteur'; 
$page_actuelle = basename($_SERVER['PHP_SELF']); 

// --- DEBUT DU VIDEUR DE SESSION ---
if (isset($_SESSION['login']) && $role_nav !== 'admin') { // On évite de bloquer un admin par erreur !
    $chemin_fichier_users = 'data/utilisateur.json'; 
    
    if (file_exists($chemin_fichier_users)) {
        $utilisateurs = json_decode(file_get_contents($chemin_fichier_users), true);
        
        foreach ($utilisateurs as $user) {
            if ($user['login'] === $_SESSION['login']) {
                if (isset($user['bloque']) && $user['bloque'] === true) {
                    // C'est fini pour lui : on détruit la session
                    session_unset();
                    session_destroy();
                    header("Location: connexion.php?erreur=bloque"); // On le renvoie à la porte
                    exit();
                }
                break;
            }
        }
    }
}
// --- FIN DU VIDEUR ---
?>

<nav class="main-nav">
        <ul>
            <li><a href="index.php">Accueil</a></li>

            <?php 
            $role_nav = $_SESSION['role'] ?? 'visiteur'; 
            ?>

            <?php if ($role_nav === 'visiteur' || $role_nav === 'client'): ?>
                <li><a href="menu.php">Notre Carte</a></li>
                <li><a href="reservation.php">Réservation</a></li>
                <li><a href="notation.php">Laissez un avis</a></li>
                <li><a href="panier.php" class="active">🛒 Mon Panier</a></li>
            <?php endif; ?>

            <?php if ($role_nav === 'restaurateur'): ?>
                <li><a href="commandes.php">Gestion des Commandes</a></li>
                <li><a href="menu.php">Carte</a></li>
                <li><a href="modif_menu.php">Modifier la Carte</a></li>
            <?php endif; ?>

            <?php if ($role_nav === 'livreur'): ?>
                <li><a href="livraison.php">Livraisons</a></li>
            <?php endif; ?>

            <?php if ($role_nav === 'admin'): ?>
                <li><a href="administrateur.php">Panneau Admin</a></li>
                <li><a href="reservation.php">Réservation</a></li>
                <li><a href="commandes.php">Commandes</a></li>
                <li><a href="menu.php">Carte</a></li>
                <li><a href="panier.php">Panier</a></li>
                <li><a href="livraison.php">Livreur</a></li>
                <li><a href="notation.php">Avis</a></li>
            <?php endif; ?>

        </ul>

        <div class="user-menu">
        <?php if (isset($_SESSION['nom'])): ?>
            <button type="button" class="btn-gossip btn-xs">*</button>
            <ul class="deroulant">
                <li><a href="profil.php">Mon Profil</a></li>
                <li><a href="deconnexion.php">Déconnexion</a></li>
                <li>
                    <button id="theme-toggle" >Changer de thème</button>
                </li>
            </ul>
        <?php else: ?>
            <button type="button" class="btn-gossip btn-xs">Mon Compte</button>
            <ul class="deroulant">
                <li><a href="connexion.php">Connexion</a></li>
                <li><a href="inscription.php">S'inscrire</a></li>
            <li>
                <button id="theme-toggle" class="btn-gossip btn-xs">Changer de thème</button>
            </li>
            </ul>
        <?php endif; ?>
        </div>
    </nav>


<!-- On charge le script JavaScript pour qu'il soit sur toutes les pages -->
    <script src="js/theme.js"></script>