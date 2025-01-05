<?php
require_once __DIR__ . '/../models/Grid.php';
class GridController
{

    private $gridModel;

    public function __construct()
    {
        $this->gridModel = new Grid();
    }

    // Fonction pour créer une grille
    public function createGrid($name, $user_id, $num_rows, $num_columns, $difficulty)
    {

        if ($this->gridModel->createGrid($name, $user_id, $num_rows, $num_columns, $difficulty)) {
            return true;

        } else {
            echo "Erreur lors de la création de la grille ";
        }

    }



    // Fonction pour récupérer une grille par son ID

    public function getById($id)
    {

        return $this->gridModel->getById($id);


    }


    // Fonction pour afficher les grilles pour un utilisateur

    public function displayAllGridUser()
    {

        return $this->gridModel->displayAllGridUser(); // Appel de la méthode pour récupérer les grilles

    }


    // Fonction pour afficher les grilles pour un admin

    public function displayAllGridAdmin($page, $search)
    {

        return $this->gridModel->displayAllGridAdmin($page, $search); // Appel de la méthode pour récupérer les grilles

    }


    public function getTotalGridsCount()
    {
        return $this->gridModel->getTotalGridsCount();
    }

    public function countTotalGrids()
    {

        return $this->gridModel->countTotalGrids();
    }


    // Fonction pour supprimer une grille
    public function deleteGrid($grid_id)
    {
        return $this->gridModel->deleteGrid($grid_id);
    }




    // Fonction pour jouer une grille

    public function playGrid($id)
    {


        return $this->gridModel->playGrid($id);

    }

    // Fonction pour récuperer le ID d'une grille

    public function getMaxGrid()
    {


        return $this->gridModel->getMaxGrid();

    }


}