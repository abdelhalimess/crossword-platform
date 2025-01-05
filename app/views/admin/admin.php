<?php
// Inclure les fichiers nécessaires pour les contrôleurs
session_start();
include('../../controllers/UserController.php');
include('../../controllers/GridController.php');

// Vérification si l'utilisateur est administrateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php'); // Redirige si l'utilisateur n'est pas admin
    exit();
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
        <header>
            <h1>Bienvenue dans le panneau d'administration</h1>
            <p>Gérez les utilisateurs et les grilles</p>
        </header>

    </main>
</body>

</html>