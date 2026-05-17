document.addEventListener("DOMContentLoaded", function() {
    
    const formProfil = document.getElementById('form-profil');
    const btnModifier = document.getElementById('btn-modifier');
    const btnSauvegarder = document.getElementById('btn-sauvegarder');
    const msgRetour = document.getElementById('msg-retour');

    // Vérifie qu'on est bien sur la page profil avant d'exécuter le code
    if (formProfil && btnModifier) {
        
        // 1. Quand on clique sur "Modifier"
        btnModifier.addEventListener('click', function() {
            // On trouve tous les <input> du formulaire
            const inputs = formProfil.querySelectorAll('input');
            
            // On enlève l'attribut "readonly" pour pouvoir écrire dedans
            inputs.forEach(input => input.removeAttribute('readonly'));
            
            // On cache le bouton "Modifier" et on affiche "Valider"
            btnModifier.style.display = 'none';
            btnSauvegarder.style.display = 'inline-block';
        });

        // 2. Quand on clique sur "Valider" (Soumission du formulaire)
        formProfil.addEventListener('submit', function(evenement) {
            // 🛑 LIGNE CRUCIALE : Empêche la page de se recharger !
            evenement.preventDefault(); 
            
            // On capture toutes les données tapées dans le formulaire
            const formData = new FormData(formProfil);

            //ON LANCE LA REQUÊTE ASYNCHRONE (AJAX/FETCH)
            fetch('traitement/update_profil.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // On s'attend à recevoir une réponse en JSON du PHP
            .then(data => {
                if (data.success) {
                    // Si le PHP dit que c'est bon
                    msgRetour.textContent = "Vos informations ont bien été mises à jour ! 🎉";
                    msgRetour.style.color = "var(--success-text)"; // On utilise tes belles variables CSS !
                    
                    // On reverrouille le formulaire
                    const inputs = formProfil.querySelectorAll('input');
                    inputs.forEach(input => input.setAttribute('readonly', true));
                    
                    // On inverse les boutons à nouveau
                    btnSauvegarder.style.display = 'none';
                    btnModifier.style.display = 'inline-block';
                } else {
                    // Si le PHP a détecté une erreur
                    msgRetour.textContent = "Erreur : " + data.message;
                    msgRetour.style.color = "var(--red-gossip)";
                }
            })
            .catch(error => {
                // S'il y a un problème de connexion avec le serveur
                msgRetour.textContent = "Impossible de joindre le serveur.";
            });
        });
    }
});