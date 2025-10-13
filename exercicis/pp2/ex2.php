<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 2</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Ejercicio 2</h1>
    
    <div class="contenedor">
        <?php
            $temp = 0;
            for($i = 1; $i<=11; $i++)
            {
                echo "<div class='bloqueSmall'>";
                for($j = 1; $j<=9; $j++)
                {
                    $temp = $i * $j;
                    echo "<div>$i * $j = $temp</div>";
                }
                echo "</div>";
            }
        ?>
    </div>
</body>
</html>