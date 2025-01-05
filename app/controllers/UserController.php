<?php
require_once __DIR__ . '/../models/User.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }
    // Fonction pour enregistrer un nouvel utilisateur
    public function register($username, $password, $email, $role)
    {
        $this->userModel->create($username, $password, $email, $role);
        if ($this->userModel) {
            return true;
        }
    }

    // Fonction pour se connecter
    public function login($username, $password)
    {
        $userData = $this->userModel->getByUsername($username);

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
        header("Location: ../../index.php");
    }

    // Fonction pour récupérer l'utilisateur authentifié
    public function getAuthenticatedUser()
    {
        if (isset($_SESSION['user_id'])) {
            return $this->userModel->getById($_SESSION['user_id']); // Récupérer l'utilisateur à partir de l'ID de session
        }

        return null; // Aucun utilisateur authentifié
    }

    // Fonction pour récupérer un utilisateur par son nom d'utilisateur
    public function getUserByUsername($username)
    {
        return $this->userModel->getByUsername($username);
    }

    // Fonction pour récupérer un utilisateur par son ID
    public function getUserById($userId)
    {
        return $this->userModel->getById($userId);
    }



 // Fonction pour supprimer un utilisateur
    public function deleteUser($userId)
    {
        if (!$userId) {
            return "ID utilisateur non valide.";
        }

        // Appelez la méthode du modèle User pour supprimer
        $result = $this->userModel->deleteUser($userId);

        // Gérer le retour et les messages
        if ($result) {
            return "Utilisateur supprimé avec succès.";
        } else {
            return "Échec de la suppression de l'utilisateur. Vérifiez si l'utilisateur existe.";
        }
    }


 // Fonction pour afficher les utilisateurs

    public function displayAllUsers($page, $searchUsername)
    {
        return $this->userModel->displayAllUsers($page, $searchUsername);
    }


}
