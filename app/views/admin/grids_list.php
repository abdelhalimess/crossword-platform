<?php
// Inclure les fichiers nécessaires pour les contrôleurs
session_start();
include('../../controllers/UserController.php');
include('../../controllers/GridController.php');

// Vérification si l'utilisateur est administrateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: views/auth/login.php'); // Redirige si l'utilisateur n'est pas admin
    exit();
}
$gridsController = new GridController();
$message = '';

// Vérifie si une suppression est demandée
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_grid') {
    if (isset($_POST['grid_id'])) {
        $gridId = intval($_POST['grid_id']);
        $message = $gridsController->deleteGrid($gridId);
    } else {
        $message = "ID de grille non fourni.";
    }
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : null;


$gridList = $gridsController->displayAllGridAdmin($page, $search);
$totalGrids = $gridsController->countTotalGrids($search);
// Calculer le nombre total de pages
$totalPages = ceil($totalGrids / 15);

if (isset($_SESSION['success'])) {
    echo "<div class='alert alert-success'>{$_SESSION['success']}</div>";
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger'>{$_SESSION['error']}</div>";
    unset($_SESSION['error']);
}

if (isset($_SESSION['warning'])) {
    echo "<div class='alert alert-warning'>{$_SESSION['warning']}</div>";
    unset($_SESSION['warning']);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Administration</title>
    <link rel="stylesheet" href="../../public/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Panneau Admin</h2>
        </div>
        <nav class="sidebar-menu">
        <ul>
            <li><a href="create_user.php">Créer des utilisateurs</a></li>
            <li><a href="users_list.php">Gérer les utilisateurs</a></li>
            <li><a href="grids_list.php">Gérer les grilles</a></li>
        </ul>
        <ul class="logout-section">
            <li class="logout"><a href="../../views/auth/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">

        <!-- Liste des grilles -->

        <section id="gridList">
            <h2>Liste des grilles</h2>

            <!-- Formulaire de recherche -->
            <form method="GET" action="grids_list.php">
                <input type="text" name="search" placeholder="Rechercher par nom de grille"
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit">Rechercher</button>
                <a href="grids_list.php" class="clear-button">Effacer</a>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom de la grille</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($gridList as $grid): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($grid['id']); ?></td>
                        <td><?php echo htmlspecialchars($grid['name']); ?></td>
                        <td>
                            <form method="POST" action="grids_list.php"
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette grille ?');">
                                <input type="hidden" name="action" value="delete_grid">
                                <input type="hidden" name="grid_id" value="<?= htmlspecialchars($grid['id']) ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div id="pagination">
                <?php if ($page > 1): ?>
                <a href="admin.php?page=1&search=<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    <<< /a>
                        <a
                            href="admin.php?page=<?php echo $page - 1; ?>&search=<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                            << /a>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="admin.php?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                                    <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
                                <?php endfor; ?>

                                <?php if ($page < $totalPages): ?>
                                <a
                                    href="admin.php?page=<?php echo $page + 1; ?>&search=<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">></a>
                                <a
                                    href="admin.php?page=<?php echo $totalPages; ?>&search=<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">>></a>
                                <?php endif; ?>
            </div>
        </section>

        </main>
</body>

</html>