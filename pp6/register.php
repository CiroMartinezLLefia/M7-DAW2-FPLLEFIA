<?php
require_once 'config.php';
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    // 1
    $nom = $_POST['nom']; 
    $email = $_POST['email']; 
    $password = $_POST['password'];
    $rol = 'user';
    // 2
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    // 3
    $stmt = $mysqli -> prepare("INSERT INTO usuaris (nom, email, password, rol, data_registre) VALUES (?, ?, ?, 'user', NOW))");
    // 4
    if (!$stmt) {
        die('Error en la preparació de la consulta: ' . $mysqli -> error);
    }
    // 5
    $stmt -> bind_param('sss', $nom, $email, $hashed_password);
    // 6
    if ($stmt -> execute()) {
        echo 'Registre exitós!. <a href="login.php">Inicia sessió aquí</a>.';
        exit();
    } else {
        echo 'Error en el registre: ' . $stmt -> error;
    }
    // 7
    $stmt -> close();
    $mysqli -> close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
</head>
<body>
    <h2>Registro de Usuario</h2>
    <form method="POST" action="register.php">
        <label for="nom">Nombre:</label><br>
        <input type="text" id="nom" name="nom" required><br><br>
        
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        
        <input type="submit" value="Registrar">
</body>
</html>