<?php 
session_start();

if ($SERVER ['REQUEST_METHOD']) {
    $nom = $POST{'nom'};
    $prenom = $POST{'prenom'};
    $email = $POST{'email'};
    $mot_de_passe = $POST{'mot_de_passe'};
    $numero_telephone = $POST{'telephone'};
    $adresse = $POST{'adresse'};
    $interphone = $POST{'interphone'};
    $etage = $POST{'etage'};
    $commentaires = $POST{'commentaires'};

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
        'email' => $email,
        'mot_de_passe' => $mot_de_passe,
        'numero_telephone' => $numero_telephone,
        'adresse' => $adresse,
        'interphone' => $interphone,
        'etage' => $etage,
        'commentaires' => $commentaires
    ];
    $utilisateur[] = $nouvel_utilisateur;
    $nouveau_contenu_json = json_encode($utilisateurs, JSON_PRETTY_PRINT);
    file_put_contents($fichier_json, $nouveau_contenu_json);

    header('Lcation: connexion.php?sucess=inscription');
    exit();
}




