<?php
require_once 'utils/Database.php';

$message = "";
$status_class = "error";

try {
    $pdo = Database::getInstance();
    
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (count($tables) > 0) {
        $message = "Connection established successfully. Tables on database: " . implode(", ", $tables);
        $status_class = "success";
    } else {
        $message = "Unable to connect to database";
    }
} catch (Exception $e) {
    $message = "Error dealing with the database";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database test- Camagru</title>
	<link rel="stylesheet" href="public/css/style.css">
</head>
<body>

    <header>
        <h1>Camagru</h1>
    </header>

    <main>
        <div class="card">
            <h2>DB status</h2>
            <div class="<?php echo $status_class; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <br>
        </div>
    </main>

    <footer>
        <p>2026 Camagru - 42Rio - gcorreia</p>
    </footer>

</body>
</html>
