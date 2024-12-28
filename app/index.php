<?php
session_start();
require_once 'controllers/UserController.php';
require_once 'models/Grid.php';

// Création du contrôleur d'utilisateur
$userController = new UserController();
$user = $userController->getAuthenticatedUser();  // Récupérer l'utilisateur connecté

$grids = new Grid();
$gridList = $grids->getAllPublicGrids();  // Récupérer toutes les grilles publiques
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme de Mots Croisés</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

    <!-- En-tête -->
    <header>
        <h1>Bienvenue sur la plateforme de Mots Croisés</h1>
        <p>Explorez une large sélection de grilles de mots croisés, résolvez-les en ligne et créez vos propres défis !</p>
    </header>

    <!-- Navbar -->
    <nav>
        <ul>
            <?php if ($user): ?>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="views/grids/list.php">Voir mes grilles</a></li>
                <?php if ($user['role'] == 'admin'): ?>
                    <li><a href="admin.php">Administration</a></li>
                <?php endif; ?>
                <li><a href="views/auth/logout.php">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="views/auth/login.php">Connexion</a></li>
                <li><a href="views/auth/register.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Section des grilles -->
    <section>
        <h2>Grilles disponibles</h2>
        <?php if ($grids && count($gridList) > 0): ?>
            <ul>
                <?php foreach ($gridList as $grid): ?>
                    <li>
                        <a href="view_grid.php?id=<?= $grid['id']; ?>"><?= htmlspecialchars($grid['name']); ?></a>
                        <p>Difficulté: <?= ucfirst($grid['difficulty']); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucune grille disponible pour le moment.</p>
        <?php endif; ?>
    </section>

    <!-- Section Connexion/Inscription (si non connecté) -->
    <?php if (!$user): ?>
        <section>
            <h2>Commencez à résoudre des grilles</h2>
            <p>Vous devez être connecté pour résoudre des grilles et en créer de nouvelles.</p>
            <p>Si vous n'avez pas encore de compte, <a href="views/auth/register.php">inscrivez-vous ici</a>.</p>
            <p>Si vous avez déjà un compte, <a href="views/auth/login.php">connectez-vous ici</a>.</p>
        </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Plateforme de Mots Croisés. Tous droits réservés.</p>
    </footer>

</body>
</html>
