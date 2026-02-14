<?php
require_once 'utils/Database.php';

$message = "";
$div_class = "";

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    $pdo = Database::getInstance();
    
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email AND activation_token = :token");
    $stmt->execute(['email' => $email, 'token' => $token]);
    $user = $stmt->fetch();

    if ($user) {
        $update = $pdo->prepare("UPDATE users SET is_active = 1, activation_token = NULL WHERE id = :id");
        $update->execute(['id' => $user['id']]);
        
        $message = "Account activated successfully! You can login now.";
        $msg_class = "success";
        header("refresh:3;url=login.php");
    } else {
        $message = "Invalid link or account already activated";
        $msg_class = "error";
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ativação - Camagru</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header>
        <h1>Camagru</h1>
    </header>

    <main>
		<div>
			<div class="message <?php echo $msg_class; ?>">
        	    <?php echo htmlspecialchars($message); ?>
        	</div>
        	<br>
			<a class= "btn-link" href="index.php">Login</a>
		</div>
    </main>

    <footer>
        <p>2026 Camagru - 42Rio - gcorreia</p>
    </footer>
</body>
</html>
