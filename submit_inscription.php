<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['login'];
    $mot_de_passe = $_POST['mdp'];
    $numero_telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];
    $interphone = $_POST['interphone'];
    $etage = $_POST['etage'];
    $commentaires = $_POST['commentaires'];

    $fichier_json = 'data/utilisateur.json';
    $utilisateurs=[];

    if (file_exists($fichier_json)){
        $contenu_json=file_get_contents($fichier_json);
        $utilisateurs=json_decode($contenu_json,true);
    }

    $nouvel_utilisateur = [
        'id' => count($utilisateurs) + 1,
        'nom' => $nom,
        'prenom' => $prenom,
        'login' => $email,
        'mdp' => $mot_de_passe,
        'numero_telephone' => $numero_telephone,
        'adresse' => $adresse,
        'interphone' => $interphone,
        'etage' => $etage,
        'commentaires' => $commentaires,
        'role' => 'client'
    ];
    $utilisateurs[] = $nouvel_utilisateur;
    $nouveau_contenu_json = json_encode($utilisateurs, JSON_PRETTY_PRINT);
    file_put_contents($fichier_json, $nouveau_contenu_json);

    header('Location: connexion.php?sucess=inscription');
    exit();
}
?>




