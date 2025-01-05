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

// Création d'un utilisateur
if (isset($_POST['create_user'])) {
    $userController = new UserController();
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Pas de hachage ici car le contrôleur le gère
    $userController->register($_POST['username'], $_POST['password'], $_POST['email'], 'registered');
}

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
    <link rel="icon" type="image/png" href="../../public/img/crossword-placeholder3.png">

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

        <!-- Formulaire pour créer un utilisateur -->
        <section id="createUserForm">
            <h2>Créer un utilisateur</h2>
            <form action="create_user.php" method="POST">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" name="username" required>

                <label for="email">Email :</label>
                <input type="email" name="email" required>

                <label for="password">Mot de passe :</label>
                <input type="password" name="password" required>

                <button type="submit" name="create_user">Créer un utilisateur</button>
            </form>
        </section>

    </main>
</body>

</html>