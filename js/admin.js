document.addEventListener('DOMContentLoaded', function() {
    
    // !! querySelectorAll récupère tous les boutons "Bloquer/Débloquer" de la page
    const boutonsStatut = document.querySelectorAll('.btn-statut');

    // On parcourt chaque bouton pour lui attacher une action au clic
    boutonsStatut.forEach(bouton => {
        bouton.onclick = function() {
            // On récupère l'email/login caché dans le bouton
            const loginCible = this.getAttribute('data-login');
            
            // On prépare un objet invisible pour transporter l'email vers le PHP
            let formData = new FormData();
            formData.append('login_cible', loginCible);

            // On envoie en asynchrone
            fetch('traitement/update_user_statut.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // !! "this" fait référence au bouton exact sur lequel on vient de cliquer.
                    // On met à jour le texte du bouton instantanément
                    if (data.est_bloque) {
                        this.textContent = "Débloquer 🟢";
                    } else {
                        this.textContent = "Bloquer 🔴";
                    }
                } else {
                    alert("Erreur lors de la modification du statut.");
                }
            })
            .catch(error => {
                // S'il y a un problème de connexion avec le serveur
                alert("Impossible de joindre le serveur. Veuillez réessayer.");
            });
        };
    });
    // Ce fichier gère la page d'administration, les boutons de blocage/déblocage des utilisateurs. Lorsqu'on clique sur un bouton, une requête asynchrone est envoyée au serveur
});