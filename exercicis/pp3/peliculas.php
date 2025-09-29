<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peliculas</title>
</head>
<body>
    <?php
        $peliculas = [
            [
                'id' => 0, 
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
                'id' => 1, 
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
                'id' => 2, 
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
                'id' => 3, 
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
                'id' => 4, 
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
                'id' => 5, 
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
                'id' => 6, 
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
                'id' => 7, 
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
                'id' => 8, 
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
                'id' => 9, 
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
    ?>
</body>
</html>