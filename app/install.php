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

    // Exécuter init.sql
    try {
        // Charger le script SQL
        $sqlFile = 'init.sql';
        if (!file_exists($sqlFile)) {
            throw new Exception("Le fichier $sqlFile est introuvable.");
        }
        
        $sqlContent = file_get_contents($sqlFile);

        // Connexion à la base de données
        $dsn = "mysql:host=$host;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        $pdo->exec("USE `$dbname`;");

        // Exécuter les commandes SQL de init.sql
        $pdo->exec($sqlContent);

        // Ajouter l'utilisateur admin par défaut
        $passwordHash = password_hash('adminpassword', PASSWORD_DEFAULT); // Remplacez par le mot de passe de votre choix
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, email, role) VALUES (?, ?, ?, ?)");
        $stmt->execute(['admin_user', $passwordHash, 'admin@example.com', 'admin']);

        echo "<p>Base de données initialisée avec succès et utilisateur admin créé.</p>";
    } catch (PDOException $e) {
        echo "<p>Erreur lors de l'initialisation de la base de données : " . $e->getMessage() . "</p>";
    } catch (Exception $e) {
        echo "<p>" . $e->getMessage() . "</p>";
    }

    // Redirection vers la page principale
    header('Location: index.php');
    exit;
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
    <label for="host">Hôte de la base de données:</label><br>
    <input placeholder="localhost" type="text" id="host" name="host" required><br>

    <label for="dbname">Nom de la base de données:</label><br>
    <input placeholder="projet" type="text" id="dbname" name="dbname" required><br>

    <label for="username">Nom d'utilisateur:</label><br>
    <input placeholder="projet" type="text" id="username" name="username" required><br>

    <label for="password">Mot de passe:</label><br>
    <input placeholder="tejorp" type="password" id="password" name="password" required><br><br>

    <button type="submit">Sauvegarder la configuration</button>
</form>
</body>
</html>
