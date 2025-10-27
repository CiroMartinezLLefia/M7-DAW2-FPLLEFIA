<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mini-wp</title>
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>
    <?php include_once 'inc/header.php'; ?>
    <main>
        <form action="index.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Entrar</button>
        </form>
    </main>

    <?php include_once 'inc/footer.php'; ?>
</body>
</html>
