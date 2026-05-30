// --- État des filtres actifs ---
const filtresActifs = {
    categorie: '',
    tag: '',       
    gout: ''
};
let triActif = 'defaut';

document.addEventListener("DOMContentLoaded", function() {

    // ==========================================
    // 🛠️ ACTION DU BOUTON DE REMISE À ZÉRO
    // ==========================================
    const btnReset = document.getElementById('btn-reset-filtres');
    if (btnReset) {
        btnReset.addEventListener('click', function() {
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
            const type = this.dataset.type;
            document.querySelectorAll(`.js-filtre[data-type="${type}"]`).forEach(b => b.classList.remove('actif'));
            this.classList.add('actif');
            
            filtresActifs[type] = this.dataset.val;
            appliquerFiltres();
        });
    });

    // 2. Régimes / Tags (Comportement de sélection unique)
    document.querySelectorAll('.js-tag').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.dataset.type; 
            if (this.classList.contains('actif')) {
                this.classList.remove('actif');
                filtresActifs[type] = '';
                // Si on désactive, on remet le bouton "Tout" par défaut s'il existe
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
            document.querySelectorAll('.js-tri').forEach(b => b.classList.remove('actif'));
            this.classList.add('actif');
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

    if (aucunFiltre) {
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

    fetch('traitement/get_produits.php?' + params.toString())
        .then(response => response.json())
        .then(plats => {
            plats = trierTableau(plats);
            afficherResultats(plats);
        })
        .catch(err => {
            console.error('Erreur fetch :', err);
        });
}

// --- Tri d'un tableau de plats ---
function trierTableau(plats) {
    if (triActif === 'prix_asc')  return [...plats].sort((a, b) => a.prix - b.prix);
    if (triActif === 'prix_desc') return [...plats].sort((a, b) => b.prix - a.prix);
    if (triActif === 'populaire') return [...plats].sort((a, b) => b.nb_commandes - a.nb_commandes);
    return plats;
}

// --- Tri dans les sections normales ---
function trierSectionsNormales() {
    ['boissons', 'sale', 'sucre'].forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (!section) return;
        const liste = section.querySelector('.menu-list');
        if (!liste) return;
        const items = Array.from(liste.querySelectorAll('li'));

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
        liste.appendChild(li);
    });
}