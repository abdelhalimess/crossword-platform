<?php

require_once __DIR__ . '/../core/Database.php';

class Grid
{
    private $db;

    public function __construct()
    {
        // Connexion à la base de données via la classe Database
        $this->db = Database::connect();
    }


    // Créer une nouvelle grille
    public function createGrid($name, $userId, $numRows, $numColumns, $difficulty)
    {
        $query = "INSERT INTO grids (name, user_id, num_rows, num_columns, difficulty, created_at) 
                  VALUES (:name, :user_id, :num_rows, :num_columns, :difficulty, :created_at)";

        $stmt = $this->db->prepare($query);

        // Récupérer la date et l'heure actuelles
        $currentDateTime = date('Y-m-d H:i:s');

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':num_rows', $numRows);
        $stmt->bindParam(':num_columns', $numColumns);
        $stmt->bindParam(':difficulty', $difficulty);
        $stmt->bindParam(':created_at', $currentDateTime);

        // Exécution de la requête
        if ($stmt->execute()) {
            return true;
        }

        header("Location: ../../index.php");
    }


    public function getById($id)
    {
        $query = 'SELECT * FROM grids WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ---------------------------------------------------

    public function displayAllGridUser()
    {
        // Préparer la requête SQL pour récupérer toutes les grilles
        $query = "SELECT * FROM grids";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Retourner les résultats sous forme de tableau associatif
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function displayAllGridAdmin($page = 1, $search = null)
    {
        $limit = 15;
        // Limite d'éléments par page
        $offset = ($page - 1) * $limit; // Calcul de l'offset pour la pagination

        // Construction de la requête
        $query = "SELECT * FROM grids WHERE 1"; // Par défaut, on récupère toutes les grilles

        // Si une recherche est fournie, on ajoute un filtre
        if ($search) {
            $query .= " AND name LIKE :search"; // Recherche par nom
        }

        $query .= " LIMIT :limit OFFSET :offset"; // Ajout de la pagination

        $stmt = $this->db->prepare($query);

        // Si une recherche est spécifiée, on lie le paramètre
        if ($search) {
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }

        // Bind des autres paramètres
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        // Retourner les résultats sous forme de tableau associatif
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Supprimer une grille par son ID
    public function deleteGrid($id)
    {
        $query = "DELETE FROM grids WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);

        $stmt->execute();
        if ($stmt->execute()) {
            $_SESSION['success'] = "Grille supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Échec de la suppression de la grille.";
        }
    }



    public function getTotalGridsCount()
    {
        $query = "SELECT COUNT(*) FROM grids";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn(); // Retourne le nombre total de grilles
    }



    public function countTotalGrids($search = null)
    {
        $query = "SELECT COUNT(*) FROM grids WHERE 1"; // Par défaut, on compte toutes les grilles

        // Si une recherche est fournie, on ajoute un filtre
        if ($search) {
            $query .= " AND name LIKE :search";
        }

        $stmt = $this->db->prepare($query);

        // Si une recherche est spécifiée, on lie le paramètre
        if ($search) {
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchColumn(); // Retourne le nombre total de grilles
    }


    public function playGrid($id)
    {
        // Préparer la requête SQL pour récupérer toutes les grilles
        $query = "SELECT * FROM grids WHERE  id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        // Retourner les résultats sous forme de tableau associatif
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function getMaxGrid()
    {
        $query = "SELECT MAX(id) as max_id FROM grids"; // Suppose que la colonne ID s'appelle "id"
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Utilisez fetch au lieu de fetchAll pour une seule ligne
        return ($result['max_id'] ?? 0); // Si aucune ligne, retournez 1
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