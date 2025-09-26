<?php
// array multidimensional

$alumnos = [
    [
        'nombre' => 'Juan',
        'apellido' => 'Perez',
        'edad' => 21,
        'curso' => 'DAW2',
        'inteligente' => true
    ],
    [
        'nombre' => 'Maria',
        'apellido' => 'Lopez',
        'edad' => 22,
        'curso' => 'DAW1',
        'inteligente' => false
    ],
    [
        'nombre' => 'Pedro',
        'apellido' => 'Garcia',
        'edad' => 20,
        'curso' => 'DAW2',
        'inteligente' => true
    ]
];

print_r($alumnos);

foreach($alumnos as $alumno)
{
    echo "<h1>{$alumno['nombre']} {$alumno['apellido']}</h1>";
    echo "<p>Edad: {$alumno['edad']}</p>";
    echo "<p>Curso: {$alumno['curso']}</p>";
    echo "<p>Inteligente: " . ($alumno['inteligente'] ? 'Sí' : 'No') . "</p>";
    echo "<hr>";
}

?>