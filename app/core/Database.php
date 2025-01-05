<?php

class Database {
    private static $host;
    private static $dbname;
    private static $username;
    private static $password;

    // Charger la configuration à partir du fichier
    private static function loadConfig() {
        $config = include __DIR__ . '/../config.php';
        self::$host = $config['db']['host'];
        self::$dbname = $config['db']['dbname'];
        self::$username = $config['db']['username'];
        self::$password = $config['db']['password'];
    }

    public static function connect() {
        // Charger la configuration si ce n'est pas déjà fait
        if (self::$host === null) {
            self::loadConfig();
        }

        try {
            $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$dbname . ';charset=utf8mb4';
            return new PDO($dsn, self::$username, self::$password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die("Veuillez vérifier la configuration. Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
}
