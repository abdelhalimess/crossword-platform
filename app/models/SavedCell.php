<?php

require_once __DIR__ . '/../core/Database.php';

class UserCell
{
    private $db;

    public function __construct()
    {
        // Connexion à la base de données via la classe Database
        $this->db = Database::connect();
    }

    // Ajouter une cellule
    public function createCell($userid, $gridId, $row, $col, $content)
    {
        $query = "INSERT INTO saved_cells (user_id,grid_id, rowa, col, content) 
                  VALUES (:user_id, :grid_id, :rowa, :col, :content)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userid);
        $stmt->bindParam(':grid_id', $gridId);
        $stmt->bindParam(':rowa', $row); // Changement : $row au lieu de $rowa
        $stmt->bindParam(':col', $col);
        $stmt->bindParam(':content', $content);

        // Exécution de la requête
        return $stmt->execute();
    }

    // Récupérer les cellules d'une grille
    public function getCellsByGridId($userid, $gridId)
    {
        $query = "SELECT * FROM saved_cells WHERE grid_id = :grid_id AND user_id=:user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userid);
        $stmt->bindParam(':grid_id', $gridId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCellsClearContent($gridId)
{
    $query = "SELECT * FROM saved_cells WHERE grid_id = :grid_id";
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



    public function getCellByPosition($userid, $gridId, $row, $col)
    {
        $query = "SELECT * FROM saved_cells WHERE user_id = :user_id AND grid_id = :grid_id AND rowa = :rowa AND col = :col";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userid);
        $stmt->bindParam(':grid_id', $gridId);
        $stmt->bindParam(':rowa', $row);
        $stmt->bindParam(':col', $col);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // Retourne une seule cellule ou false
    }






    public function getGridsUser($userid)
    {
        $query = "SELECT user_id, grid_id FROM saved_cells WHERE user_id=:user_id GROUP BY user_id, grid_id;";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userid);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Mettre à jour une cellule
    public function updateCell($user_id, $gridId, $row, $col, $content)
    {
        $query = "UPDATE saved_cells SET content = :content WHERE user_id=:user_id AND grid_id = :grid_id AND rowa = :rowa AND col = :col";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':grid_id', $gridId);
        $stmt->bindParam(':rowa', $row); // Correction du nom de la colonne en `rowa`
        $stmt->bindParam(':col', $col);

        return $stmt->execute();

    }



}
?>