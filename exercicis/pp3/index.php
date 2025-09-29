<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peliculas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Peliculas</h1>
    <div class="peliculasCards">
        <?php
            include ('peliculas.php');
            foreach($peliculas as $pelicula) {
                echo '<div class="peliculaCard">';
                echo '<img src="' . htmlspecialchars($pelicula['imagen']) . '" alt="' . htmlspecialchars($pelicula['nombre']) . '">';
                
                echo '<div class="details">';
                echo '<h2>' . htmlspecialchars($pelicula['nombre']) . '</h2>';
                echo '<p><strong>Horaris:</strong> ' . htmlspecialchars($pelicula['horarios']) . '</p>';
                echo '<a href="trailer.php?pelicula=' . urlencode($pelicula['nombre']) . '">Veure tràiler</a>';
                echo '<a href="detalles.php?idPelicula=' . urlencode($pelicula['id']) . '">Veure més informació</a>';
                echo '</div>';
                echo '</div>';
            }
        ?>
    </div>
</body>
</html>