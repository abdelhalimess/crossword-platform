<?php

require_once './core/Database.php';

class Grid
{
    private $db;

    public function __construct()
    {
        // Connexion à la base de données via la classe Database
        $this->db = Database::connect();
    }

    // Récupérer toutes les grilles publiques
    public function getAllPublicGrids()
    {
        $query = "SELECT * FROM grids WHERE difficulty IN ('debutant', 'intermediaire', 'expert') ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Retourner toutes les grilles sous forme de tableau associatif
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer une grille spécifique par son ID
    public function getGridById($id)
    {
        $query = "SELECT * FROM grids WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Créer une nouvelle grille
    public function createGrid($name, $userId, $numRows, $numColumns, $difficulty, $blackCells, $horizontalClues, $verticalClues, $solution)
    {
        $query = "INSERT INTO grids (name, user_id, num_rows, num_columns, difficulty, black_cells, horizontal_clues, vertical_clues, solution) 
                  VALUES (:name, :user_id, :num_rows, :num_columns, :difficulty, :black_cells, :horizontal_clues, :vertical_clues, :solution)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':num_rows', $numRows);
        $stmt->bindParam(':num_columns', $numColumns);
        $stmt->bindParam(':difficulty', $difficulty);
        $stmt->bindParam(':black_cells', json_encode($blackCells)); // Convertir les cellules noires en JSON
        $stmt->bindParam(':horizontal_clues', json_encode($horizontalClues)); // Convertir les indices horizontaux en JSON
        $stmt->bindParam(':vertical_clues', json_encode($verticalClues)); // Convertir les indices verticaux en JSON
        $stmt->bindParam(':solution', $solution);
        
        return $stmt->execute();
    }

    // Mettre à jour une grille existante
    public function updateGrid($id, $name, $numRows, $numColumns, $difficulty, $blackCells, $horizontalClues, $verticalClues, $solution)
    {
        $query = "UPDATE grids SET name = :name, num_rows = :num_rows, num_columns = :num_columns, difficulty = :difficulty, 
                  black_cells = :black_cells, horizontal_clues = :horizontal_clues, vertical_clues = :vertical_clues, solution = :solution
                  WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':num_rows', $numRows);
        $stmt->bindParam(':num_columns', $numColumns);
        $stmt->bindParam(':difficulty', $difficulty);
        $stmt->bindParam(':black_cells', json_encode($blackCells)); // Convertir les cellules noires en JSON
        $stmt->bindParam(':horizontal_clues', json_encode($horizontalClues)); // Convertir les indices horizontaux en JSON
        $stmt->bindParam(':vertical_clues', json_encode($verticalClues)); // Convertir les indices verticaux en JSON
        $stmt->bindParam(':solution', $solution);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    // Supprimer une grille par son ID
    public function deleteGrid($id)
    {
        $query = "DELETE FROM grids WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    // Récupérer les indices d'une grille (à partir de la colonne `horizontal_clues` et `vertical_clues`)
    public function getClues($id)
    {
        $query = "SELECT horizontal_clues, vertical_clues FROM grids WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
