<?php
// ----------------------------------------------------------------

session_start();
require_once '../../controllers/UserController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController = new UserController();
    $registrationResult = $userController->register($_POST['username'], $_POST['password'], $_POST['email'], 'registered');

    if ($registrationResult) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Une erreur est survenue. Veuillez réessayer.";
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
    <title>Inscription</title>
    <link rel="stylesheet" href="../../public/css/auth.css">
</head>

<body>

    <header>
        <h1>Inscription</h1>
    </header>

    <section>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <br>
            <br>
            <button type="submit">S'inscrire</button>
        </form>
        <p>Déjà un compte ? <a href="login.php">Connectez-vous ici</a>.</p>
    </section>

</body>

</html>