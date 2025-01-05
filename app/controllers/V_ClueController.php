<?php
require_once __DIR__ . '/../models/V_Clue.php';

class V_ClueController
{
    private $V_ClueModel;

    public function __construct()
    {
        $this->V_ClueModel = new V_Clue();
    }

    // Ajouter une cellule
    public function addV_Clue($id, $gridId, $content)
    {
        return $this->V_ClueModel->createV_Clue($id, $gridId, $content); // Correction : $row au lieu de $rowa
    }


    // Récupérer les cellules d'une grille
    public function getV_Clues($gridId)
    {
        return $this->V_ClueModel->getV_CluesByGridId($gridId);
    }








    // à modifier 

}
?>