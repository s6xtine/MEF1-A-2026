document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const passwordCounter = document.getElementById('password-counter');
    const minLength = 8; 

    //Compteur de caractères pour le mot de passe
    if (passwordInput && passwordCounter) {
        
        passwordCounter.textContent = `Minimum ${minLength} caractères requis.`;

        // !! L'événement "input" se déclenche à chaque fois que l'utilisateur tape ou efface un caractère dans le champ de mot de passe.
        passwordInput.addEventListener('input', function() {
            const currentLength = passwordInput.value.length;
            const remaining = minLength - currentLength;

            if (remaining > 0) {
                passwordCounter.textContent = `${remaining} caractère(s) manquant(s).`;
                passwordCounter.classList.add('texte-erreur');
                passwordCounter.classList.remove('texte-succes');
            } else {
                passwordCounter.textContent = "Longueur suffisante ";
                passwordCounter.classList.remove('texte-erreur');
                passwordCounter.classList.add('texte-succes');
            }
        });
    }

    // Validation du formulaire à la soumission
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const email = document.querySelector('input[type="email"]').value;
            const password = passwordInput.value;
            let errors = [];

            // !! Expression régulière pour valider le format de l'email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                errors.push("L'adresse email n'est pas valide.");
            }

           
            if (password.length < minLength) {
                errors.push(`Le mot de passe doit faire au moins ${minLength} caractères.`);
            }

            const naissanceInput = document.getElementById('naissance');
            if (naissanceInput && naissanceInput.value) {
                const dateSaisie = new Date(naissanceInput.value);
                const dateAujourdhui = new Date(); // Génère la date du jour au moment de la validation
                
                // On vérifie que la date n'est pas dans le futur
                if (dateSaisie >= dateAujourdhui) {
                    errors.push("La date de naissance doit être dans le passé.");
                }
            }else if (naissanceInput && !naissanceInput.value) {
                errors.push("Veuillez renseigner votre date de naissance.");
            }

            // S'il y a des erreurs, on bloque l'envoi et on affiche l'alerte
            if (errors.length > 0) {
                // !! preventDefault() empêche la page de changer et d'envoyer les données.
                e.preventDefault();
                alert("Erreurs :\n" + errors.join("\n"));
            }
        });
    }

    //Code pour le bouton "Afficher/Masquer le mot de passe"
    const champMdp = document.getElementById("password");
    const boutonOeil = document.getElementById("btn-oeil");

    //Sécurité : on vérifie qu'on est bien sur une page avec un mot de passe
    if (boutonOeil && champMdp) { 
        
        boutonOeil.onclick = function() {
            // !! on modifie le type du champ
            // Si c'est un mot de passe caché
            if (champMdp.type == "password") {
                champMdp.type = "text";
                boutonOeil.innerHTML = "🙈"; 
            } 
            else {
                champMdp.type = "password";
                boutonOeil.innerHTML = "👁️";
            }
        };
    }
    //Ce fichier sécurise l'expérience client côté navigateur lors de l'inscription. Longueur du mot de passe, format de l'email, date de naissance, et il gère aussi le bouton d'affichage du mot de passe.
});