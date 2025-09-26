<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>p2</title>
</head>
<body>
    <h1>Pagina para recibir datos</h1>
    <?php
        if (isset($_GET['nom'])){
            $n = $_GET['nom'];

            echo $n;
        } else{
            $n = "No hay nombre";
        }
    ?>
</body>
</html>