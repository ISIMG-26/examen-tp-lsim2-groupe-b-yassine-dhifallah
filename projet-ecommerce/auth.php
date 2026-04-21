<?php
// auth.php — Page de connexion et d'inscription
session_start();

// Si déjà connecté, rediriger
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte — ModeTrend</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@400;600&display=swap" rel="stylesheet">
</head>
<body data-page="auth">

<header>
    <div class="header-inner">
        <a href="index.php" class="logo">Mode<span>Trend</span></a>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="produits.php">Produits</a></li>
                <li><a href="auth.php" class="active btn-panier">🛒 Panier <span id="compteur-panier">0</span></a></li>
                <li><a href="auth.php" class="active">Connexion</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="auth-wrapper">
        <h1 class="section-titre">Mon Compte</h1>

        <!-- Onglets -->
        <div class="onglets-auth">
            <button class="onglet-btn actif" data-cible="form-connexion">Connexion</button>
            <button class="onglet-btn" data-cible="form-inscription">Créer un compte</button>
        </div>

        <!-- ========== FORMULAIRE CONNEXION ========== -->
        <div id="form-connexion" class="form-auth actif">
            <div id="alerte-connexion" class="alerte"></div>

            <form id="form-connexion" novalidate>
                <div class="groupe-champ">
                    <label for="email-connexion">Adresse e-mail</label>
                    <input type="email" id="email-connexion" name="email"
                           placeholder="exemple@email.com" autocomplete="email">
                    <span class="msg-erreur" id="err-email-connexion"></span>
                </div>

                <div class="groupe-champ">
                    <label for="mdp-connexion">Mot de passe</label>
                    <input type="password" id="mdp-connexion" name="mot_de_passe"
                           placeholder="Votre mot de passe" autocomplete="current-password">
                    <span class="msg-erreur" id="err-mdp-connexion"></span>
                </div>

                <button type="submit" class="btn btn-primaire" style="width:100%;justify-content:center;">
                    Se connecter
                </button>
            </form>

            <p style="text-align:center;margin-top:1.2rem;font-size:0.88rem;color:#888;">
                Pas encore de compte ?
                <a href="#" onclick="document.querySelector('[data-cible=form-inscription]').click();return false;"
                   style="color:var(--couleur-accent);font-weight:600;">Créer un compte</a>
            </p>
        </div>

        <!-- ========== FORMULAIRE INSCRIPTION ========== -->
        <div id="form-inscription" class="form-auth">
            <div id="alerte-inscription" class="alerte"></div>

            <form id="form-inscription" novalidate>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="groupe-champ">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" placeholder="Dupont">
                        <span class="msg-erreur" id="err-nom"></span>
                    </div>
                    <div class="groupe-champ">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" placeholder="Alice">
                        <span class="msg-erreur" id="err-prenom"></span>
                    </div>
                </div>

                <div class="groupe-champ">
                    <label for="email-inscription">Adresse e-mail</label>
                    <input type="email" id="email-inscription" name="email"
                           placeholder="exemple@email.com" autocomplete="email">
                    <span class="msg-erreur" id="err-email-inscription"></span>
                </div>

                <div class="groupe-champ">
                    <label for="mdp-inscription">Mot de passe</label>
                    <input type="password" id="mdp-inscription" name="mot_de_passe"
                           placeholder="Minimum 6 caractères" autocomplete="new-password">
                    <span class="msg-erreur" id="err-mdp-inscription"></span>
                </div>

                <div class="groupe-champ">
                    <label for="mdp-confirm">Confirmer le mot de passe</label>
                    <input type="password" id="mdp-confirm" name="mot_de_passe_confirm"
                           placeholder="Répétez le mot de passe" autocomplete="new-password">
                    <span class="msg-erreur" id="err-mdp-confirm"></span>
                </div>

                <button type="submit" class="btn btn-primaire" style="width:100%;justify-content:center;">
                    Créer mon compte
                </button>
            </form>
        </div>
    </div>
</main>

<footer>
    <p>&copy; <?= date('Y') ?> ModeTrend — Tous droits réservés</p>
</footer>

<div id="toast"></div>
<script src="js/main.js"></script>
</body>
</html>
