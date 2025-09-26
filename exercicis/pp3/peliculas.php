<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peliculas</title>
</head>
<body>
    <style>   
        body {
            font-family: Arial, sans-serif;
            background: #111;
            color: #fff;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .peliculasCards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .peliculaCard {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.5);
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .peliculaCard:hover {
            transform: scale(1.05);
        }

        .peliculaCard img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .peliculaCard .details {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.8);
            padding: 15px;
            text-align: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .peliculaCard:hover .details {
            opacity: 1;
        }

        .peliculaCard h2 {
            margin: 0 0 10px;
            font-size: 18px;
        }

        .peliculaCard p {
            margin: 5px 0;
        }

        .peliculaCard a {
            display: inline-block;
            margin: 8px 5px 0;
            padding: 8px 12px;
            background: #ff4747;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s ease;
        }

        .peliculaCard a:hover {
            background: #e63939;
        }
    </style>

    <h1>Peliculas</h1>
    <div class="peliculasCards">
        <?php
        $peliculas = [
            [
                'nombre' => 'The Shawshank Redemption',
                'imagen' => 'https://m.media-amazon.com/images/M/MV5BMDAyY2FhYjctNDc5OS00MDNlLThiMGUtY2UxYWVkNGY2ZjljXkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg',
                'horarios' => '18:00, 20:30, 22:45',
                'sinopsis' => 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.',
                'duracion' => '2h 22m',
                'director' => 'Frank Darabont',
                'actores' => 'Tim Robbins, Morgan Freeman, Bob Gunton',
                'calificacion' => '9.3',
                'genero' => 'Drama / Crimen',
                'URLtrailer' => 'https://www.youtube.com/watch?v=NmzuHjWmXOc'
            ],
            [
                'nombre' => 'The Godfather',
                'imagen' => 'https://m.media-amazon.com/images/M/MV5BNGEwYjgwOGQtYjg5ZS00Njc1LTk2ZGEtM2QwZWQ2NjdhZTE5XkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg',
                'horarios' => '17:45, 20:15, 22:50',
                'sinopsis' => 'The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.',
                'duracion' => '2h 55m',
                'director' => 'Francis Ford Coppola',
                'actores' => 'Marlon Brando, Al Pacino, James Caan',
                'calificacion' => '9.2',
                'genero' => 'Crimen / Drama',
                'URLtrailer' => 'https://www.youtube.com/watch?v=sY1S34973zA'
            ],
            [
                'nombre' => 'The Dark Knight',
                'imagen' => 'https://m.media-amazon.com/images/M/MV5BMTMxNTMwODM0NF5BMl5BanBnXkFtZTcwODAyMTk2Mw@@._V1_.jpg',
                'horarios' => '18:30, 21:00, 23:15',
                'sinopsis' => 'When the menace known as the Joker emerges from his mysterious past, he wreaks havoc and chaos on the people of Gotham.',
                'duracion' => '2h 32m',
                'director' => 'Christopher Nolan',
                'actores' => 'Christian Bale, Heath Ledger, Aaron Eckhart',
                'calificacion' => '9.0',
                'genero' => 'Acción / Crimen / Drama',
                'URLtrailer' => 'https://www.youtube.com/watch?v=EXeTwQWrcwY'
            ],
            [
                'nombre' => 'Schindler\'s List',
                'imagen' => 'https://m.media-amazon.com/images/I/817R7RXH9PL._UF1000,1000_QL80_.jpg',
                'horarios' => '19:00, 21:45',
                'sinopsis' => 'In German-occupied Poland during World War II, industrialist Oskar Schindler gradually becomes concerned for his Jewish workforce.',
                'duracion' => '3h 15m',
                'director' => 'Steven Spielberg',
                'actores' => 'Liam Neeson, Ralph Fiennes, Ben Kingsley',
                'calificacion' => '8.9',
                'genero' => 'Biografía / Drama / Historia',
                'URLtrailer' => 'https://www.youtube.com/watch?v=gG22XNhtnoY'
            ],
            [
                'nombre' => '12 Angry Men',
                'imagen' => 'https://upload.wikimedia.org/wikipedia/commons/b/b5/12_Angry_Men_%281957_film_poster%29.jpg',
                'horarios' => '18:15, 20:45',
                'sinopsis' => 'A jury holdout attempts to prevent a miscarriage of justice by forcing his colleagues to reconsider the evidence.',
                'duracion' => '1h 36m',
                'director' => 'Sidney Lumet',
                'actores' => 'Henry Fonda, Lee J. Cobb, Martin Balsam',
                'calificacion' => '9.0',
                'genero' => 'Drama / Crimen',
                'URLtrailer' => 'https://www.youtube.com/watch?v=_13J_9B5jEk'
            ],
            [
                'nombre' => 'The Lord of the Rings: The Return of the King',
                'imagen' => 'https://m.media-amazon.com/images/M/MV5BMTZkMjBjNWMtZGI5OC00MGU0LTk4ZTItODg2NWM3NTVmNWQ4XkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg',
                'horarios' => '17:30, 20:00, 22:45',
                'sinopsis' => 'Gandalf and Aragorn lead the World of Men against Sauron’s army to draw his gaze from Frodo and Sam as they approach Mount Doom with the One Ring.',
                'duracion' => '3h 21m',
                'director' => 'Peter Jackson',
                'actores' => 'Elijah Wood, Viggo Mortensen, Ian McKellen',
                'calificacion' => '8.9',
                'genero' => 'Aventura / Fantasía / Épico',
                'URLtrailer' => 'https://www.youtube.com/watch?v=r5X-hFf6Bwo'
            ],
            [
                'nombre' => 'Pulp Fiction',
                'imagen' => 'https://m.media-amazon.com/images/M/MV5BYTViYTE3ZGQtNDBlMC00ZTAyLTkyODMtZGRiZDg0MjA2YThkXkEyXkFqcGc@._V1_.jpg',
                'horarios' => '19:15, 21:50, 00:10',
                'sinopsis' => 'The lives of two mob hitmen, a boxer, a gangster’s wife and a pair of diner bandits intertwine in four tales of violence and redemption.',
                'duracion' => '2h 34m',
                'director' => 'Quentin Tarantino',
                'actores' => 'John Travolta, Uma Thurman, Samuel L. Jackson',
                'calificacion' => '8.9',
                'genero' => 'Crimen / Drama',
                'URLtrailer' => 'https://www.youtube.com/watch?v=s7EdQ4FqbhY'
            ],
            [
                'nombre' => 'Fight Club',
                'imagen' => 'https://m.media-amazon.com/images/M/MV5BOTgyOGQ1NDItNGU3Ny00MjU3LTg2YWEtNmEyYjBiMjI1Y2M5XkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg',
                'horarios' => '20:00, 22:30',
                'sinopsis' => 'An insomniac office worker and a devil-may-care soap maker form an underground fight club that evolves into something much more.',
                'duracion' => '2h 19m',
                'director' => 'David Fincher',
                'actores' => 'Brad Pitt, Edward Norton, Helena Bonham Carter',
                'calificacion' => '8.8',
                'genero' => 'Drama / Thriller',
                'URLtrailer' => 'https://www.youtube.com/watch?v=SUXWAEX2jlg'
            ],
            [
                'nombre' => 'Inception',
                'imagen' => 'https://m.media-amazon.com/images/M/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_FMjpg_UX1000_.jpg',
                'horarios' => '18:45, 21:15, 23:50',
                'sinopsis' => 'A skilled thief is offered a chance to have his past crimes forgiven if he can implant another person’s idea into a target’s subconscious.',
                'duracion' => '2h 28m',
                'director' => 'Christopher Nolan',
                'actores' => 'Leonardo DiCaprio, Joseph Gordon-Levitt, Ellen Page',
                'calificacion' => '8.8',
                'genero' => 'Acción / Ciencia ficción / Aventura',
                'URLtrailer' => 'https://www.youtube.com/watch?v=YoHD9XEInc0'
            ],
            [
                'nombre' => 'Interstellar',
                'imagen' => 'https://m.media-amazon.com/images/M/MV5BYzdjMDAxZGItMjI2My00ODA1LTlkNzItOWFjMDU5ZDJlYWY3XkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg',
                'horarios' => '19:00, 21:30',
                'sinopsis' => 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity’s survival.',
                'duracion' => '2h 49m',
                'director' => 'Christopher Nolan',
                'actores' => 'Matthew McConaughey, Anne Hathaway, Jessica Chastain',
                'calificacion' => '8.6',
                'genero' => 'Aventura / Ciencia ficción / Drama',
                'URLtrailer' => 'https://www.youtube.com/watch?v=zSWdZVtXT7E'
            ]
        ];

        foreach($peliculas as $pelicula) {
            echo '<div class="peliculaCard">';
            echo '<img src="' . htmlspecialchars($pelicula['imagen']) . '" alt="' . htmlspecialchars($pelicula['nombre']) . '">';
            
            echo '<div class="details">';
            echo '<h2>' . htmlspecialchars($pelicula['nombre']) . '</h2>';
            echo '<p><strong>Horaris:</strong> ' . htmlspecialchars($pelicula['horarios']) . '</p>';
            echo '<a href="trailer.php?pelicula=' . urlencode($pelicula['nombre']) . '">Veure tràiler</a>';
            echo '<a href="detalles.php?pelicula=' . urlencode($pelicula['nombre']) . '">Veure més informació</a>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>