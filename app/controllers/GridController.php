<?php

class GridController {
    private $gridModel;
    
    public function __construct() {
        $this->gridModel = new Grid();
    }
    
    public function showAllGrids() {
        $grids = $this->gridModel->getAllGrids();
        require_once 'app/views/grids/list.php';
    }
    
    public function createGrid($name, $user_id, $num_rows, $num_columns, $difficulty, $black_cells, $horizontal_clues, $vertical_clues, $solution) {
        $this->gridModel->create($name, $user_id, $num_rows, $num_columns, $difficulty, $black_cells, $horizontal_clues, $vertical_clues, $solution);
        header('Location: /grids');
    }
}
