<?php 
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
    header('Location: ../../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN PANEL</title>
</head>
<body>
    <h1>PANELL DADMINSISTRADOR</h1>
    <nav>
        <ul>
            <li><a href="">Gestionar Noticies</a></li>
            <li>Gestionar Projectes</li>
            <li>Gestionar Testimonis</li>
            <li>Gestionar FAQ's</li>
        </ul>
    </nav>
</body>
</html>