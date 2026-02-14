<?php
$var = '42';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru - Home</title>
	<link rel="stylesheet" href="public/css/style.css">
</head>
<body>

    <header>
        <h1>Camagru</h1>
    </header>

    <main>
        <h2>Welcome to camagru</h2>
        <p>Take a picture and post it on the website</p>
        
        <div class="status">
            <strong>Variable test: </strong> <?php echo $var; ?>
        </div>

        <br><br>
        <a href="#" class="btn">Public gallery</a>
    </main>

    <footer>
        <p>2026 Camagru - 42Rio - gcorreia</p>
    </footer>

</body>
</html>
