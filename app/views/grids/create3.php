<?php
session_start();

require_once '../../controllers/GridController.php';
require_once '../../controllers/CellController.php';
require_once '../../controllers/H_ClueController.php';
require_once '../../controllers/V_ClueController.php';
$gridController = new GridController();




// ------------------ THIS ---------------------------
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id']; // ID de l'utilisateur connecté
} else {
    // Gérer le cas où l'utilisateur n'est pas connecté
    echo "Utilisateur non connecté.";
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si la requête provient d'un formulaire classique (les données sont dans $_POST)
    // Si l'utilisateur est connecté, utiliser son ID
 $data = json_decode(file_get_contents('php://input'), true); // Lire les données JSON
    if (!empty($data) && isset($data['cells']) && is_array($data['cells'])) {
        // Récupérer le prochain ID de grille


        if (isset($data['grid'])) {
            // Parcourir les données du FormData dans 'grid'
            $gridData = $data['grid'];
    
        $name = $gridData['name'] ?? '';
        $rows = $gridData['rows'] ?? '';
        $columns = $gridData['columns'] ?? '';
        $difficulty = $gridData['difficulty'] ?? '';

        $registrationResult = $gridController->createGrid($name,$userId,$rows,$columns,$difficulty);

        }










        
        $gridId = $gridController->getMaxGrid();
    
        $cellController = new CellController();
        $cells = $data['cells']; // Liste des cellules à insérer
        
        $HClueController = new H_ClueController();
        $H_Clues = $data['Hori_Clues']; // Liste des indices horizontaux  
        
        $VClueController = new V_ClueController();
        $V_Clues = $data['Verti_Clues']; // Liste des indices verticaux  
        
        $errors = [];
    
        foreach ($cells as $cell) {
            if (isset($cell['row'], $cell['col'], $cell['content'])) {
                $result = $cellController->addCell($gridId, $cell['row'], $cell['col'], $cell['content']);
                if (!$result) {
                    $errors[] = "Erreur lors de l'insertion de la cellule (row: {$cell['row']}, col: {$cell['col']}).";
                }
            } else {
                $errors[] = "Cellule mal formatée.";
            }
        }
   

        foreach ($H_Clues as $H_clu) {
            if (isset($H_clu['id'], $H_clu['content'])) {
                $result = $HClueController->addH_Clue($H_clu['id'], $gridId, $H_clu['content']);
                if (!$result) {
                    $errors[] = "Erreur lors de l'insertion de l'indice horizontal (id: {$H_clu['id']}).";
                }
            } else {
                $errors[] = "Indice horizontal mal formaté.";
            }
        }
    
        foreach ($V_Clues as $V_clu) {
            if (isset($V_clu['id'], $V_clu['content'])) {
                $result = $VClueController->addV_clue($V_clu['id'], $gridId, $V_clu['content']);
                if (!$result) {
                    $errors[] = "Erreur lors de l'insertion de l'indice vertical (id: {$V_clu['id']}).";
                }
            } else {
                $errors[] = "Indice vertical mal formaté.";
            }
        }
    
        // Vérification des erreurs
        if (empty($errors)) {
            echo json_encode(['success' => true, 'message' => 'Toutes les cellules ont été insérées.']);
        } else {
            echo json_encode(['success' => false, 'errors' => $errors]);
        }
        exit();
    }}
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/grids.css">

    <title>Création de grille </title>
</head>
<body>
<section>
    <h1>Création d'une nouvelle grille</h1>
    <form method="POST" >
        <div id="formContent">

                <label for="name">Nom de la grille :</label>
                <input type="text" id="name" name="name" required><br><br>

                <label for="rows">Nombre de lignes :</label>
                <input type="number" id="rows" name="rows" min="4" max="12" required><br><br>

                <label for="columns">Nombre de  colonnes :</label>
                <input type="number" id="columns" name="columns" min="4" max="12" required><br><br>

            <label for="difficulty">Difficulté:</label><br>
            <select id="difficulty" name="difficulty" required>
            <option value="debutant">Débutant</option>
            <option value="intermediaire">Intermédiaire</option>
            <option value="expert">Expert</option>
        </select><br><br>
            <button onclick="Next()" type="button" id="loadMoreFields" style="margin-left: 40px;">Suivant</button>
        </div>
    </form>
    <div id="buttons">
    <a href="../../index.php">
        
        <button type="button" id="homeButton">Retour à l'Accueil</button>
        
    </a>

    </div>
   
        </section>
    <script src="../../public/js/grid3.js"></script> 
</body>
</html>
