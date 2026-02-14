<?php
require_once 'utils/User.php';
$message = "";
$div_class = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = new User();

    if (strlen($password) < 8) {
        $message = "The password must have at least 8 characters";
		$div_class = "error";
    } else {
        if ($user->create($username, $email, $password)) {
            $message = "User registered! Verify your account through the link sent to your e-mail";
			$div_class = "success";
        } else {
            $message = "Error registering. Username or e-mail already being used";
			$div_class = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="us-en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Registro - Camagru</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

    <header>
        <h1>Camagru</h1>
    </header>

    <main>
        <div class="form-container">
            <h2>Register</h2>

			<div class= "message <?php echo $div_class; ?>">
				<?php if ($message): ?>
            	    <p class="notification"><?php echo htmlspecialchars($message); ?></p>
            	<?php endif; ?>
			</div>
            
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="E-mail" required>
                <input type="password" name="password" placeholder="Password (min. 8 characters)" required>
                <button type="submit" class="btn">Register</button>
            </form>

        </div>
    </main>

    <footer>
        <p>2026 Camagru - 42Rio - gcorreia</p>
    </footer>

</body>
</html>
