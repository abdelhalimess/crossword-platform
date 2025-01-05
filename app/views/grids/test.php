<?php

require_once '../../controllers/GridController.php';

$gridController = new GridController();

$gridId = $gridController->getMaxGrid();

echo "La valeur de l'ID est : " . htmlspecialchars($gridId) . "<br>";
echo "Le type de l'ID est : " . gettype($gridId);




?>
