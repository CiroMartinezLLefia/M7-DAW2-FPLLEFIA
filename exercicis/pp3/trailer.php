  <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peliculas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Trailer</h1>
    <?php
        include ('peliculas.php');
        $id = $_GET['idPelicula'];

        echo "<h2>" . $peliculas[$id]['nombre'] . "</h2>";

    ?>
    </div>
</body>
</html>