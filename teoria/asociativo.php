<?php
$alumno = [
    'nombre' => 'Juan',
    'apellido' => 'Perez',
    'edad' => 21,
    'curso' => 'DAW2',
    'inteligente' => true
];

echo $alumno;

print_r($alumno);

echo $alumno['nombre'];
echo $alumno['edad'];
echo $alumno['inteligente'];

$alumno['email'] = 'juan.perez@example.com';

echo '<br><br>';
print_r($alumno);

foreach($alumno as $clave => $valor)
{
    echo "<h1>$clave: $valor</h1>";
}
?>