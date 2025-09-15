<?php

function sayName($name) {
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
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
    <header>
      <img src="imagenes/logo-fpllefia.jfif" alt="logo">
      <h1>Módulo 7 - Práctica 1. Mi primera aplicación en PHP</h1>
    </header>

    <main>
      <img src="imagenes/yo.jpg" alt="yo">
      <p>Ciro Martinez</p>
      <p>
        <?='
        Esta aplicacion hace uso de PHP para mostrar datos dinamicos en una pagina web.
        Utilizando la funcion echo para imprimir texto y variables en el HTML.
        '?>
      </p> 
    </main>

    <footer>
      <?php
         sayName('Ciro Martinez');
         echo date('Y-d-m');
      ?>
    </footer>
  </body>

</html>