<?php
// index.php — Page d'accueil
session_start();
require_once 'back/connexion_db.php';

// Récupérer les 4 derniers produits pour la mise en avant
$stmt = $pdo->query('SELECT p.*, c.nom AS categorie_nom FROM produits p LEFT JOIN categories c ON p.categorie_id = c.id ORDER BY p.date_ajout DESC LIMIT 4');
$produits_vedette = $stmt->fetchAll();

// Récupérer les catégories
$stmt_cat = $pdo->query('SELECT * FROM categories ORDER BY nom');
$categories = $stmt_cat->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ModeTrend — Boutique en ligne</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@400;600&display=swap" rel="stylesheet">
</head>
<body data-page="accueil">

<header>
    <div class="header-inner">
        <a href="index.php" class="logo">Mode<span>Trend</span></a>
        <nav>
            <ul>
                <li><a href="index.php" class="active">Accueil</a></li>
                <li><a href="produits.php">Produits</a></li>
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
    <!-- Section hero -->
    <section class="hero">
        <h1>La mode qui vous ressemble</h1>
        <p>Découvrez notre nouvelle collection — des pièces soigneusement sélectionnées pour vous.</p>
        <a href="produits.php" class="btn btn-primaire">Voir la collection</a>
    </section>

    <!-- Catégories -->
    <section>
        <h2 class="section-titre">Nos catégories</h2>
        <div class="grille-categories">
            <?php
            $icones = ['Homme' => '👔', 'Femme' => '👗', 'Accessoires' => '👜'];
            foreach ($categories as $cat):
                $icone = $icones[$cat['nom']] ?? '🛍️';
            ?>
            <a href="produits.php?categorie=<?= $cat['id'] ?>" class="carte-categorie">
                <div class="icone-cat"><?= $icone ?></div>
                <h3><?= htmlspecialchars($cat['nom']) ?></h3>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Produits en vedette -->
    <section>
        <h2 class="section-titre">Nouveautés</h2>
        <div class="grille-produits">
            <?php foreach ($produits_vedette as $p): ?>
            <article class="carte-produit">
                <div class="img-placeholder">
                    <?= $p['categorie_nom'] === 'Homme' ? '👔' : ($p['categorie_nom'] === 'Femme' ? '👗' : '👜') ?>
                </div>
                <div class="carte-produit-corps">
                    <span class="badge-categorie"><?= htmlspecialchars($p['categorie_nom'] ?? 'Autre') ?></span>
                    <h3><?= htmlspecialchars($p['nom']) ?></h3>
                    <p><?= htmlspecialchars(substr($p['description'] ?? '', 0, 60)) ?>...</p>
                    <div class="prix"><?= number_format($p['prix'], 2) ?> €</div>
                    <a href="produits.php" class="btn btn-secondaire btn-sm">Voir plus</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<footer>
    <p>&copy; <?= date('Y') ?> ModeTrend — Tous droits réservés | <a href="auth.php">Mon compte</a></p>
</footer>

<div id="toast"></div>
<script src="js/main.js"></script>
</body>
</html>
