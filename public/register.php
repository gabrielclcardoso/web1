<?php
require_once '../srcs/includes/init.php';
require_once '../srcs/utils/User.php';
require_once '../srcs/utils/Email.php';
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

$page_title = 'Camagru - Register';
require_once '../srcs/includes/header.php';
?>


<div class="form-container">
    <h2>Register</h2>

		<?php if ($message): ?>
			<div class= "message <?php echo $div_class; ?>">
				<p class="notification"><?php echo htmlspecialchars($message); ?></p>
			</div>
    	<?php endif; ?>
    
    <form method="POST">
		<label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Username" autocomplete="username" required>
        
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" placeholder="E-mail" autocomplete="email" required>
        
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Password (min. 8 characters)" autocomplete="new-password" required>
        <button type="submit" class="btn">Register</button>
    </form>

</div>

<?php 
require_once '../srcs/includes/footer.php'; 
?>
