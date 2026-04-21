<?php
// back/connexion_db.php
// Connexion à la base de données MySQL

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Modifier selon votre config XAMPP/WAMP
define('DB_PASS', '');           // Modifier selon votre config
define('DB_NAME', 'boutique_mode');

$pdo = null;

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(['erreur' => 'Connexion base de données échouée : ' . $e->getMessage()]));
}
