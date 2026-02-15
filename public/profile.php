<?php
require_once '../srcs/includes/init.php';
require_once '../srcs/utils/User.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user = new User();
$currentData = $user->getById($_SESSION['user_id']);
$message = "";
$div_class = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_info'])) {
        $username = $_POST['username'] ?? $currentData['username'];
        $email = $_POST['email'] ?? $currentData['username'];

        $res = $user->updateInfo($_SESSION['user_id'], $username, $email);
        if ($res['status']) {
            $_SESSION['username'] = $username;
            $message = "Information updated!";
            $div_class = "success";
        } else {
            $message = $res['message'];
            $div_class = "error";
        }
    } elseif (isset($_POST['update_pass'])) {
        $pass = $_POST['password'] ?? '';
        if (strlen($pass) < 8) {
            $message = "The password must have at least 8 characters";
            $div_class = "error";
        } else {
            if ($user->updatePassword($_SESSION['user_id'], $pass)) {
                $message = "Password updated successfully!";
                $div_class = "success";
            }
        }
    }
}

$page_title = "Camagru - Profile";
require_once '../srcs/includes/header.php';
?>

<div class="form-container" style="max-width: 500px;">
    <h2>Edit Profile</h2>

    <?php if ($message): ?>
        <div class="message <?php echo $div_class; ?>"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" style="margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
        <h3>Account Information</h3>
        <label>Username:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($currentData['username']); ?>" required>
        <label>E-mail:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($currentData['email']); ?>" required>
        <button type="submit" name="update_info" class="btn">Submit Changes</button>
    </form>

    <form method="POST">
        <h3>Change Password</h3>
        <input type="password" name="password" placeholder="New Password" required>
        <button type="submit" name="update_pass" class="btn" style="background: #6c757d;">Change Password</button>
    </form>
</div>

<?php require_once '../srcs/includes/footer.php'; ?>
