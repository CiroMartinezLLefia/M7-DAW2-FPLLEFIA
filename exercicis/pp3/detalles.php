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

        echo '<div class="detalles-container">';
            echo '<img class="imgDetalles" src="' . htmlspecialchars($peliculas[$id]['imagen']) . '" alt="' . htmlspecialchars($peliculas[$id]['nombre']) . '">';

            echo '<div class="infoDetalles">';
                echo "<h2>" . $peliculas[$id]['nombre'] . "</h2>";
                echo "<table border='1' cellpadding='5'>";
                    echo "<tr><th>Sinopsis</th><td>" . $peliculas[$id]['sinopsis'] . "</td></tr>";
                    echo "<tr><th>Duración</th><td>" . $peliculas[$id]['duracion'] . "</td></tr>";
                    echo "<tr><th>Director</th><td>" . $peliculas[$id]['director'] . "</td></tr>";
                    echo "<tr><th>Actores</th><td>" . $peliculas[$id]['actores'] . "</td></tr>";
                    echo "<tr><th>Calificación</th><td>" . $peliculas[$id]['calificacion'] . "</td></tr>";
                    echo "<tr><th>Género</th><td>" . $peliculas[$id]['genero'] . "</td></tr>";
                    echo "<tr><th>Horarios</th><td>" . $peliculas[$id]['horarios'] . "</td></tr>";
                echo "</table>";
            echo "</div>";
        echo "</div>";

    ?>
    </div>
</body>
</html>