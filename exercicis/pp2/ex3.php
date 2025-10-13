<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 3</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Ejercicio 3</h1>
    
    <?php
        $numero = 0;
        $numero = rand(0, 100);
        if ($numero % 2 == 0)
        {
            echo "<div class='verde'>$numero PAR</div>";
        } else 
        {
            echo "<div class='rojo'>$numero IMPAR</div>";
        }
    ?>
</body>
</html>