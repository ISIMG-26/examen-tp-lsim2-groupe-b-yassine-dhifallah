# ModeTrend — Boutique de Vêtements en Ligne

## Informations du groupe

| Champ        | Valeur            |
|--------------|-------------------|
| **Nom**      | À compléter       |
| **Prénom**   | À compléter       |
| **Section**  | Groupe X          |
| **Cours**    | Technologies & Programmation Web 2025-2026 |

## Description du projet

**ModeTrend** est un mini site e-commerce de vente de vêtements développé en HTML/CSS/JavaScript natif et PHP avec une base de données MySQL.

Le site propose :
- Une **page d'accueil** avec présentation des produits en vedette et des catégories
- Un **catalogue de produits** avec filtrage dynamique par catégorie et recherche en temps réel (AJAX)
- Une **page d'authentification** avec connexion et inscription (validation côté client + serveur)
- Une **page panier** permettant de gérer ses articles (ajout, suppression, total dynamique)

## Structure du projet

```
projet-ecommerce/
├── index.php              ← Page d'accueil
├── produits.php           ← Catalogue (chargement AJAX)
├── auth.php               ← Connexion / Inscription
├── panier.php             ← Panier de l'utilisateur
├── README.md
│
├── css/
│   └── style.css          ← Feuille de style externe unique
│
├── js/
│   └── main.js            ← JavaScript externe (DOM, AJAX, validation)
│
├── back/
│   ├── connexion_db.php   ← Connexion PDO MySQL
│   ├── login.php          ← Traitement connexion (POST)
│   ├── register.php       ← Traitement inscription (POST)
│   ├── get_produits.php   ← Endpoint AJAX : liste des produits (GET)
│   ├── panier_action.php  ← Endpoint AJAX : gestion du panier (POST)
│   ├── check_email.php    ← Endpoint AJAX : vérification email (GET)
│   └── logout.php         ← Déconnexion
│
└── database/
    └── script.sql         ← Script de création + données de test
```

## Installation (XAMPP / WAMP)

1. Copier le dossier dans `htdocs/` (XAMPP) ou `www/` (WAMP)
2. Ouvrir **phpMyAdmin** et importer `database/script.sql`
3. Vérifier les paramètres dans `back/connexion_db.php` (host, user, pass)
4. Accéder à `http://localhost/projet-ecommerce/`

### Compte de test
- **Email** : alice@test.com
- **Mot de passe** : password (à tester avec le hash dans le SQL)

## Technologies utilisées

| Technologie | Usage |
|-------------|-------|
| HTML5 sémantique | Structure des pages |
| CSS3 externe | Mise en forme, variables, responsive |
| JavaScript natif | DOM, AJAX (fetch), validation formulaires |
| PHP natif | Back-end, sessions, traitement POST/GET |
| MySQL + PDO | Base de données, requêtes préparées |

## Fonctionnalités couvertes

- ✅ Structure HTML sémantique (header, nav, section, footer, article)
- ✅ CSS externe avec variables et responsive
- ✅ Manipulation du DOM (getElementById, querySelector, createElement)
- ✅ Événements utilisateurs (click, input, submit, change)
- ✅ Validation formulaires côté client (email, mot de passe, champs obligatoires)
- ✅ AJAX avec `fetch` : chargement produits, ajout panier, vérification email
- ✅ PHP : `$_POST`, `$_GET`, `$_SESSION`, traitement formulaires
- ✅ MySQL : 4 tables liées, SELECT, INSERT, UPDATE, DELETE (CRUD complet)
- ✅ Navigation cohérente sur toutes les pages

## Répartition des tâches

| Tâche | Membre |
|-------|--------|
| HTML / Structure des pages | À compléter |
| CSS / Design | À compléter |
| JavaScript / AJAX | À compléter |
| PHP / Back-end | À compléter |
| Base de données | À compléter |
