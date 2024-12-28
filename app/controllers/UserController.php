<?php
require_once __DIR__ . '/../models/User.php';

class UserController
{
    // Fonction pour enregistrer un nouvel utilisateur
    public function register($username, $password, $email, $role)
    {
        $user = new User();

        // Vérification de l'existence de l'utilisateur
        if ($user->create($username, $password, $email, $role)) {
            return true;
        } else {
            echo "Erreur lors de la création de l'utilisateur.";
        }
    }

    // Fonction pour se connecter
    public function login($username, $password)
    {
        $user = new User();
        $userData = $user->getByUsername($username);

        if ($userData && password_verify($password, $userData['password_hash'])) {
            // L'utilisateur est authentifié, commencer la session
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['username'] = $userData['username'];
            $_SESSION['role'] = $userData['role'];

            return true; // Connexion réussie
        }

        return false; // Echec de la connexion
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
}
