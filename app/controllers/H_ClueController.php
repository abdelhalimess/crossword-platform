<?php
require_once __DIR__ . '/../models/H_Clue.php';

class H_ClueController
{
    private $H_clueModel;

    public function __construct()
    {
        $this->H_clueModel = new H_Clue();
    }

    // Ajouter une cellule
    public function addH_Clue($id, $gridId, $content)
    {
        return $this->H_clueModel->createH_clue($id, $gridId, $content); // Correction : $row au lieu de $rowa
    }



    // Récupérer les cellules d'une grille
    public function getH_Clues($gridId)
    {
        return $this->H_clueModel->getH_CluesByGridId($gridId);
    }










    // à modifier 

}
?>