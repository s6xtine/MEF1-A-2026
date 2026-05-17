document.addEventListener('DOMContentLoaded', function() {
    
    // On récupère tous les boutons "Bloquer/Débloquer" de la page
    const boutonsStatut = document.querySelectorAll('.btn-statut');

    boutonsStatut.forEach(bouton => {
        bouton.onclick = function() {
            // On récupère l'email/login caché dans le bouton
            const loginCible = this.getAttribute('data-login');
            
            // On prépare le colis pour le PHP
            let formData = new FormData();
            formData.append('login_cible', loginCible);

            // On envoie en asynchrone
            fetch('traitement/update_statut.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // On met à jour le texte du bouton instantanément !
                    if (data.est_bloque) {
                        this.textContent = "Débloquer 🟢";
                    } else {
                        this.textContent = "Bloquer 🔴";
                    }
                } else {
                    alert("Erreur lors de la modification du statut.");
                }
            });
        };
    });
});