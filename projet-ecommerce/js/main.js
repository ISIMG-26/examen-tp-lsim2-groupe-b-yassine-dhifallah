// js/main.js — Boutique Mode
// Fichier JavaScript externe — DOM, AJAX, validation, événements

/* ===================================================
   UTILITAIRES
   =================================================== */

/** Affiche un toast (notification) temporaire */
function afficherToast(message, duree = 3000) {
    const toast = document.getElementById('toast');
    if (!toast) return;
    toast.textContent = message;
    toast.classList.add('visible');
    setTimeout(() => toast.classList.remove('visible'), duree);
}

/** Affiche/cache un message d'erreur sous un champ */
function afficherErreurChamp(champId, message) {
    const champ = document.getElementById(champId);
    const msgEl = document.getElementById('err-' + champId);
    if (champ) champ.classList.add('erreur');
    if (msgEl) {
        msgEl.textContent = message;
        msgEl.classList.add('visible');
    }
}

/** Efface tous les messages d'erreur d'un formulaire */
function effacerErreurs(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    form.querySelectorAll('.erreur').forEach(el => el.classList.remove('erreur'));
    form.querySelectorAll('.msg-erreur').forEach(el => {
        el.textContent = '';
        el.classList.remove('visible');
    });
}

/** Met à jour le compteur de panier dans le header */
function mettreAJourCompteurPanier(nb) {
    const el = document.getElementById('compteur-panier');
    if (el) el.textContent = nb;
}

/* ===================================================
   VALIDATION DES FORMULAIRES
   =================================================== */

/** Valide le formulaire de connexion — retourne true si OK */
function validerFormConnexion() {
    effacerErreurs('form-connexion');
    let valide = true;

    const email = document.getElementById('email-connexion').value.trim();
    const mdp   = document.getElementById('mdp-connexion').value;

    if (!email) {
        afficherErreurChamp('email-connexion', 'L\'email est obligatoire.');
        valide = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        afficherErreurChamp('email-connexion', 'Format email invalide.');
        valide = false;
    }

    if (!mdp) {
        afficherErreurChamp('mdp-connexion', 'Le mot de passe est obligatoire.');
        valide = false;
    }

    return valide;
}

/** Valide le formulaire d'inscription — retourne true si OK */
function validerFormInscription() {
    effacerErreurs('form-inscription');
    let valide = true;

    const nom    = document.getElementById('nom').value.trim();
    const prenom = document.getElementById('prenom').value.trim();
    const email  = document.getElementById('email-inscription').value.trim();
    const mdp    = document.getElementById('mdp-inscription').value;
    const conf   = document.getElementById('mdp-confirm').value;

    if (!nom) { afficherErreurChamp('nom', 'Le nom est obligatoire.'); valide = false; }
    if (!prenom) { afficherErreurChamp('prenom', 'Le prénom est obligatoire.'); valide = false; }

    if (!email) {
        afficherErreurChamp('email-inscription', 'L\'email est obligatoire.');
        valide = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        afficherErreurChamp('email-inscription', 'Format email invalide.');
        valide = false;
    }

    if (!mdp) {
        afficherErreurChamp('mdp-inscription', 'Le mot de passe est obligatoire.');
        valide = false;
    } else if (mdp.length < 6) {
        afficherErreurChamp('mdp-inscription', 'Minimum 6 caractères.');
        valide = false;
    }

    if (!conf) {
        afficherErreurChamp('mdp-confirm', 'Veuillez confirmer le mot de passe.');
        valide = false;
    } else if (mdp !== conf) {
        afficherErreurChamp('mdp-confirm', 'Les mots de passe ne correspondent pas.');
        valide = false;
    }

    return valide;
}

/* ===================================================
   AJAX — PRODUITS
   =================================================== */

/** Charge les produits via AJAX et les affiche */
function chargerProduits(categorie = 0, recherche = '') {
    const conteneur = document.getElementById('liste-produits');
    if (!conteneur) return;

    conteneur.innerHTML = '<p class="loader">⏳</p>';

    const params = new URLSearchParams({ categorie, q: recherche });

    fetch('back/get_produits.php?' + params.toString())
        .then(res => res.json())
        .then(data => {
            if (!data.succes || data.produits.length === 0) {
                conteneur.innerHTML = '<p style="color:#888;padding:2rem;">Aucun produit trouvé.</p>';
                return;
            }
            conteneur.innerHTML = '';
            data.produits.forEach(p => {
                const carte = creerCarteProduit(p);
                conteneur.appendChild(carte);
            });
        })
        .catch(() => {
            conteneur.innerHTML = '<p style="color:red;">Erreur de chargement des produits.</p>';
        });
}

