<?php
// back/check_email.php — Vérifie si un email existe déjà (appelé par AJAX)
require_once 'connexion_db.php';

header('Content-Type: application/json');

$email = trim($_GET['email'] ?? '');

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['existe' => false]);
    exit;
}

$stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE email = :email');
$stmt->execute([':email' => $email]);
$user = $stmt->fetch();

echo json_encode(['existe' => (bool)$user]);
