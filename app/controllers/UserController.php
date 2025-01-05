<?php
require_once __DIR__ . '/../models/User.php';

class UserController
{
    // Fonction pour enregistrer un nouvel utilisateur
    public function register($username, $password, $email, $role)
    {
        $user = new User();
        $user->create($username, $password, $email, $role);
        if ($user) {
            return true;
        }
    }

    // Fonction pour se connecter
    public function login($username, $password)
    {
        $user = new User();
        $userData = $user->getByUsername($username);
    
        if ($userData) {
            // Vérifier le mot de passe
            if (password_verify($password, $userData['password_hash'])) {
                $_SESSION['username'] = $userData['username'];
                $_SESSION['user_id'] = $userData['id'];
                $_SESSION['role'] = $userData['role']; // Assurez-vous que le rôle est bien attribué
                return true; // Connexion réussie
            } else {
                $_SESSION['error'] = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        } else {
            $_SESSION['error'] = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
    

    // Fonction pour se déconnecter
    public function logout()
    {
        // Supprimer les variables de session
        session_unset();
        session_destroy();
    }

    // Fonction pour récupérer l'utilisateur authentifié
    public function getAuthenticatedUser()
    {
        if (isset($_SESSION['user_id'])) {
            $user = new User();
            return $user->getById($_SESSION['user_id']); // Récupérer l'utilisateur à partir de l'ID de session
        }

        return null; // Aucun utilisateur authentifié
    }

    // Fonction pour récupérer un utilisateur par son nom d'utilisateur
    public function getUserByUsername($username)
    {
        $user = new User();
        return $user->getByUsername($username);
    }

    // Fonction pour récupérer un utilisateur par son ID
    public function getUserById($userId)
    {
        $user = new User();
        return $user->getById($userId);
    }

// ----------------------------------------------------------------



    public function deleteUser($userId) {
        $user = new User();
        if (!$userId) {
            return "ID utilisateur non valide.";
        }

        // Appelez la méthode du modèle User pour supprimer
        $result = $user->deleteUser($userId);

        // Gérer le retour et les messages
        if ($result) {
            return "Utilisateur supprimé avec succès.";
        } else {
            return "Échec de la suppression de l'utilisateur. Vérifiez si l'utilisateur existe.";
        }
    }

public function displayAllUsers($page, $searchUsername)
{
    $userModel = new User();
    return $userModel->displayAllUsers($page, $searchUsername);
}

// ----------------------------------------------------------------

}
