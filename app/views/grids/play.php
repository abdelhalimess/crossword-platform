<?php


session_start();



require_once '../../controllers/GridController.php';
require_once '../../controllers/CellController.php';
require_once '../../controllers/H_ClueController.php';
require_once '../../controllers/V_ClueController.php';
require_once '../../controllers/SavedCellsController.php';

require_once '../../controllers/UserController.php';


if (isset($_GET['id'])) {
    // Récupérer la valeur de 'id'
    $id = $_GET['id'];
} else {
    // Gérer le cas où l'utilisateur n'est pas connecté
    // header('Location: ../auth/login.php');

}

$gridController = new GridController();
$playGrid = $gridController->playGrid($id);

$savedCellController = new SavedCellsController();
$cellController = new CellController();



if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id']; // ID de l'utilisateur connecté
    $cells = $savedCellController->getUserCells($userId, $id);
} else {
    // Gérer le cas où l'utilisateur n'est pas connecté
    $userId = '';
    $cells = $cellController->getBlackCells($id);

}


// if 



$cellController = new CellController();
$cells2 = $cellController->getCells($id);




// else





$H_ClueController = new H_ClueController();
$cells3 = $H_ClueController->getH_Clues($id);


$V_ClueController = new V_ClueController();
$cells4 = $V_ClueController->getV_Clues($id);

// Convertir les résultats en JSON pour les passer à JavaScript
$cellsJson = json_encode($playGrid);
$cellsJson2 = json_encode($cells2);
$cellsJson3 = json_encode($cells3);
$cellsJson4 = json_encode($cells4);
$cellsJson5 = json_encode($cells);



// user id 


$data = json_decode(file_get_contents('php://input'), true); // Lire les données JSON
    if (!empty($data) && isset($data['cells']) && is_array($data['cells'])) {
        // Récupérer le prochain ID de grille
    
        $cells = $data['cells']; // Liste des cellules à insérer
        

        $errors = [];
    
        foreach ($cells as $cell) {
            if (isset($cell['row'], $cell['col'], $cell['content'])) {
                $result = $savedCellController->addUserCell($id,$userId, $cell['row'], $cell['col'], $cell['content']);
                if (!$result) {
                    $errors[] = "Erreur lors de l'insertion de la cellule (row: {$cell['row']}, col: {$cell['col']}).";
                }
            } else {
                $errors[] = "Cellule mal formatée.";
            }
        }}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="../../public/css/style.css"> -->
    <title>Jouer</title>
    <link rel="icon" type="image/png" href="../../public/img/crossword-placeholder3.png">

    <link rel="stylesheet" href="../../public/css/play.css">

    <script src="../../public/js/play.js"></script> 
</head>
<body>
    <section>
    <div id="userInfo" data-user-id="<?php echo htmlspecialchars($userId); ?>"></div>

    <a href="../../index.php">
        
        <button type="button" id="homeButton">Retour à l'Accueil</button>
        
    </a>
            <h1 id="name">  </h1>
       
    <!-- Ajouter un élément avec un attribut data qui contient les données JSON -->
    <div id="cell-data" data-cell="<?php echo htmlspecialchars($cellsJson); ?>"></div>
    <div id="cell-data2" data-cell2="<?php echo htmlspecialchars($cellsJson2); ?>"></div>
    <div id="cell-data3" data-cell3="<?php echo htmlspecialchars($cellsJson3); ?>"></div>
    <div id="cell-data4" data-cell4="<?php echo htmlspecialchars($cellsJson4); ?>"></div>
    <div id="cell-data5" data-cell5="<?php echo htmlspecialchars($cellsJson5); ?>"></div>
    

    <div id="buttons"> 
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'registered'): ?>
    <button type="button" id="SauvegarderButton">Sauvegarder</button>
    <?php endif; ?>

    </div>


    </section>

</body>
</html>
