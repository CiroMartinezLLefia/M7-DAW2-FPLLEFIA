<?php
$dies = ['Dilluns', 'Dimarts', 'Dimecres', 'Dijous', 'Divendres', 'Dissabte', 'Diumenge'];

echo $dies[0];
echo $dies[2];
echo $dies[4];

$dies[] = 'Dissabte';
array_push($dies, 'Diumenge');
array_pop($dies);

foreach($dies as $dia)
{
    echo "<p>$dia</p>";
}

?>