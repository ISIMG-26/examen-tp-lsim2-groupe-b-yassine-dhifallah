<?php
// back/get_produits.php — Retourne les produits en JSON (utilisé par AJAX)
require_once 'connexion_db.php';

header('Content-Type: application/json');

$categorie = isset($_GET['categorie']) ? (int)$_GET['categorie'] : 0;
$recherche = trim($_GET['q'] ?? '');

$sql    = 'SELECT p.*, c.nom AS categorie_nom FROM produits p LEFT JOIN categories c ON p.categorie_id = c.id WHERE 1=1';
$params = [];

if ($categorie > 0) {
    $sql .= ' AND p.categorie_id = :cat';
    $params[':cat'] = $categorie;
}

if ($recherche !== '') {
    $sql .= ' AND (p.nom LIKE :q OR p.description LIKE :q2)';
    $params[':q']  = '%' . $recherche . '%';
    $params[':q2'] = '%' . $recherche . '%';
}

$sql .= ' ORDER BY p.date_ajout DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produits = $stmt->fetchAll();

echo json_encode(['succes' => true, 'produits' => $produits]);
