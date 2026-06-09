window.onload = function() {
    
    var themeBtn = document.getElementById("theme-toggle");

    // !! Fonction pour fouiller dans les cookies du navigateur
    // document.cookie renvoie une énorme chaîne de texte, cette fonction permet de l'isoler et de trouver uniquement la valeur du cookie "theme".
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

        // !! Manipulation du DOM (Document Object Model) : page web générée par le navigateur à partir de ton HTML, le JS peut la manipuler
        // Le JS va  "fabriquer" une balise <link> et l'injecter dans le <head> du HTML.
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
            // Si on veut le mode clair, on supprime la balise <link> du thème sombre s'il existe
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

    //Le script gère le mode sombre/clair du site. Il manipule le DOM pour injecter ou retirer la feuille de style du mode sombre et il manipule les Cookies du navigateur pour que le site mémorise le choix de l'utilisateur lorsqu'il change de page
};