<?php
require_once __DIR__ . '/../models/Grid.php';
class GridController
{






    public function createGrid($name, $user_id, $num_rows, $num_columns, $difficulty)
    {

        $grid = new Grid();
        if ($grid->createGrid($name, $user_id, $num_rows, $num_columns, $difficulty)) {
            return true;

        } else {
            echo "Erreur lors de la création de la grille ";
        }

    }




    public function getById($id)
    {

        $grid = new Grid();
        return $grid->getById($id);


    }


    // ----------------------------------------------------------------------


    public function displayAllGridUser()
    {

        $grid = new Grid();


        return $grid->displayAllGridUser(); // Appel de la méthode pour récupérer les grilles

    }

    public function displayAllGridAdmin($page, $search)
    {

        $grid = new Grid();


        return $grid->displayAllGridAdmin($page, $search); // Appel de la méthode pour récupérer les grilles

    }

    public function sortGrids($page, $sortBy, $order)
    {
        $gridModel = new Grid();
        return $gridModel->sortGrids($page, $sortBy, $order);
    }

    public function getTotalGridsCount()
    {
        $gridModel = new Grid();
        return $gridModel->getTotalGridsCount();
    }

    public function countTotalGrids()
    {
        $gridModel = new Grid();
        return $gridModel->countTotalGrids();
    }



    public function deleteGrid($grid_id)
    {
        $gridModel = new Grid();
        return $gridModel->deleteGrid($grid_id);
    }




    // ----------------------------------------------------------------------


    public function playGrid($id)
    {

        $grid = new Grid();


        return $grid->playGrid($id); // Appel de la méthode pour récupérer les grilles

    }


    public function getMaxGrid()
    {

        $grid = new Grid();


        return $grid->getMaxGrid(); // Appel de la méthode pour récupérer les grilles

    }


}