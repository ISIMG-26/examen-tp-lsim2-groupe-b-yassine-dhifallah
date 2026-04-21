-- ============================================
-- Base de données : boutique_mode
-- ============================================

CREATE DATABASE IF NOT EXISTS boutique_mode CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE boutique_mode;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des catégories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

-- Table des produits (liée à catégories)
CREATE TABLE IF NOT EXISTS produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255) DEFAULT 'placeholder.jpg',
    categorie_id INT,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Table du panier (liée à utilisateurs et produits)
CREATE TABLE IF NOT EXISTS panier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    produit_id INT NOT NULL,
    quantite INT DEFAULT 1,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE CASCADE
);

-- ============================================
-- Données de test
-- ============================================

INSERT INTO categories (nom) VALUES
('Homme'),
('Femme'),
('Accessoires');

INSERT INTO produits (nom, description, prix, stock, image, categorie_id) VALUES
('T-shirt Classique', 'T-shirt en coton 100% bio, coupe droite confortable.', 29.99, 50, 'tshirt.jpg', 1),
('Jean Slim', 'Jean slim moderne, tissu stretch de qualité.', 59.99, 30, 'jean.jpg', 1),
('Robe Élégante', 'Robe mi-longue parfaite pour toutes les occasions.', 79.99, 20, 'robe.jpg', 2),
('Veste en Lin', 'Veste légère en lin, idéale pour l\'été.', 89.99, 15, 'veste.jpg', 2),
('Sac en Cuir', 'Sac à main en cuir véritable, plusieurs coloris.', 119.99, 10, 'sac.jpg', 3),
('Ceinture Tressée', 'Ceinture artisanale tressée, réglable.', 24.99, 40, 'ceinture.jpg', 3),
('Pull Oversize', 'Pull doux et chaud, coupe oversize tendance.', 49.99, 25, 'pull.jpg', 1),
('Jupe Plissée', 'Jupe plissée fluide, tombé parfait.', 44.99, 18, 'jupe.jpg', 2);

-- Utilisateur de test (mot de passe : Test1234)
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES
('Dupont', 'Alice', 'alice@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
