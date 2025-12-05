<?php
$host = 'mysql-gestornotasuab.alwaysdata.net';
$dbname = 'gestornotasuab_musicschool';
$username = '439980';
$password = 'examenPHP123';

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die('Error de conexion: ' . $mysqli->connect_error);
}

// Establecer conjunto de caracteres recomendado
if (!@$mysqli->set_charset('utf8mb4')) {
    error_log('Warning: no se pudo establecer charset utf8mb4: ' . $mysqli->error);
}
?>
