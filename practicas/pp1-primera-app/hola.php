<?php

function sayHello($name) {
  echo $name;
  echo "<br>";
}
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visual Studio Remote :: PHP</title>
  </head>

  <body>
    <header>
      <img src="imagenes/logo-fpllefia.jfif" alt="logo">
      <h1>Módulo 7 - Práctica 1. Mi primera aplicación en PHP</h1>
    </header>

    <main>
      <img src="imagenes/yo.jpg" alt="yo">
      <p>Ciro Martinez</p>
      <p>Explicacion</p> 
    </main>

    <footer>
      <?php
         sayHello('Ciro Martinez');
         echo date('Y-d-m');
      ?>
    </footer>
  </body>

</html>