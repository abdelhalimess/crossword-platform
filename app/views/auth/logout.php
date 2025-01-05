<?php
session_start();
require_once '../../controllers/UserController.php';


$userController = new UserController();
$user = $userController->logout();


// Rediriger l'utilisateur vers la page de connexion ou l'accueil

exit();
?>