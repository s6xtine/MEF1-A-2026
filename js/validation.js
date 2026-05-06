document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const passwordCounter = document.getElementById('password-counter');
    const minLength = 8; 

    if (passwordInput && passwordCounter) {
        
        passwordCounter.textContent = `Minimum ${minLength} caractères requis.`;

        
        passwordInput.addEventListener('input', function() {
            const currentLength = passwordInput.value.length;
            const remaining = minLength - currentLength;

            if (remaining > 0) {
                passwordCounter.textContent = `${remaining} caractère(s) manquant(s).`;
                passwordCounter.classList.add('texte-alerte');
                passwordCounter.classList.remove('valide');
            } else {
                passwordCounter.textContent = "Longueur suffisante ";
                passwordCounter.classList.remove('texte-alerte');
                passwordCounter.classList.add('valide');
            }
        });
    }

  
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const email = document.querySelector('input[type="email"]').value;
            const password = passwordInput.value;
            let errors = [];

            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                errors.push("L'adresse email n'est pas valide.");
            }

           
            if (password.length < minLength) {
                errors.push(`Le mot de passe doit faire au moins ${minLength} caractères.`);
            }

            
            if (errors.length > 0) {
                e.preventDefault();
                alert("Erreurs :\n" + errors.join("\n"));
            }
        });
    }
});