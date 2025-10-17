<?php
session_start();
include 'header.php';

//COMPRUEBA USUARIO, SI NO, RETURN A INDEX.PHP

if(isset($_POST['userName']))
{
    $_SESSION['userName'] = $_POST['userName'];
    $name = $_SESSION['userName'];
    $_SESSION['password'] = $_POST['password'];
    $password = $_SESSION['password'];
}

echo 'Usuario: ' . $name . '<br>';
echo 'Contraseña: ' . $password;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>
<body>
    <form action="logout.php">
        <button type="submit">Logout</button>
    </form>
    <form action="index.php">
        <button type="submit">Inicio</button>
    </form>

    <label for=""></label>

</body>
</html>