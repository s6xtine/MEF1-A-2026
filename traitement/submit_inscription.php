<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['login'];
    $mot_de_passe = $_POST['mdp'];
    $numero_telephone = $_POST['telephone'];
    $naissance = $_POST['naissance'];
    $adresse = $_POST['adresse'];
    $interphone = $_POST['interphone'];
    $etage = $_POST['etage'];
    $commentaires = $_POST['commentaires'];

    // Sécurité : double vérif côté serveur (même règles que dans le JS)
    $erreurs = [];

    // Vérification email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'adresse email n'est pas valide.";
    }

    // Vérification mdp (8 caractères min)
    if (strlen($mot_de_passe) < 8) {
        $erreurs[] = "Le mot de passe doit faire au moins 8 caractères.";
    }

    // Vérification date de naissance
    if (!empty($naissance)) {
        $dateSaisie = new DateTime($naissance);
        $dateAujourdhui = new DateTime();
        if ($dateSaisie >= $dateAujourdhui) {
            $erreurs[] = "La date de naissance doit être dans le passé.";
        }
    } else {
        $erreurs[] = "La date de naissance est obligatoire.";
    }

    // S'il y a une erreur, on bloque et on renvoie à l'inscription
    if (!empty($erreurs)) {
        header("Location: ../inscription.php?erreur=");
        exit();
    }
    // fin sécu

    $fichier_json = '../data/utilisateur.json';
    $utilisateurs=[];

    if (file_exists($fichier_json)){
        $contenu_json=file_get_contents($fichier_json);
        $utilisateurs=json_decode($contenu_json,true);
    }

//hachage du mot de passe pour la sécurité
    $mot_de_passe = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    $nouvel_utilisateur = [
        'id' => count($utilisateurs) + 1,
        'nom' => $nom,
        'prenom' => $prenom,
        'login' => $email,
        'mdp' => $mot_de_passe,
        'telephone' => $numero_telephone,
        'adresse' => $adresse,
        'interphone' => $interphone,
        'etage' => $etage,
        'commentaires' => $commentaires,
        'role' => 'client',
        "naissance" => $naissance,
        "dernier_anniv_offert" => ""
    ];
    $utilisateurs[] = $nouvel_utilisateur;
    $nouveau_contenu_json = json_encode($utilisateurs, JSON_PRETTY_PRINT);
    file_put_contents($fichier_json, $nouveau_contenu_json);

    header('Location: ../connexion.php?succes=inscription');
    exit();
}
// On refait une validation côté serveur et on transforme les mdp en hachage pour la sécurité
?>