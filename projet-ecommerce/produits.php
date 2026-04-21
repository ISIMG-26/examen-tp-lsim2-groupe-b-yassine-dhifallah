<?php
// produits.php — Catalogue de produits
session_start();
require_once 'back/connexion_db.php';

// Catégories pour le filtre
$stmt = $pdo->query('SELECT * FROM categories ORDER BY nom');
$categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits — ModeTrend</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@400;600&display=swap" rel="stylesheet">
</head>
<body data-page="produits">

<header>
    <div class="header-inner">
        <a href="index.php" class="logo">Mode<span>Trend</span></a>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="produits.php" class="active">Produits</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="panier.php" class="btn-panier">🛒 Panier <span id="compteur-panier">0</span></a></li>
                    <li><a href="back/logout.php">👤 <?= htmlspecialchars($_SESSION['user_prenom']) ?></a></li>
                <?php else: ?>
                    <li><a href="auth.php" class="btn-panier">🛒 Panier <span id="compteur-panier">0</span></a></li>
                    <li><a href="auth.php">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<main>
    <h1 class="section-titre">Notre Catalogue</h1>

    <!-- Barre de filtres (AJAX) -->
    <div class="filtres">
        <input type="text" id="recherche" placeholder="🔍 Rechercher un produit...">
        <select id="filtre-categorie">
            <option value="0">Toutes les catégories</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"
                <?= (isset($_GET['categorie']) && $_GET['categorie'] == $cat['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['nom']) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Les produits sont chargés dynamiquement par AJAX (js/main.js) -->
    <div class="grille-produits" id="liste-produits">
        <p style="color:#888;">Chargement des produits...</p>
    </div>
</main>

<footer>
    <p>&copy; <?= date('Y') ?> ModeTrend — Tous droits réservés | <a href="auth.php">Mon compte</a></p>
</footer>

<div id="toast"></div>
<script src="js/main.js"></script>
<script>
    // Pré-sélectionner la catégorie si passée en URL
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const cat = urlParams.get('categorie');
        if (cat) {
            const select = document.getElementById('filtre-categorie');
            if (select) {
                select.value = cat;
                chargerProduits(cat, '');
            }
        }
    });
</script>
</body>
</html>
