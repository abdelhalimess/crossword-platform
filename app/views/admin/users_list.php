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

// Suppression d'un utilisateur
$userController = new UserController();
$message = '';

// Vérifie si une suppression est demandée
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_user') {
    if (isset($_POST['user_id'])) {
        $userId = intval($_POST['user_id']);
        $message = $userController->deleteUser($userId);
    } else {
        $message = "ID de l'utilisateur non fourni.";
    }
}

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : null;
$searchUsername = isset($_GET['searchUsername']) ? $_GET['searchUsername'] : null;


// Récupérer la liste des utilisateurs et des grilles
$userController = new UserController();
$users = $userController->displayAllUsers($page, $searchUsername);

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
                <li class="logout"><a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>

            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">

        <!-- Liste des utilisateurs -->
        <section id="userList">
            <h2>Liste des utilisateurs</h2>
            <form method="GET" action="users_list.php">
                <input type="text" name="searchUsername" placeholder="Rechercher par le nom d'utilisateur"
                    value="<?php echo isset($_GET['searchUsername']) ? htmlspecialchars($_GET['searchUsername']) : ''; ?>">
                <button type="submit">Rechercher</button>
                <a href="users_list.php" class="clear-button">Effacer</a>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <form method="POST" action="users_list.php"
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                    <input type="hidden" name="action" value="delete_user">
                                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
                                    <button type="submit">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

    </main>
</body>

</html>