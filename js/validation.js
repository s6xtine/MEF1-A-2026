document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. VALIDATION INSCRIPTION ---
    const formInscription = document.querySelector('form[action*="submit_inscription"]');
    
    if (formInscription) {
        formInscription.addEventListener('submit', function(e) {
            let erreurs = [];
            const email = formInscription.querySelector('input[type="email"]').value;
            const password = formInscription.querySelector('input[type="password"]').value;

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                erreurs.push("L'adresse e-mail n'est pas valide.");
            }

            if (password.length < 8) {
                erreurs.push("Le mot de passe doit contenir au moins 8 caractères.");
            }

            if (erreurs.length > 0) {
                e.preventDefault(); 
                alert("⚠️ Erreurs détectées :\n- " + erreurs.join("\n- "));
            }
        });
    }

    // --- 2. COMPTEUR DE CARACTÈRES (Avis / Instructions) ---
    const textAreas = document.querySelectorAll('textarea');
    
    textAreas.forEach(area => {
        const compteur = document.createElement('small');
        
        compteur.classList.add('compteur-caracteres');
        area.parentNode.insertBefore(compteur, area.nextSibling);

        area.addEventListener('input', function() {
            const reste = 200 - this.value.length;
            compteur.textContent = reste + " caractères restants";
            
            
            if (reste < 0) {
                compteur.classList.add('texte-alerte');
                area.classList.add('bordure-erreur');
            } else {
                compteur.classList.remove('texte-alerte');
                area.classList.remove('bordure-erreur');
            }
        });
    });

    // --- 3. AFFICHER/MASQUER LE MOT DE PASSE ---
    const passInputs = document.querySelectorAll('input[type="password"]');
    passInputs.forEach(input => {
        const toggleBtn = document.createElement('span');
        toggleBtn.innerHTML = " 👀";
        toggleBtn.classList.add('toggle-password'); 
        input.parentNode.insertBefore(toggleBtn, input.nextSibling);

        toggleBtn.addEventListener('click', function() {
            input.type = (input.type === "password") ? "text" : "password";
        });
    });
});