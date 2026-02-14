<?php
$var = '42';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru - Home</title>
    <style>
        body { font-family: sans-serif; margin: 0; display: flex; flex-direction: column; min-height: 100vh; }
        header, footer { background: #333; color: white; padding: 1rem; text-align: center; }
        main { flex: 1; padding: 2rem; text-align: center; }
        .status { padding: 10px; border-radius: 5px; background: #eee; display: inline-block; margin-top: 20px; }
        .btn { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
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
