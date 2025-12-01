<?php
session_start();
require_once 'config.php';

// 1
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 2
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 3
    $stmt = $mysqli->prepare("SELECT id, nom, password, rol FROM usuaris WHERE email = ?");

    // 4
    if (!$stmt) {
        die('Error en la preparació de la consulta: ' . $mysqli->error);
    }

    // 5
    $stmt->bind_param('s', $email);

    // 6
    $stmt->execute();

    // 7
    $result = $stmt->get_result();
    var_dump($result);

    // 8
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        print_r($user);
    }

    // 9
    if (password_verify($password, $user['password'])) {
        // 10
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_rol'] = $user['rol'];
        echo 'Inici de sessió exitós! Benvingut, ' . htmlspecialchars($user['nom']) . '.';
        header('Location: index.php');
        exit();
    } else {
        echo 'Credencials incorrectes. Torna-ho a intentar.';
    }

    // 11
    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Formulario tonto para comprobar username y password</title>
    </head>
    <body>
        <h2>Login de Usuario</h2>
        <form method="POST" action="login.php">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            
            <label for="password">Contraseña:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            
            <input type="submit" value="Iniciar Sesión">
    </body>
</html>
