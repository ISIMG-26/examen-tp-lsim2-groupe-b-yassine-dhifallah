<?php
// back/panier_action.php — Gestion du panier (ajout, suppression, mise à jour)
session_start();
require_once 'connexion_db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['succes' => false, 'message' => 'Vous devez être connecté.']);
    exit;
}

$action     = $_POST['action'] ?? $_GET['action'] ?? '';
$produit_id = isset($_POST['produit_id']) ? (int)$_POST['produit_id'] : 0;
$user_id    = (int)$_SESSION['user_id'];

switch ($action) {

    case 'ajouter':
        if ($produit_id <= 0) {
            echo json_encode(['succes' => false, 'message' => 'Produit invalide.']);
            exit;
        }
        // Vérifier si déjà dans le panier
        $stmt = $pdo->prepare('SELECT id, quantite FROM panier WHERE utilisateur_id = :u AND produit_id = :p');
        $stmt->execute([':u' => $user_id, ':p' => $produit_id]);
        $ligne = $stmt->fetch();

        if ($ligne) {
            // Incrémenter la quantité
            $stmt = $pdo->prepare('UPDATE panier SET quantite = quantite + 1 WHERE id = :id');
            $stmt->execute([':id' => $ligne['id']]);
        } else {
            // Nouvelle entrée
            $stmt = $pdo->prepare('INSERT INTO panier (utilisateur_id, produit_id, quantite) VALUES (:u, :p, 1)');
            $stmt->execute([':u' => $user_id, ':p' => $produit_id]);
        }
        echo json_encode(['succes' => true, 'message' => 'Produit ajouté au panier.']);
        break;

    case 'supprimer':
        $panier_id = isset($_POST['panier_id']) ? (int)$_POST['panier_id'] : 0;
        $stmt = $pdo->prepare('DELETE FROM panier WHERE id = :id AND utilisateur_id = :u');
        $stmt->execute([':id' => $panier_id, ':u' => $user_id]);
        echo json_encode(['succes' => true, 'message' => 'Article supprimé.']);
        break;

    case 'vider':
        $stmt = $pdo->prepare('DELETE FROM panier WHERE utilisateur_id = :u');
        $stmt->execute([':u' => $user_id]);
        echo json_encode(['succes' => true, 'message' => 'Panier vidé.']);
        break;

    case 'lister':
        $stmt = $pdo->prepare(
            'SELECT pan.id AS panier_id, pan.quantite, prod.id AS produit_id,
                    prod.nom, prod.prix, prod.image
             FROM panier pan
             JOIN produits prod ON pan.produit_id = prod.id
             WHERE pan.utilisateur_id = :u'
        );
        $stmt->execute([':u' => $user_id]);
        $items = $stmt->fetchAll();
        $total = array_sum(array_map(fn($i) => $i['prix'] * $i['quantite'], $items));
        echo json_encode(['succes' => true, 'items' => $items, 'total' => number_format($total, 2)]);
        break;

    default:
        echo json_encode(['succes' => false, 'message' => 'Action inconnue.']);
}
