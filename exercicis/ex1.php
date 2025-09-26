<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 1</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Ejercicio 1</h1>
    
    <div class="bloque">
        <?php
            for($i = 50; $i<=500; $i++)
            {
                if ($i % 2 == 0) echo "<div>$i</div>";
            }
        ?>
    </div>
</body>
</html>