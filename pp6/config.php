<?php
$host = 'mysql-gestornotasuab.alwaysdata.net';
$dbname = 'gestornotasuab_martinezmartinciro';
$username = '439980';
$password = 'martinezmartincirofpllefiacom';

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli -> connect_error)
{
    die('Error de conexion: ' . $mysqli -> connect_error);
} else
{
    echo '<h1>Conexion exitosa</h1>';
}
?>