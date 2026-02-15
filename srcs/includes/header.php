<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Camagru'; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <a class="logo" href="index.php">Camagru</a>
            <div class="nav-links">
                <a href="index.php">Gallery</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="editor.php">Editor</a>
                    <a href="profile.php">Profile (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <main>
