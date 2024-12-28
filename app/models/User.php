<?php

require_once __DIR__ . '/../core/Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getByUsername($username) {
        $query = 'SELECT * FROM users WHERE username = :username LIMIT 1';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = 'SELECT * FROM users WHERE id = :id LIMIT 1';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($username, $password, $email, $role = 'registered')
    {
        // Hachage du mot de passe
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Requête SQL pour insérer un nouvel utilisateur
        $query = 'INSERT INTO users (username, password_hash, email, role) 
                  VALUES (:username, :password_hash, :email, :role)';
        $stmt = $this->db->prepare($query);

        // Bind des paramètres
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password_hash', $passwordHash);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);

        // Exécution de la requête
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    
}