/** Crée un élément DOM pour une carte produit */
function creerCarteProduit(produit) {
    const div = document.createElement('article');
    div.className = 'carte-produit';

    div.innerHTML = `
        <div class="img-placeholder">👗</div>
        <div class="carte-produit-corps">
            <span class="badge-categorie">${produit.categorie_nom || 'Autre'}</span>
            <h3>${produit.nom}</h3>
            <p>${produit.description || ''}</p>
            <div class="prix">${parseFloat(produit.prix).toFixed(2)} €</div>
            <button class="btn btn-primaire btn-sm btn-ajouter-panier"
                    data-id="${produit.id}"
                    data-nom="${produit.nom}">
                🛒 Ajouter
            </button>
        </div>
    `;

    // Événement ajout au panier
    div.querySelector('.btn-ajouter-panier').addEventListener('click', function () {
        ajouterAuPanier(this.dataset.id, this.dataset.nom);
    });

    return div;
}

/* ===================================================
   AJAX — PANIER
   =================================================== */

/** Ajoute un produit au panier via AJAX */
function ajouterAuPanier(produitId, nomProduit) {
    const data = new FormData();
    data.append('action', 'ajouter');
    data.append('produit_id', produitId);

    fetch('back/panier_action.php', { method: 'POST', body: data })
        .then(res => res.json())
        .then(rep => {
            if (rep.succes) {
                afficherToast('✅ ' + nomProduit + ' ajouté au panier !');
                rafraichirCompteurPanier();
            } else {
                afficherToast('⚠️ ' + (rep.message || 'Connectez-vous pour ajouter au panier.'));
            }
        })
        .catch(() => afficherToast('❌ Erreur réseau.'));
}

/** Récupère le nombre d'articles dans le panier et met à jour le compteur */
function rafraichirCompteurPanier() {
    fetch('back/panier_action.php?action=lister')
        .then(res => res.json())
        .then(data => {
            if (data.succes) {
                const total = data.items.reduce((s, i) => s + parseInt(i.quantite), 0);
                mettreAJourCompteurPanier(total);
            }
        })
        .catch(() => {});
}

/** Supprime un article du panier */
function supprimerDuPanier(panierIdEl) {
    const panier_id = panierIdEl.dataset.panierId;
    const data = new FormData();
    data.append('action', 'supprimer');
    data.append('panier_id', panier_id);

    fetch('back/panier_action.php', { method: 'POST', body: data })
        .then(res => res.json())
        .then(rep => {
            if (rep.succes) {
                // Supprimer la ligne du tableau dans le DOM
                const ligne = panierIdEl.closest('tr');
                if (ligne) ligne.remove();
                afficherToast('🗑️ Article supprimé.');
                recalculerTotal();
                rafraichirCompteurPanier();
            }
        });
}

/** Recalcule le total affiché dans la page panier */
function recalculerTotal() {
    const lignes  = document.querySelectorAll('.ligne-panier');
    let total     = 0;

    lignes.forEach(ligne => {
        const prix = parseFloat(ligne.dataset.prix || 0);
        const qte  = parseInt(ligne.querySelector('.qte-val').textContent || 1);
        total += prix * qte;
    });

    const el = document.getElementById('total-montant');
    if (el) el.textContent = total.toFixed(2) + ' €';
}

/* ===================================================
   AJAX — VÉRIFICATION EMAIL (inscription)
   =================================================== */

let timerEmailCheck = null;

function verifierEmailDisponible(email) {
    const msgEl = document.getElementById('err-email-inscription');
    if (!msgEl || !email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return;

    clearTimeout(timerEmailCheck);
    timerEmailCheck = setTimeout(() => {
        fetch('back/check_email.php?email=' + encodeURIComponent(email))
            .then(res => res.json())
            .then(data => {
                if (data.existe) {
                    const champ = document.getElementById('email-inscription');
                    if (champ) champ.classList.add('erreur');
                    msgEl.textContent = 'Cet email est déjà utilisé.';
                    msgEl.classList.add('visible');
                } else {
                    const champ = document.getElementById('email-inscription');
                    if (champ) champ.classList.remove('erreur');
                    msgEl.textContent = '';
                    msgEl.classList.remove('visible');
                }
            });
    }, 500); // délai de 500ms après la saisie
}

/* ===================================================
   INITIALISATION — PAGE PRODUITS
   =================================================== */

function initPageProduits() {
    const searchInput = document.getElementById('recherche');
    const selectCat   = document.getElementById('filtre-categorie');

    if (!searchInput && !selectCat) return;

    chargerProduits(); // chargement initial

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const cat = selectCat ? selectCat.value : 0;
            chargerProduits(cat, this.value.trim());
        });
    }

    if (selectCat) {
        selectCat.addEventListener('change', function () {
            const q = searchInput ? searchInput.value.trim() : '';
            chargerProduits(this.value, q);
        });
    }
}

