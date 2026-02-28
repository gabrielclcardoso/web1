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
		$email = $_POST['email'] ?? $currentData['email'];
		$notifications = isset($_POST['notifications']) ? 1 : 0;

		$res = $user->updateInfo($_SESSION['user_id'], $username, $email, $notifications);
        if ($res['status']) {
            $_SESSION['username'] = $username;
            $message = "Information updated!";
            $div_class = "success";
            $currentData['notif_comment'] = $notifications;
        } else {
			$message = $res['message'] ?? "Error updating info.";
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
		<label for="username">Username:</label>
		<input type="text" id="username" name="username" autocomplete="username" value="<?php echo htmlspecialchars($currentData['username']); ?>" required>
		<label for="email">E-mail:</label>
        <input type="email" id="email" name="email" autocomplete="email" value="<?php echo htmlspecialchars($currentData['email']); ?>" required>
		<div style="margin: 15px 0; display: flex; align-items: center; gap: 10px;">
            <input type="checkbox" id="notifications" name="notifications" value="1" style="width: auto; margin: 0;" 
                <?php echo (!isset($currentData['notif_comment']) || $currentData['notif_comment'] == 1) ? 'checked' : ''; ?>>
            <label for="notifications" style="cursor: pointer;">Send me an email when someone comments on my pictures</label>
        </div>
        <button type="submit" name="update_info" class="btn">Submit Changes</button>
    </form>

    <form method="POST">
        <h3>Change Password</h3>
		<label for="password">New Password:</label>
        <input type="password" id="password" name="password" autocomplete="new-password" placeholder="New Password" required>
        <button type="submit" name="update_pass" class="btn" style="background: #6c757d;">Change Password</button>
    </form>
</div>

<?php require_once '../srcs/includes/footer.php'; ?>
