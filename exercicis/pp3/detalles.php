  <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peliculas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Detalles</h1>
    <?php
        include ('peliculas.php');
        $id = $_GET['idPelicula'];

        echo $peliculas[$id]['nombre'];
        echo '<br>';
        echo $peliculas[$id]['horarios'];
        echo '<br>';
        echo $peliculas[$id]['sinopsis'];
        echo '<br>';
        echo $peliculas[$id]['duracion'];
        echo '<br>';
        echo $peliculas[$id]['director'];
        echo '<br>';
        echo $peliculas[$id]['actores'];
        echo '<br>';
        echo $peliculas[$id]['calificacion'];
        echo '<br>';
        echo $peliculas[$id]['genero'];
    ?>
    </div>
</body>
</html>