document.addEventListener("DOMContentLoaded", function() {
//  !! DOMContentLoaded garantit que le JS attend que tout le HTML soit chargé et dessiné à l'écran avant de chercher les boutons.

    const formProfil = document.getElementById('form-profil');
    const btnModifier = document.getElementById('btn-modifier');
    const btnSauvegarder = document.getElementById('btn-sauvegarder');
    const msgRetour = document.getElementById('msg-retour');

    // Vérifie qu'on est bien sur la page profil avant d'exécuter le code
    if (formProfil && btnModifier) {
        
        // 1. Quand on clique sur "Modifier"
        btnModifier.addEventListener('click', function() {
            // On trouve tous les <input> du formulaire
            const inputs = formProfil.querySelectorAll('input, textarea');
            
            // !! On enlève l'attribut "readonly" pour rendre les champs modifiables
            inputs.forEach(input => input.removeAttribute('readonly'));
            
            // On cache le bouton "Modifier" et on affiche "Valider" ; .cache est une classe CSS
            btnModifier.classList.add('cache');
            btnSauvegarder.classList.remove('cache');
        });

        // 2. Quand on clique sur "Valider" (Soumission du formulaire)
        formProfil.addEventListener('submit', function(evenement) {
            // !! preventDefault() annule le comportement par défaut du formulaire (qui est de recharger la page en envoyant les données).
            evenement.preventDefault(); 
            
            // !! On capture toutes les données tapées dans le formulaire
            const formData = new FormData(formProfil);

            //ON LANCE LA REQUÊTE ASYNCHRONE (AJAX/FETCH) ; fetch() envoie le colis formData au fichier PHP en arrière-plan, sans recharger la page.
            fetch('traitement/update_profil.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // On s'attend à recevoir une réponse en JSON du PHP
            .then(data => {
                if (data.success) {
                    // Si le PHP dit que c'est bon
                    msgRetour.textContent = "Vos informations ont bien été mises à jour ! 🎉";
                   
                   // On nettoie la classe d'erreur au cas où elle y serait, et on ajoute celle de succès
                    msgRetour.classList.remove('texte-erreur');
                    msgRetour.classList.add('texte-succes');
                    
                    // On reverrouille le formulaire
                    const inputs = formProfil.querySelectorAll('input, textarea');
                    inputs.forEach(input => input.setAttribute('readonly', true));
                    
                    // On inverse les boutons à nouveau
                    btnSauvegarder.classList.add('cache');
                    btnModifier.classList.remove('cache');
                } else {
                    // Si le PHP a détecté une erreur
                    msgRetour.textContent = "Erreur : " + data.message;

                    // On nettoie la classe de succès au cas où elle y serait, et on ajoute celle d'erreur
                    msgRetour.classList.remove('texte-succes');
                    msgRetour.classList.add('texte-erreur');
                }
            })
            .catch(error => {
                // S'il y a un problème de connexion avec le serveur
                msgRetour.textContent = "Impossible de joindre le serveur.";

                msgRetour.classList.remove('texte-succes');
                msgRetour.classList.add('texte-erreur');
            });
        });
    }

    // Ce fichier gère la page de profil du client. Son but est de permettre au client de modifier ses informations sans que la page ne se recharge, grâce à une requête asynchrone (AJAX).
});