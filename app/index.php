<?php
// ----------------------------------------------------------------

session_start();
require_once 'controllers/UserController.php';
require_once 'controllers/GridController.php';


if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header('Location: views/auth/login.php'); // Redirige si l'utilisateur n'est pas inscrit
    exit();
}



// Création du contrôleur d'utilisateur
$userController = new UserController();
$user = $userController->getAuthenticatedUser();  // Récupérer l'utilisateur connecté

$gridsController = new GridController();


$gridList = $gridsController->displayAllGridUser();

// Obtenir le nombre total de grilles pour la pagination
$totalGrids = $gridsController->getTotalGridsCount();

// Calculer le nombre total de pages
$totalPages = ceil($totalGrids / 15);

// Récupérer les paramètres de tri et de pagination depuis le formulaire GET
$sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'created_at';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 15;
$offset = ($page - 1) * $itemsPerPage;

// Ordre des niveaux de difficulté
$difficultyOrder = [
    'expert' => 3,
    'intermediaire' => 2,
    'debutant' => 1
];

// Fonction de tri
usort($gridList, function($a, $b) use ($sortBy, $order, $difficultyOrder) {
    $difficultyComparison = $difficultyOrder[$a['difficulty']] - $difficultyOrder[$b['difficulty']];
    if ($difficultyComparison === 0) {
        $dateComparison = strtotime($a['created_at']) - strtotime($b['created_at']);
        return $order == 'ASC' ? $dateComparison : -$dateComparison;
    }
    return $order == 'ASC' ? $difficultyComparison : -$difficultyComparison;
});

// Découper la liste complète pour la pagination
$paginatedGridList = array_slice($gridList, $offset, $itemsPerPage);

// Calculer le nombre total de pages
$totalGrids = count($gridList);
$totalPages = ceil($totalGrids / $itemsPerPage);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CruciWeb</title>
    <link rel="icon" type="image/png" href="public/img/crossword-placeholder3.png">
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <!-- En-tête -->
    <header>
        <h1>Bienvenue sur la plateforme de Mots Croisés</h1>
        <p>Explorez une large sélection de grilles de mots croisés, résolvez-les en ligne et créez vos propres défis !
        </p>
    </header>

    <!-- Navbar -->
    <nav>
    <ul>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'registered'): ?>
            <li><a href="">Accueil</a></li>
            <li><a href="views/grids/list.php">Voir mes grilles</a></li>
            <li><a href="views/grids/create3.php">Créer une grille</a></li>
            <li class="logout"><a  href="views/auth/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        <?php else: ?>
            <li><a href="views/auth/login.php">Connexion</a></li>
            <li><a href="views/auth/register.php">Inscription</a></li>
        <?php endif; ?>
    </ul>
</nav>

    <!-- Section des grilles -->


    <!-- Formulaire de recherche -->
    <section>
        <form method="GET" action="" class="search-bar">
            <label for="sort_by">Trier par :</label>
            <select name="sort_by" id="sort_by">
                <option value="difficulty"
                    <?= isset($_GET['sort_by']) && $_GET['sort_by'] == 'difficulty' ? 'selected' : ''; ?>>Difficulté
                </option>
                <option value="created_at"
                    <?= isset($_GET['sort_by']) && $_GET['sort_by'] == 'created_at' ? 'selected' : ''; ?>>Date d'ajout
                </option>
            </select>

            <label for="order">Ordre :</label>
            <select name="order" id="order">
                <option value="ASC" <?= isset($_GET['order']) && $_GET['order'] == 'ASC' ? 'selected' : ''; ?>>Ascendant
                </option>
                <option value="DESC" <?= isset($_GET['order']) && $_GET['order'] == 'DESC' ? 'selected' : ''; ?>>
                    Descendant</option>
            </select>

            <button type="submit">Trier</button>
        </form>
        </section>
    <section>
        <!-- Affichage des grilles -->
        <?php if ($gridList && count($gridList) > 0): ?>
        <div class="grid-container">
            <?php foreach ($gridList as $grid): ?>
            <div class="grid-card">
                <img src="/public/img/crossword-placeholder3.png" alt="Image de grille" class="grid-image">
                <h3><?= htmlspecialchars($grid['name']); ?></h3>
                <p>Difficulté: <?= ucfirst($grid['difficulty']); ?></p>
                <p>Date d'ajout: <?= htmlspecialchars($grid['created_at']); ?></p>
                <a href="/views/grids/play.php?id=<?= $grid['id']; ?>" class="btn">Jouer</a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <div class="no_grids">

                <p >Aucune grille disponible pour le moment.</p>

            </div>
        <?php endif; ?>

    </section>
    <!-- Pagination -->
    <div id="pagination">
    <!-- Lien Précédent -->
    <a href="?page=<?= max($page - 1, 1); ?>&sort_by=<?= htmlspecialchars($sort_by); ?>&order=<?= htmlspecialchars($order); ?>" id="prev-link" class="<?= $page == 1 ? 'disabled' : ''; ?>">Précédent</a>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i; ?>&sort_by=<?= htmlspecialchars($sort_by); ?>&order=<?= htmlspecialchars($order); ?>" id="page-<?= $i; ?>" class="<?= $i == $page ? 'active' : ''; ?>">
            <?= $i; ?>
        </a>
    <?php endfor; ?>

    <!-- Lien Suivant -->
    <a href="?page=<?= min($page + 1, $totalPages); ?>&sort_by=<?= htmlspecialchars($sort_by); ?>&order=<?= htmlspecialchars($order); ?>" id="next-link" class="<?= $page == $totalPages ? 'disabled' : ''; ?>">Suivant</a>
</div>
    <!-- Section Connexion/Inscription (si non connecté) -->
    <?php if (!$user): ?>
    <section style="text-align: center">
        <h2>Commencez à résoudre des grilles</h2>
        <p>Vous devez être connecté pour sauvegarder des grilles et en créer de nouvelles.</p>
        <p>Si vous n'avez pas encore de compte, <a href="views/auth/register.php">inscrivez-vous </a>.</p>
        <p>Si vous avez déjà un compte, <a href="views/auth/login.php">connectez-vous </a>.</p>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Plateforme de Mots Croisés. Tous droits réservés.</p>
    </footer>

</body>

</html>