<?php
require_once '../srcs/includes/init.php';
require_once '../srcs/utils/User.php';

$message = "";
$div_class = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $message = "Please fill out all the fields";
        $div_class = "error";
    } else {
        $user = new User();
        $result = $user->login($username, $password);

        if ($result['status']) {
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['username'] = $result['username'];
            
            header("Location: index.php");
            exit;
        } else {
            $message = $result['message'];
            $msg_class = "error";
        }
    }
}

$page_title = 'Camagru - Login';
require_once '../srcs/includes/header.php';
?>

<div class="form-container">
    <h2>Login</h2>

    <?php if ($message): ?>
        <div class="message <?php echo $msg_class; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
		<input type="text" id="username" name="username" placeholder="Username" autocomplete="username" required>
        <input type="password" id="password" name="password" placeholder="Password" autocomplete="current-password" required>
        <button type="submit" class="btn">login</button>
    </form>

    <p style="margin-top: 20px;">
        Don't have an account? <a href="register.php">Register</a>
    </p>
    <p>
        <a href="forgot_password.php" style="font-size: 0.9em;">Forgot my password</a>
    </p>
</div>

<?php 
require_once '../srcs/includes/footer.php'; 
?>
