<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['host'];
    $dbname = $_POST['dbname'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Écrire la configuration dans config.php
    $configContent = "<?php\n";
    $configContent .= "return [\n";
    $configContent .= "    'db' => [\n";
    $configContent .= "        'host' => '$host',\n";
    $configContent .= "        'dbname' => '$dbname',\n";
    $configContent .= "        'username' => '$username',\n";
    $configContent .= "        'password' => '$password',\n";
    $configContent .= "    ]\n";
    $configContent .= "];\n";

    file_put_contents('config.php', $configContent);

    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CruciWeb</title>
    <link rel="icon" type="image/png" href="public/img/crossword-placeholder3.png">
    <link rel="stylesheet" href="public/css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

<form method="POST">
    <label  for="host">Hôte de la base de données:</label><br>
    <input placeholder="localhost" type="text" id="host" name="host" required><br>

    <label  for="dbname">Nom de la base de données:</label><br>
    <input  placeholder="projet" type="text" id="dbname" name="dbname" required><br>

    <label  for="username">Nom d'utilisateur:</label><br>
    <input placeholder="projet" type="text" id="username" name="username" required><br>

    <label  for="password">Mot de passe:</label><br>
    <input placeholder="tejorp"type="password" id="password" name="password" required><br><br>

    <button type="submit">Sauvegarder la configuration</button>
</form>
</body>