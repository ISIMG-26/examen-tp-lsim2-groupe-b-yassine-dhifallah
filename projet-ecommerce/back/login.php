<?php
session_start();
require_once 'connexion_db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['succes' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$mdp   = $_POST['mot_de_passe'] ?? '';

if (empty($email) || empty($mdp)) {
    echo json_encode(['succes' => false, 'message' => 'Email et mot de passe sont requis.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['succes' => false, 'message' => 'Format email invalide.']);
    exit;
}

$stmt = $pdo->prepare('SELECT id, nom, prenom, email, mot_de_passe FROM utilisateurs WHERE email = :email');
$stmt->execute([':email' => $email]);
$user = $stmt->fetch();

if (!$user || !password_verify($mdp, $user['mot_de_passe'])) {
    echo json_encode(['succes' => false, 'message' => 'Email ou mot de passe incorrect.']);
    exit;
}

$_SESSION['user_id']     = $user['id'];
$_SESSION['user_nom']    = $user['nom'];
$_SESSION['user_prenom'] = $user['prenom'];
$_SESSION['user_email']  = $user['email'];

echo json_encode(['succes' => true, 'message' => 'Connexion réussie.', 'redirect' => '../index.php']);