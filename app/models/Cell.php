<?php

require_once __DIR__ . '/../core/Database.php';

class Cell
{
    private $db;

    public function __construct()
    {
        // Connexion à la base de données via la classe Database
        $this->db = Database::connect();
    }

    // Ajouter une cellule
    public function createCell($gridId, $row, $col, $content)
    {
        $query = "INSERT INTO cells (grid_id, rowa, col, content) 
                  VALUES (:grid_id, :rowa, :col, :content)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':grid_id', $gridId);
        $stmt->bindParam(':rowa', $row); // Changement : $row au lieu de $rowa
        $stmt->bindParam(':col', $col);
        $stmt->bindParam(':content', $content);

        // Exécution de la requête
        return $stmt->execute();
    }

    // Récupérer les cellules d'une grille
    public function getCellsByGridId($gridId)
    {
        $query = "SELECT * FROM cells WHERE grid_id = :grid_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':grid_id', $gridId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getBlackCells($gridId)
{
    $query = "SELECT * FROM cells WHERE grid_id = :grid_id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':grid_id', $gridId, PDO::PARAM_INT);
    $stmt->execute();

    $cells = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Parcourir les cellules pour modifier le contenu si nécessaire
    foreach ($cells as &$cell) {
        if ($cell['content'] !== 'black') {
            $cell['content'] = ""; // Écraser le contenu avec une chaîne vide
        }
    }

    return $cells;
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
