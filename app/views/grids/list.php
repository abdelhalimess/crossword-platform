<?php
// ----------------------------------------------------------------
// SESSION ET CONTROLLERS
session_start();
require_once '../../controllers/SavedCellsController.php';
require_once '../../controllers/GridController.php';

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id']; // ID de l'utilisateur connecté
} else {
    echo "Utilisateur non connecté.";
    exit;
}

// INITIALISATION DES CONTROLLERS
$SavedCellsController = new SavedCellsController();
$gridsController = new GridController();

// Récupérer la liste des grilles de l'utilisateur
$gridList = $SavedCellsController->getUserGrids($userId);
$fullGridList = [];

foreach ($gridList as $grid) {
    $gridDetails = $gridsController->getById($grid['grid_id']);
    $fullGridList[] = $gridDetails;
}

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
usort($fullGridList, function($a, $b) use ($sortBy, $order, $difficultyOrder) {
    $difficultyComparison = $difficultyOrder[$a['difficulty']] - $difficultyOrder[$b['difficulty']];
    if ($difficultyComparison === 0) {
        $dateComparison = strtotime($a['created_at']) - strtotime($b['created_at']);
        return $order == 'ASC' ? $dateComparison : -$dateComparison;
    }
    return $order == 'ASC' ? $difficultyComparison : -$difficultyComparison;
});

// Découper la liste complète pour la pagination
$paginatedGridList = array_slice($fullGridList, $offset, $itemsPerPage);

// Calculer le nombre total de pages
$totalGrids = count($fullGridList);
$totalPages = ceil($totalGrids / $itemsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Grilles de Mots Croisés</title>
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
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'registered'): ?>
                <li><a href="../../index.php">Accueil</a></li>
                <li><a href="">Voir mes grilles</a></li>
                <li><a href="./create3.php">Créer une grille</a></li>
                <li class="logout"><a href="../../views/auth/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>     
                
                <?php else: ?>
                <li><a href="views/auth/login.php">Connexion</a></li>
                <li><a href="views/auth/register.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Formulaire de recherche -->
    <section>
        <form method="GET" action="" class="search-bar">
            <label for="sort_by">Trier par :</label>
            <select name="sort_by" id="sort_by">
                <option value="difficulty" <?= isset($_GET['sort_by']) && $_GET['sort_by'] == 'difficulty' ? 'selected' : ''; ?>>Difficulté</option>
                <option value="created_at" <?= isset($_GET['sort_by']) && $_GET['sort_by'] == 'created_at' ? 'selected' : ''; ?>>Date d'ajout</option>
            </select>

            <label for="order">Ordre :</label>
            <select name="order" id="order">
                <option value="ASC" <?= isset($_GET['order']) && $_GET['order'] == 'ASC' ? 'selected' : ''; ?>>Ascendant</option>
                <option value="DESC" <?= isset($_GET['order']) && $_GET['order'] == 'DESC' ? 'selected' : ''; ?>>Descendant</option>
            </select>

            <button type="submit">Trier</button>
        </form>
    </section>

    <!-- Affichage des grilles -->
    <section>
        <?php if ($paginatedGridList && count($paginatedGridList) > 0): ?>
            <div class="grid-container">
                <?php foreach ($paginatedGridList as $grid): ?>
                    <div class="grid-card">
                        <img src="/public/img/crossword-placeholder3.png" alt="Image de grille" class="grid-image">
                        <h3><?= htmlspecialchars($grid['name']); ?></h3>
                        <p>Difficulté: <?= ucfirst($grid['difficulty']); ?></p>
                        <p>Date d'ajout: <?= htmlspecialchars($grid['created_at']); ?></p>
                        <a href="/views/grids/play.php?id=<?= $grid['id']; ?>" class="btn">Reprendre</a>
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
        <a href="?page=<?= max($page - 1, 1); ?>&sort_by=<?= htmlspecialchars($sortBy); ?>&order=<?= htmlspecialchars($order); ?>" id="prev-link" class="<?= $page == 1 ? 'disabled' : ''; ?>">Précédent</a>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i; ?>&sort_by=<?= htmlspecialchars($sortBy); ?>&order=<?= htmlspecialchars($order); ?>" id="page-<?= $i; ?>" class="<?= $i == $page ? 'active' : ''; ?>"><?= $i; ?></a>
        <?php endfor; ?>
        <a href="?page=<?= min($page + 1, $totalPages); ?>&sort_by=<?= htmlspecialchars($sortBy); ?>&order=<?= htmlspecialchars($order); ?>" id="next-link" class="<?= $page == $totalPages ? 'disabled' : ''; ?>">Suivant</a>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Plateforme de Mots Croisés. Tous droits réservés.</p>
    </footer>
</body>
</html>
