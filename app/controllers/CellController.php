<?php
require_once __DIR__ . '/../models/Cell.php';

class CellController
{
    private $cellModel;

    public function __construct()
    {
        $this->cellModel = new Cell();
    }

    // Ajouter une cellule
    public function addCell($gridId, $row, $col, $content)
    {
        return $this->cellModel->createCell($gridId, $row, $col, $content); // Correction : $row au lieu de $rowa
    }

    // Récupérer les cellules d'une grille
    public function getCells($gridId)
    {
        return $this->cellModel->getCellsByGridId($gridId);
    }


    

    // Récupérer les cellules noires
    public function getBlackCells($gridId)
    {
        return $this->cellModel->getBlackCells($gridId);
    }
    




    // Mettre à jour une cellule
    public function updateCell($gridId, $row, $col, $content)
    {
        return $this->cellModel->updateCell($gridId, $row, $col, $content);
    }

    // Supprimer une cellule
    public function deleteCell($gridId, $row, $col)
    {
        return $this->cellModel->deleteCell($gridId, $row, $col);
    }



    
}
?>
