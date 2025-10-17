<?php
session_start();

echo 'Sesion iniciada con exito';
echo '<br>';
echo 'Usuario: ' . $_SESSION['user'];
echo '<br>';
echo 'Rol: ' . $_SESSION['role'];