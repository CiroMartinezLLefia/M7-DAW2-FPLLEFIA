<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>p1 php</title>
</head>
<body>
    <h1>hola pp1 teoria</h1>
    
    <?php
        echo '<h2>hola subtitulo</h2>';
        echo "hola mundo dos comillas <br>";

        $nom = 'Miguel Angel';
        $apellido = "Saiz";
        $edad = 21;
        $frase = "Hola, soy $nom $apellido y tengo $edad años <br>";
    ?>

    <section class="div-padre">
        <h1>Numeros del 0-10</h1>
        <?php
        for($i = 0; $i <= 10; $i++)
        {
            echo "<div class=\"num-box\">Numero: $i</div> <br>";
        }
        ?>
    </section>

    <style>
        .num-box
        {
            background-color: red;
            padding: 2rem;
        }
        .div-padre
        {
            background-color: green;
            gap: 1em;
            display: flex;
            justify-content: wrap;
        }
    </style>

    <?php
        $peliculas = [];
        $peliculas[0]['nombre'] = "La princesa Mononoke";
        $peliculas[0]['img'] = "https://m.media-amazon.com/images/M/MV5BYjc1YjI2OGUtNzgyOC00ZmFiLThkNzgtYTRkNDQ5ZGEwM2I1XkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg";
        $peliculas[0]['puntuacion'] = "9.8";

        $peliculas[1]['nombre'] = "Los caballeros de la mesa cuadrada y sus locos seguidores (1975)";
        $peliculas[1]['img'] = "https://m.media-amazon.com/images/M/MV5BMGJmODUwODAtYjQyMi00MTVkLWExNGYtNjhjM2M1MDMxMjFlXkEyXkFqcGc@._V1_.jpg";
        $peliculas[1]['puntuacion'] = "8.8";

        $peliculas[2]['nombre'] = "Pacific Rim";
        $peliculas[2]['img'] = "https://m.media-amazon.com/images/M/MV5BMTY3MTI5NjQ4Nl5BMl5BanBnXkFtZTcwOTU1OTU0OQ@@._V1_FMjpg_UX1000_.jpg";
        $peliculas[2]['puntuacion'] = "9.2";

        $peliculas[3]['nombre'] = "No es país para viejos";
        $peliculas[3]['img'] = "https://uncuadernoenblanco.com/wp-content/uploads/2022/11/no_es_pa_s_para_viejos-744066475-large.jpg";
        $peliculas[3]['puntuacion'] = "7.8";
    ?>

    <h1>Peliculas buenas</h1>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Imagen</th>
            <th>Puntuacion</th>
        </tr>
        <?php
            for($i = 0; $i <= 3; $i++)
            {
                echo "        
                <tr>
                    <td class=\"oscuro\">{$peliculas[$i]['nombre']}</td>
                    <td><img src=\"{$peliculas[$i]['img']}\" alt=\"peli\"></td>
                    <td class=\"rojo\">{$peliculas[$i]['puntuacion']}</td>
                </tr>
                ";
            }
        ?>
    </table>

    <style>
        body
        {
            background-color: lightblue;
        }

        table, th, td {
            border: 1px solid;
        }
        td img
        {
            width: 80px;
            height: 120px;
        }
        th
        {
            background-color: yellow;
        }
        
        .oscuro
        {
            background-color: grey;
        }
        .rojo
        {
            background-color: red;
        }
    </style>
</body>
</html>