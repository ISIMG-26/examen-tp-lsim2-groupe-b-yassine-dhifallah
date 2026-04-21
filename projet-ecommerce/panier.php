<?php
// panier.php — Page du panier d'achat
session_start();
require_once 'back/connexion_db.php';

// Rediriger si non connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit;
}

$user_id = (int)$_SESSION['user_id'];

// Récupérer les articles du panier
$stmt = $pdo->prepare(
    'SELECT pan.id AS panier_id, pan.quantite,
            prod.id AS produit_id, prod.nom, prod.prix, prod.description
     FROM panier pan
     JOIN produits prod ON pan.produit_id = prod.id
     WHERE pan.utilisateur_id = :u'
);
$stmt->execute([':u' => $user_id]);
$articles = $stmt->fetchAll();

// Calculer le total
$total = array_sum(array_map(fn($a) => $a['prix'] * $a['quantite'], $articles));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier — ModeTrend</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@400;600&display=swap" rel="stylesheet">
</head>
<body data-page="panier">

<header>
    <div class="header-inner">
        <a href="index.php" class="logo">Mode<span>Trend</span></a>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="produits.php">Produits</a></li>
                <li><a href="panier.php" class="active btn-panier">🛒 Panier <span id="compteur-panier">0</span></a></li>
                <li><a href="back/logout.php">👤 <?= htmlspecialchars($_SESSION['user_prenom']) ?></a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <h1 class="section-titre">Mon Panier</h1>

    <?php if (empty($articles)): ?>
    <!-- Panier vide -->
    <div class="panier-vide" id="panier-vide">
        <div class="icone">🛒</div>
        <p>Votre panier est vide.</p>
        <a href="produits.php" class="btn btn-primaire" style="margin-top:1rem;">Voir les produits</a>
    </div>

    <?php else: ?>
    <!-- Tableau panier -->
    <div id="tableau-panier">
        <table class="table-panier">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Sous-total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                <tr class="ligne-panier"
                    data-prix="<?= $article['prix'] ?>"
                    data-id="<?= $article['panier_id'] ?>">
                    <td>
                        <strong><?= htmlspecialchars($article['nom']) ?></strong>
                        <br>
                        <small style="color:#888;"><?= htmlspecialchars(substr($article['description'] ?? '', 0, 50)) ?></small>
                    </td>
                    <td><?= number_format($article['prix'], 2) ?> €</td>
                    <td>
                        <span class="qte-val"><?= $article['quantite'] ?></span>
                    </td>
                    <td>
                        <strong><?= number_format($article['prix'] * $article['quantite'], 2) ?> €</strong>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm btn-supprimer"
                                data-panier-id="<?= $article['panier_id'] ?>">
                            🗑️ Supprimer
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-panier">
            <p>Total : <span class="montant" id="total-montant"><?= number_format($total, 2) ?> €</span></p>
            <div style="display:flex;gap:1rem;justify-content:flex-end;margin-top:1rem;">
                <button class="btn btn-secondaire" id="btn-vider-panier">🗑️ Vider le panier</button>
                <button class="btn btn-primaire" onclick="afficherToast('✅ Commande enregistrée — merci !')">
                    ✅ Commander
                </button>
            </div>
        </div>
    </div>

    <div class="panier-vide" id="panier-vide" style="display:none;">
        <div class="icone">🛒</div>
        <p>Votre panier est vide.</p>
        <a href="produits.php" class="btn btn-primaire" style="margin-top:1rem;">Voir les produits</a>
    </div>
    <?php endif; ?>
</main>

<footer>
    <p>&copy; <?= date('Y') ?> ModeTrend — Tous droits réservés | <a href="auth.php">Mon compte</a></p>
</footer>

<div id="toast"></div>
<script src="js/main.js"></script>
</body>
</html>
