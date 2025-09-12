<?php

function sayHello($name) {
  echo "Hello $name!";
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
      <img src="img/logo-fpllefia.jfif" alt="logo">
      <h1>Módulo 7 - Práctica 1. Mi primera aplicación en PHP</h1>
    </header>

    <main>
      <?php
        sayHello('remote world');
      ?>
      <img src="img/yo.jpg" alt="yo">
      <p>Texto</p>
    </main>

    <footer>
      <p>Ciro Martinez</p>
      <p>2025-12-09</p>
    </footer>
  </body>
</html>