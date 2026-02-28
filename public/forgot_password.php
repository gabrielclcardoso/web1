<?php
require_once '../srcs/includes/init.php';
require_once '../srcs/utils/User.php';
require_once '../srcs/utils/Email.php';

$message = "";
$div_class = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $user = new User();
    $token = $user->generateResetToken($email);
    $message = "Recovery email sent";
    $div_class = "success";

    if ($token) {
        sendResetEmail($email, $token);
	}
}

$page_title = "Camagru - Password Recovery";
require_once '../srcs/includes/header.php';
?>

<div class="form-container">
    <h2>Password recovery</h2>
    <?php if ($message): ?>
        <div class="message <?php echo $div_class; ?>"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST">
        <p>Fill your email to receive tha password reset link.</p>
		<label for="email">E-mail</label>
        <input type="email" id="email" name="email" placeholder="your e-mail" autocomplete="email" required>
        <button type="submit" class="btn">Send link</button>
    </form>
    <p><a href="login.php">Login</a></p>
</div>

<?php require_once '../srcs/includes/footer.php'; ?>
