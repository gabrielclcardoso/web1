<?php
require_once '../srcs/includes/init.php';
require_once '../srcs/utils/User.php';

$message = "";
$div_class = "";
$token = $_GET['token'] ?? $_POST['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $token) {
    $new_password = $_POST['password'] ?? '';
    
    if (strlen($new_password) < 8) {
        $message = "The password must have at least 8 characters";
        $div_class = "error";
    } else {
        $user = new User();
        if ($user->resetPassword($token, $new_password)) {
            $message = "Password updated! You can login now.";
            $div_class = "success";
            header("refresh:3;url=login.php");
        } else {
            $message = "Invalid or expired token.";
            $div_class = "error";
        }
    }
}

$page_title = "Camagru - New Password";
require_once '../srcs/includes/header.php';
?>

<div class="form-container">
    <h2>Setup new password</h2>
    <?php if ($message): ?>
        <div class="message <?php echo $div_class; ?>"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <input type="password" name="password" placeholder="New Password" required>
        <button type="submit" class="btn">Redefine Password</button>
    </form>
</div>

<?php require_once '../srcs/includes/footer.php'; ?>
