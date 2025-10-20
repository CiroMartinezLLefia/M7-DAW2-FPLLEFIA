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
            <label for="title">Título:</label>
            <input type="text" id="title" name="title" required>

            <label for="content">Contenido:</label>
            <input type="text" id="content" name="content" required>
            
            <label for="date">Fecha:</label>
            <input type="date" id="date" name="date" required>

            <label for="image">Imagen (URL):</label>
            <input type="text" id="image" name="image" required>

            <label for="category">Categoría:</label>
            <input type="text" id="category" name="category" required>

            <button type="submit">Añadir Noticia</button>
        </form>
    </main>

    <?php include_once 'inc/footer.php'; ?>
</body>
</html>
