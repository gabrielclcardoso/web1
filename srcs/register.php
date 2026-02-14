<?php
require_once 'utils/User.php';
require_once 'utils/Email.php';
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
		$token = $user->create($username, $email, $password);

        if ($token) {
            if (sendActivationEmail($email, $token)) {
                $message = "Account created! Verify your e-mail to activate.";
                $div_class = "success";
            } else {
                $message = "Account created, but email failed.";
                $div_class = "warning";
            }
        } else {
            $message = "Error: Username or Email already exists.";
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

				<?php if ($message): ?>
					<div class= "message <?php echo $div_class; ?>">
						<p class="notification"><?php echo htmlspecialchars($message); ?></p>
					</div>
            	<?php endif; ?>
            
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
