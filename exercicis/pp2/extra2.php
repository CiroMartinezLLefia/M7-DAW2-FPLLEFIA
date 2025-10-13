<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extra 2</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Extra 2</h1>
    
    <?php
        $resultado = 0;
        $esPrimo = true;
        $numero = rand(0, 100);
        echo "<div class = 'verde'>$numero</div>";
        for($i = 2; $i < $numero; $i++)
        {
            $resultado = $numero % $i;
            if ($resultado == 0)
            {
                echo "<div>$i</div>";
                $esPrimo = false;
            }
        }
        
        if ($esPrimo == false)
        {
            echo "<div class = 'rojo'>$numero No es PRIMO</div>";
        } else
        {
            echo "<div class = 'verde'>$numero Es PRIMO</div>";
        }

    ?>
</body>
</html>