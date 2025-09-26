  <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peliculas</title>
</head>
<body>
    <h1>Detalles</h1>
    <?php
        if (isset($_GET['pelicula'])){
            $n = $_GET['pelicula'];

            echo $n;
        } else{
            $n = "No hay peli";
        }
    ?>
    </div>
</body>
</html>