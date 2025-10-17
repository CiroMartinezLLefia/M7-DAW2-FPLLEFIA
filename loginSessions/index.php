<?php
session_start();
include 'header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>
<body>
    <style>
        h1
        {
            background-color: beige;
        }
    </style>
    <h1>Login</h1>

    <form action="home.php" method="POST">
        <label for="userName"></label>
        <input type="text" name="userName" id="userName" placeholder="Nombre de usuario" required>
        <label for="password"></label>
        <input type="password" name="password" id="password" placeholder="Contraseña" required>

        <button type="submit">Enviar</button>
    </form>

    <label for=""></label>

</body>
</html>