<?php

session_start();

$_SESSION['user'] = 'Maria';
$_SESSION['role'] = 'Admin';

echo 'Sesion iniciada con exito';
echo '<br>';
echo 'Usuario: ' . $_SESSION['user'];
echo '<br>';
echo 'Rol: ' . $_SESSION['role'];