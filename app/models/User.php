<?php
// ----------------------------------------------------------------
// 	** 	** 	** 	** 	** 	**
require_once __DIR__ . '/../core/Database.php';

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getByUsername($username)
    {
        $query = 'SELECT * FROM users WHERE username = :username LIMIT 1';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = 'SELECT * FROM users WHERE id = :id LIMIT 1';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($username, $password, $email, $role)
    {
        // Vérification si le nom d'utilisateur existe déjà
        $query = 'SELECT COUNT(*) FROM users WHERE username = :username';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $usernameExists = $stmt->fetchColumn();

        // Vérification si l'email existe déjà
        $query = 'SELECT COUNT(*) FROM users WHERE email = :email';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $emailExists = $stmt->fetchColumn();

        // Si l'un des deux existe déjà, renvoyer un message d'erreur
        if ($usernameExists) {
            $_SESSION['error'] = "Le nom d'utilisateur existe déjà.";
            return;
        }

        if ($emailExists) {
            $_SESSION['error'] = "L'email est déjà utilisé.";
            return;
        }

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

        // Exécution de la requête d'insertion
        if ($stmt->execute()) {
            $_SESSION['success'] = "Utilisateur créé avec succès.";
        } else {
            $_SESSION['error'] = "Échec de la création de l'utilisateur.";
        }
    }

    // ----------------------------------------------------------------



    public function deleteUser($userId)
    {
        if ($userId == 1) {
            $_SESSION['warning'] = "Impossible de supprimer l'utilisateur par défaut.";
            header("Location: users_list.php");
            exit();
        }

        try {
            // Supprimer toutes les grilles associées à cet utilisateur
            $query = "DELETE FROM grids WHERE user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            // Maintenant, supprimer l'utilisateur
            $query = "DELETE FROM users WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['success'] = "Utilisateur et ses grilles supprimés avec succès.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur et de ses grilles : " . $e->getMessage();
        }

        header("Location: users_list.php"); // Rediriger après la suppression
        exit();
    }

    // ----------------------------------------------------------------


    public function displayAllUsers($page = 1, $search = null)
    {
        $limit = 15;
        // Calcul de l'offset pour la pagination
        $offset = ($page - 1) * $limit;

        // Construire la requête
        $query = "SELECT * FROM users";

        if ($search) {
            $query .= " WHERE username LIKE :search OR email LIKE :search";
        }

        $query .= " LIMIT :limit OFFSET :offset";

        // Préparer et exécuter la requête
        $stmt = $this->db->prepare($query);

        if ($search) {
            $searchTerm = '%' . $search . '%';
            $stmt->bindParam(':search', $searchTerm);
        }

        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        // Retourner les résultats
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



}
