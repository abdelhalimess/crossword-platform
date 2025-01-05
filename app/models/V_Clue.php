<?php

require_once __DIR__ . '/../core/Database.php';

class V_Clue
{
    private $db;

    public function __construct()
    {
        // Connexion à la base de données via la classe Database
        $this->db = Database::connect();
    }

    // Ajouter une V_Clue
    public function createV_Clue($id, $gridId, $content)
    {
        $query = "INSERT INTO vertical_clues (id, grid_id, content) 
          VALUES (:id, :grid_id, :content)";

        $stmt = $this->db->prepare($query);
        
        
        $stmt->bindParam(':id', $id); 
        $stmt->bindParam(':grid_id', $gridId);
        $stmt->bindParam(':content', $content);

        // Exécution de la requête
        return $stmt->execute();
    }




    // Récupérer les indices d'une grille
    public function getV_CluesByGridId($gridId)
    {
        $query = "SELECT * FROM vertical_clues WHERE grid_id = :grid_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':grid_id', $gridId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



















    // Récupérer les cellules noires d'une grille
    public function getBlackCells($gridId)
    {
        $query = "SELECT * FROM cells WHERE grid_id = :grid_id AND content = 'black'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':grid_id', $gridId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mettre à jour une cellule
    public function updateCell($gridId, $row, $col, $content)
    {
        $query = "UPDATE cells SET content = :content WHERE grid_id = :grid_id AND rowa = :rowa AND col = :col";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':grid_id', $gridId);
        $stmt->bindParam(':rowa', $row); // Correction du nom de la colonne en `rowa`
        $stmt->bindParam(':col', $col);

        return $stmt->execute();
    }

    // Supprimer une cellule
    public function deleteCell($gridId, $row, $col)
    {
        $query = "DELETE FROM cells WHERE grid_id = :grid_id AND rowa = :rowa AND col = :col";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':grid_id', $gridId);
        $stmt->bindParam(':rowa', $row); // Correction du nom de la colonne en `rowa`
        $stmt->bindParam(':col', $col);

        return $stmt->execute();
    }
}
?>
