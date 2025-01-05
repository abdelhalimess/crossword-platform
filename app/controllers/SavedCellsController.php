<?php
require_once __DIR__ . '/../models/SavedCell.php';

class SavedCellsController
{
    private $cellModel;

    public function __construct()
    {
        $this->cellModel = new UserCell();
        
    }

    // Ajouter une cellule
    public function addUserCell($gridId, $userid, $row, $col, $content)
{
    // Récupérer la cellule spécifique à la position (row, col)
    $cell = $this->cellModel->getCellByPosition($userid, $gridId, $row, $col);

    if ($cell) {
        // Si la cellule existe, faire un update
        return $this->cellModel->updateCell($userid, $gridId, $row, $col, $content);
    } else {
        // Sinon, faire un insert
        return $this->cellModel->createCell($userid, $gridId, $row, $col, $content);
    }
}


    // Récupérer les cellules d'une grille
    public function getUserCells($userid,$gridId)
    {
        return $this->cellModel->getCellsByGridId($userid,$gridId);
    }

    public function getNotRegisteredUserCells($gridId)
    {
        return $this->cellModel->getCellsClearContent($gridId);
    }


    // Récupérer les cellules d'une grille
        public function getUserGrids($userid)
        {
            return $this->cellModel->getGridsUser($userid);
        }

    


}
?>
