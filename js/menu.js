// --- État des filtres actifs ---
const filtresActifs = {
    categorie: '',
    tag: '',       
    gout: ''
};
let triActif = 'defaut';

// DOMContentLoaded = attend que toute la page HTML soit chargée
// Sans ça le JS chercherait des boutons qui n'existent pas encore et ça planterait
document.addEventListener("DOMContentLoaded", function() {

    // ==========================================
    // 🛠️ ACTION DU BOUTON DE REMISE À ZÉRO
    // ==========================================
    const btnReset = document.getElementById('btn-reset-filtres');
    if (btnReset) {
        btnReset.addEventListener('click', function() {
            // Remet toutes les variables de filtres à vide
            filtresActifs.categorie = '';
            filtresActifs.tag = '';
            filtresActifs.gout = '';
            triActif = 'defaut';

            document.querySelectorAll('.filtres-btns button').forEach(btn => btn.classList.remove('actif'));
            document.querySelectorAll('[data-val=""], [data-tri="defaut"]').forEach(btn => btn.classList.add('actif'));

            appliquerFiltres();
        });
    }

    // 1. Catégories et Goûts (Sélection unique via .js-filtre)
    document.querySelectorAll('.js-filtre').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.dataset.type; // Récupère "categorie" ou "gout"
            
            // Enlève "actif" de tous les boutons du même groupe (même data-type)
            document.querySelectorAll(`.js-filtre[data-type="${type}"]`).forEach(b => b.classList.remove('actif'));
            this.classList.add('actif'); // Met "actif" sur le bouton cliqué
            
            filtresActifs[type] = this.dataset.val;
            appliquerFiltres(); // Envoie la requête au serveur
        });
    });

    // 2. Régimes (js-tag)
    // Peut être désactivé en recliquant dessus
    document.querySelectorAll('.js-tag').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.dataset.type; // Récupère "tag"
            if (this.classList.contains('actif')) {
                // Le bouton est déjà actif → on le désactive
                this.classList.remove('actif');
                filtresActifs[type] = ''; // Remet le filtre à vide

                // Si on désactive, on remet le bouton "Tout" par défaut s'il existe
                // Le bouton n'est pas actif → on désactive tous les autres et on active celui-ci
            } else {
                document.querySelectorAll('.js-tag').forEach(b => b.classList.remove('actif'));
                this.classList.add('actif');
                filtresActifs[type] = this.dataset.val;
            }
            appliquerFiltres();
        });
    });

    // 3. Tri (Sélection unique via .js-tri)
    document.querySelectorAll('.js-tri').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.js-tri').forEach(b => b.classList.remove('actif')); // Enlève "actif" de tous les boutons de tri
            this.classList.add('actif'); // Met "actif" sur le bouton cliqué
            triActif = this.dataset.tri;

            const aucunFiltre = filtresActifs.categorie === ''
                             && filtresActifs.tag === ''
                             && filtresActifs.gout === '';

            if (aucunFiltre) {
                trierSectionsNormales();
            } else {
                appliquerFiltres();
            }
        });
    });
});

// --- Fonction principale : envoie la requête fetch ---
function appliquerFiltres() {
    const aucunFiltre = filtresActifs.categorie === ''
                     && filtresActifs.tag === ''
                     && filtresActifs.gout === '';

    if (aucunFiltre) { // Pas de filtre actif : affiche les sections normales
        document.getElementById('resultats-filtres').style.display = 'none';
        const sectionsNormales = document.getElementById('sections-normales');
        if (sectionsNormales) sectionsNormales.style.display = 'block';
        trierSectionsNormales();
        return;
    }

    const params = new URLSearchParams();
    if (filtresActifs.categorie) params.set('categorie', filtresActifs.categorie);
    if (filtresActifs.gout)      params.set('gout', filtresActifs.gout);
    if (filtresActifs.tag)       params.set('tag', filtresActifs.tag);

    // fetch = requête asynchrone vers get_produits.php SANS recharger la page
    // C'est la connexion asynchrone obligatoire demandée dans les consignes

    fetch('traitement/get_produits.php?' + params.toString())
        .then(response => response.json()) // Convertit la réponse en tableau JavaScript
        .then(plats => {
            plats = trierTableau(plats); // Trie les résultats reçus
            afficherResultats(plats);
        })
        .catch(err => {
            console.error('Erreur fetch :', err);
        });
}

// --- Tri d'un tableau de plats ---
function trierTableau(plats) {
    // [...plats] = copie le tableau pour ne pas modifier l'original
    // .sort((a, b) => ...) = compare deux plats entre eux
    if (triActif === 'prix_asc')  return [...plats].sort((a, b) => a.prix - b.prix);
    if (triActif === 'prix_desc') return [...plats].sort((a, b) => b.prix - a.prix);
    if (triActif === 'populaire') return [...plats].sort((a, b) => b.nb_commandes - a.nb_commandes);
    return plats;
}

// --- Tri dans les sections normales ---
// Trie les éléments HTML déjà présents SANS requête serveur = plus rapide
function trierSectionsNormales() {
    ['boissons', 'sale', 'sucre'].forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (!section) return; // Sécurité : si la section n'existe pas on arrête
        const liste = section.querySelector('.menu-list');
        if (!liste) return; 
        const items = Array.from(liste.querySelectorAll('li')); // Convertit en vrai tableau pour pouvoir trier

        items.sort((a, b) => {
            const prixA = parseFloat(a.dataset.prix || 0);
            const prixB = parseFloat(b.dataset.prix || 0);
            if (triActif === 'prix_asc')  return prixA - prixB;
            if (triActif === 'prix_desc') return prixB - prixA;
            return 0;
        });

        items.forEach(item => liste.appendChild(item));
    });
}

// --- Afficher les résultats filtrés ---
// Crée les cartes produits et les injecte dans le HTML
function afficherResultats(plats) {
    const section = document.getElementById('resultats-filtres');
    const titre   = document.getElementById('resultats-titre');
    const liste   = document.getElementById('liste-resultats');

    const sectionsNormales = document.getElementById('sections-normales');
    if (sectionsNormales) sectionsNormales.style.display = 'none';
    
    section.style.display = 'block';
    titre.textContent = `${plats.length} résultat${plats.length > 1 ? 's' : ''}`;
    liste.innerHTML = '';

    if (plats.length === 0) {
        liste.innerHTML = '<li class="aucun-resultat">😔 Aucun produit ne correspond à ta sélection.</li>';
        return;
    }

     // Pour chaque plat reçu, crée une carte HTML et l'ajoute dans la liste
    plats.forEach(plat => {
        const li = document.createElement('li');
        li.className = 'plat-avec-image';
        li.innerHTML = `
            <img src="${plat.image}" alt="${plat.nom}" class="plat-image">
            <div class="plat-infos">
                <span class="item-nom">${plat.nom}</span>
                <span class="item-desc">${plat.description}</span>
                <form action="traitement/ajoute_panier.php" method="POST" class="form-sans-boite">
                    <input type="hidden" name="id_produit" value="${plat.id}">
                    <input type="hidden" name="nom" value="${plat.nom}">
                    <input type="hidden" name="prix" value="${plat.prix}">
                    <div class="panier-controls">
                        <input type="number" name="quantite" value="1" min="1" max="10" class="input-qte">
                    </div>
                    <button type="submit" class="btn-gossip btn-xs">
                        ${parseFloat(plat.prix).toFixed(2).replace('.', ',')} €
                    </button>
                </form>
            </div>
        `;
        liste.appendChild(li); // Ajoute la carte dans la liste
    });
}