<?php
session_start();
require_once '../../controllers/UserController.php';
// ----------------------------------------------------------------



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController = new UserController();
    $loginResult = $userController->login($_POST['username'], $_POST['password']);
    if ($loginResult) {
        if ($_SESSION['role'] === 'admin') {
            header("Location: ../admin/admin.php");
        } else {
            header("Location: ../../index.php");
        }
        exit();

    }
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
    <link rel="stylesheet" href="../../public/css/auth.css">
    <title>Connexion</title>
</head>

<body>

    <header>
        <h1>Connexion</h1>
    </header>

    <section>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit">Se connecter</button>
        </form>
        <p>Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a>.</p>
    </section>

</body>

</html>