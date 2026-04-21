<?php
// back/register.php
session_start();
require_once 'connexion_db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['succes' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

$nom        = trim($_POST['nom'] ?? '');
$prenom     = trim($_POST['prenom'] ?? '');
$email      = trim($_POST['email'] ?? '');
$mdp        = $_POST['mot_de_passe'] ?? '';
$mdp_conf   = $_POST['mot_de_passe_confirm'] ?? '';

// Validations
if (empty($nom) || empty($prenom) || empty($email) || empty($mdp) || empty($mdp_conf)) {
    echo json_encode(['succes' => false, 'message' => 'Tous les champs sont obligatoires.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['succes' => false, 'message' => 'Format email invalide.']);
    exit;
}

if (strlen($mdp) < 6) {
    echo json_encode(['succes' => false, 'message' => 'Le mot de passe doit contenir au moins 6 caractères.']);
    exit;
}

if ($mdp !== $mdp_conf) {
    echo json_encode(['succes' => false, 'message' => 'Les mots de passe ne correspondent pas.']);
    exit;
}

// Vérifier si l'email existe déjà
$stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE email = :email');
$stmt->execute([':email' => $email]);
if ($stmt->fetch()) {
    echo json_encode(['succes' => false, 'message' => 'Cet email est déjà utilisé.']);
    exit;
}

// Insertion
$hash = password_hash($mdp, PASSWORD_BCRYPT);
$stmt = $pdo->prepare('INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES (:nom, :prenom, :email, :mdp)');
$stmt->execute([
    ':nom'    => $nom,
    ':prenom' => $prenom,
    ':email'  => $email,
    ':mdp'    => $hash,
]);

$newId = $pdo->lastInsertId();
$_SESSION['user_id']     = $newId;
$_SESSION['user_nom']    = $nom;
$_SESSION['user_prenom'] = $prenom;
$_SESSION['user_email']  = $email;

echo json_encode(['succes' => true, 'message' => 'Compte créé avec succès !', 'redirect' => '../index.php']);
