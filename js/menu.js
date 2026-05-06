// --- État des filtres actifs ---
const filtresActifs = {
    categorie: '',
    tags: [],
    gout: ''
};
let triActif = 'defaut';

// --- Boutons catégorie et goût (sélection unique) ---
document.querySelectorAll('.filtre-btn:not(.tag-btn)').forEach(btn => {
    btn.addEventListener('click', function() {
        const type = this.dataset.type;
        document.querySelectorAll(`.filtre-btn[data-type="${type}"]`).forEach(b => b.classList.remove('actif'));
        this.classList.add('actif');
        filtresActifs[type] = this.dataset.val;
        appliquerFiltres();
    });
});

// --- Boutons tags (sélection multiple) ---
document.querySelectorAll('.tag-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const tag = this.dataset.val;
        if (this.classList.contains('actif')) {
            this.classList.remove('actif');
            filtresActifs.tags = filtresActifs.tags.filter(t => t !== tag);
        } else {
            this.classList.add('actif');
            filtresActifs.tags.push(tag);
        }
        appliquerFiltres();
    });
});

// --- Boutons de tri (côté client) ---
document.querySelectorAll('.tri-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tri-btn').forEach(b => b.classList.remove('actif'));
        this.classList.add('actif');
        triActif = this.dataset.tri;

        const aucunFiltre = filtresActifs.categorie === ''
                         && filtresActifs.tags.length === 0
                         && filtresActifs.gout === '';

        if (aucunFiltre) {
            trierSectionsNormales();
        } else {
            appliquerFiltres();
        }
    });
});

// --- Fonction principale : envoie la requête fetch ---
function appliquerFiltres() {
    const aucunFiltre = filtresActifs.categorie === ''
                     && filtresActifs.tags.length === 0
                     && filtresActifs.gout === '';

    if (aucunFiltre) {
        document.getElementById('resultats-filtres').style.display = 'none';
        document.getElementById('sections-normales').style.display = 'block';
        trierSectionsNormales();
        return;
    }

    // Construire l'URL avec les paramètres
    const params = new URLSearchParams();
    if (filtresActifs.categorie) params.set('categorie', filtresActifs.categorie);
    if (filtresActifs.gout)      params.set('gout', filtresActifs.gout);
    if (filtresActifs.tags.length > 0) params.set('tags', filtresActifs.tags.join(','));

    // Requête asynchrone vers get_produits.php
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

// --- Tri d'un tableau de plats (résultats filtrés) ---
function trierTableau(plats) {
    if (triActif === 'prix_asc')  return [...plats].sort((a, b) => a.prix - b.prix);
    if (triActif === 'prix_desc') return [...plats].sort((a, b) => b.prix - a.prix);
    if (triActif === 'populaire') return [...plats].sort((a, b) => b.nb_commandes - a.nb_commandes);
    return plats;
}

// --- Tri dans les sections normales (sans filtre actif) ---
function trierSectionsNormales() {
    ['boissons', 'sale', 'sucre'].forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (!section) return;
        const liste = section.querySelector('.menu-list');
        const items = Array.from(liste.querySelectorAll('li'));

        items.sort((a, b) => {
            const prixA = parseFloat(a.dataset.prix || 0);
            const prixB = parseFloat(b.dataset.prix || 0);
            const nbA   = parseInt(a.dataset.nb || 0);
            const nbB   = parseInt(b.dataset.nb || 0);

            if (triActif === 'prix_asc')  return prixA - prixB;
            if (triActif === 'prix_desc') return prixB - prixA;
            if (triActif === 'populaire') return nbB - nbA;
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

    document.getElementById('sections-normales').style.display = 'none';
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
                <div class="plat-tags">
                    ${plat.tags.map(t => `<span class="tag-badge">${t.replace('_', ' ')}</span>`).join('')}
                </div>
                <form action="traitement/ajoute_panier.php" method="POST" class="form-ajout-panier">
                    <input type="hidden" name="id_produit" value="${plat.id}">
                    <input type="hidden" name="nom" value="${plat.nom}">
                    <input type="hidden" name="prix" value="${plat.prix}">
                    <div class="panier-controls">
                        <label>Qté :</label>
                        <input type="number" name="quantite" value="1" min="1" max="10" class="input-qte">
                    </div>
                    <button type="submit" class="btn-prix-action">
                        ${plat.prix.toFixed(2).replace('.', ',')} €
                    </button>
                </form>
            </div>
        `;
        liste.appendChild(li);
    });
}