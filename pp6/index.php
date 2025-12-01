<?php
include 'config.php';

// CAPA 1
$users = $mysqli->query("SELECT * FROM users");

// CAPA 2
$resultUsers = $users->fetch_all(MYSQLI_ASSOC);

var_dump($resultUsers);
?>