/* ===================================================
   INITIALISATION — PAGE AUTH
   =================================================== */

function initPageAuth() {
    // Gestion onglets
    const onglets = document.querySelectorAll('.onglet-btn');
    onglets.forEach(btn => {
        btn.addEventListener('click', function () {
            onglets.forEach(b => b.classList.remove('actif'));
            this.classList.add('actif');
            document.querySelectorAll('.form-auth').forEach(f => f.classList.remove('actif'));
            document.getElementById(this.dataset.cible).classList.add('actif');
        });
    });

    // Soumission connexion via AJAX
    const formConnexion = document.getElementById('form-connexion');
    if (formConnexion) {
        formConnexion.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!validerFormConnexion()) return;

            const alerte = document.getElementById('alerte-connexion');
            const data = new FormData(this);

            fetch('back/login.php', { method: 'POST', body: data })
                .then(res => res.json())
                .then(rep => {
                    alerte.className = 'alerte visible ' + (rep.succes ? 'succes' : 'erreur');
                    alerte.textContent = rep.message;
                    if (rep.succes && rep.redirect) {
                        setTimeout(() => { window.location.href = rep.redirect; }, 1000);
                    }
                })
                .catch(() => {
                    alerte.className = 'alerte visible erreur';
                    alerte.textContent = 'Erreur réseau, réessayez.';
                });
        });
    }

    // Soumission inscription via AJAX
    const formInscription = document.getElementById('form-inscription');
    if (formInscription) {
        formInscription.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!validerFormInscription()) return;

            const alerte = document.getElementById('alerte-inscription');
            const data = new FormData(this);

            fetch('back/register.php', { method: 'POST', body: data })
                .then(res => res.json())
                .then(rep => {
                    alerte.className = 'alerte visible ' + (rep.succes ? 'succes' : 'erreur');
                    alerte.textContent = rep.message;
                    if (rep.succes && rep.redirect) {
                        setTimeout(() => { window.location.href = rep.redirect; }, 1200);
                    }
                })
                .catch(() => {
                    alerte.className = 'alerte visible erreur';
                    alerte.textContent = 'Erreur réseau, réessayez.';
                });
        });
    }

    // Vérification email disponible en temps réel
    const emailInsc = document.getElementById('email-inscription');
    if (emailInsc) {
        emailInsc.addEventListener('input', function () {
            verifierEmailDisponible(this.value.trim());
        });
    }
}

/* ===================================================
   INITIALISATION — PAGE PANIER
   =================================================== */

function initPagePanier() {
    // Boutons supprimer
    document.querySelectorAll('.btn-supprimer').forEach(btn => {
        btn.addEventListener('click', function () {
            supprimerDuPanier(this);
        });
    });

    // Bouton vider le panier
    const btnVider = document.getElementById('btn-vider-panier');
    if (btnVider) {
        btnVider.addEventListener('click', function () {
            if (!confirm('Vider tout le panier ?')) return;

            const data = new FormData();
            data.append('action', 'vider');

            fetch('back/panier_action.php', { method: 'POST', body: data })
                .then(res => res.json())
                .then(rep => {
                    if (rep.succes) {
                        document.querySelectorAll('.ligne-panier').forEach(l => l.remove());
                        afficherToast('🗑️ Panier vidé.');
                        mettreAJourCompteurPanier(0);
                        recalculerTotal();
                        // Afficher message panier vide
                        const table = document.getElementById('tableau-panier');
                        const videEl = document.getElementById('panier-vide');
                        if (table) table.style.display = 'none';
                        if (videEl) videEl.style.display = 'block';
                    }
                });
        });
    }
}

/* ===================================================
   DÉMARRAGE — détection de la page active
   =================================================== */

document.addEventListener('DOMContentLoaded', function () {
    // Toast global
    if (!document.getElementById('toast')) {
        const toast = document.createElement('div');
        toast.id = 'toast';
        document.body.appendChild(toast);
    }

    // Compteur panier dans le header
    rafraichirCompteurPanier();

    // Initialiser selon la page
    const page = document.body.dataset.page;
    if (page === 'produits') initPageProduits();
    if (page === 'auth')     initPageAuth();
    if (page === 'panier')   initPagePanier();
});
