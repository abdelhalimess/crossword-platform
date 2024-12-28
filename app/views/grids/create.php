<?php
require_once '../../core/Database.php';


// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = new Database();
        $pdo = $db->connect();

        // Gather form data
        $name = $_POST['name'] ?? '';
        $user_id = 14; // Replace with the logged-in user's ID if you have authentication
        $num_rows = (int) ($_POST['num_rows'] ?? 0);
        $num_columns = (int) ($_POST['num_columns'] ?? 0);
        $difficulty = $_POST['difficulty'] ?? 'debutant';
        $black_cells = $_POST['black_cells'] ?? '{}';
        $horizontal_clues = $_POST['horizontal_clues'] ?? '{}';
        $vertical_clues = $_POST['vertical_clues'] ?? '{}';
        $solution = $_POST['solution'] ?? '';

        // Validate inputs
        if (empty($name) || $num_rows <= 0 || $num_columns <= 0 || empty($solution)) {
            throw new Exception('All fields are required and must be valid.');
        }

        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO grids 
            (name, user_id, num_rows, num_columns, difficulty, black_cells, horizontal_clues, vertical_clues, solution) 
            VALUES 
            (:name, :user_id, :num_rows, :num_columns, :difficulty, :black_cells, :horizontal_clues, :vertical_clues, :solution)
        ");
        $stmt->execute([
            ':name' => $name,
            ':user_id' => $user_id,
            ':num_rows' => $num_rows,
            ':num_columns' => $num_columns,
            ':difficulty' => $difficulty,
            ':black_cells' => $black_cells,
            ':horizontal_clues' => $horizontal_clues,
            ':vertical_clues' => $vertical_clues,
            ':solution' => $solution,
        ]);

        echo "Grid created successfully!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Grid</title>
</head>
<body>
    <h1>Create a New Grid</h1>
    <form method="POST" action="">
        <label for="name">Grid Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="num_rows">Number of Rows:</label><br>
        <input type="number" id="num_rows" name="num_rows" required><br><br>

        <label for="num_columns">Number of Columns:</label><br>
        <input type="number" id="num_columns" name="num_columns" required><br><br>

        <label for="difficulty">Difficulty:</label><br>
        <select id="difficulty" name="difficulty" required>
            <option value="debutant">Beginner</option>
            <option value="intermediaire">Intermediate</option>
            <option value="expert">Expert</option>
        </select><br><br>

        <label for="black_cells">Black Cells (JSON):</label><br>
        <textarea id="black_cells" name="black_cells" required></textarea><br><br>

        <label for="horizontal_clues">Horizontal Clues (JSON):</label><br>
        <textarea id="horizontal_clues" name="horizontal_clues" required></textarea><br><br>

        <label for="vertical_clues">Vertical Clues (JSON):</label><br>
        <textarea id="vertical_clues" name="vertical_clues" required></textarea><br><br>

        <label for="solution">Solution:</label><br>
        <textarea id="solution" name="solution" required></textarea><br><br>

        <button type="submit">Create Grid</button>
    </form>
</body>
</html>
