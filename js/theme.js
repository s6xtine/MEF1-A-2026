window.onload = function() {
    
    var themeBtn = document.getElementById("theme-toggle");

    function lireCookie(nomDuCookie) {
        var nomRecherche = nomDuCookie + "=";
        var listeCookies = document.cookie.split(';');
        
        for (var i = 0; i < listeCookies.length; i++) {
            var c = listeCookies[i];
            
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            
            if (c.indexOf(nomRecherche) == 0) {
                return c.substring(nomRecherche.length, c.length);
            }
        }
        return "";
    }

    function ecrireCookie(nom, valeur) {
        document.cookie = nom + "=" + valeur + "; path=/"; // Le "path=/" permet de rendre le cookie accessible sur toutes les pages du site
    }

    function appliquerTheme(theme) {
        var head = document.getElementsByTagName('head')[0];
        var darkLink = document.getElementById("sombre-theme-style");

        if (theme == "sombre") {
            if (darkLink == null) { // Si le lien n'existe pas déjà, on le crée
                var nouveauLien = document.createElement("link");
                nouveauLien.id = "sombre-theme-style";
                nouveauLien.rel = "stylesheet";
                nouveauLien.href = "style-sombre.css";
                head.appendChild(nouveauLien); // On l'injecte dans le HTML
            }
            themeBtn.innerHTML = "Mode Clair";
        } 
        else {
            // Si on veut le mode clair, on supprime le CSS sombre
            if (darkLink != null) {
                head.removeChild(darkLink);
            }
            themeBtn.innerHTML = "Mode Sombre";
        }
    }




    var themeActuel = lireCookie("theme");
    
    // Si le cookie a une valeur incohérente, le mode choisi est le mode par défaut
    if (themeActuel !== "sombre" && themeActuel !== "clair") {
        themeActuel = "clair"; 
    }
    appliquerTheme(themeActuel);



    themeBtn.onclick = function() {
        if (themeActuel == "clair") {
            themeActuel = "sombre";
        } else {
            themeActuel = "clair";
        }
        
        ecrireCookie("theme", themeActuel); // On sauvegarde
        appliquerTheme(themeActuel);        // On met à jour l'affichage
    };
};