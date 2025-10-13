<?php
include_once 'data.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $title = $_POST['title'];
    $content = $_POST['title'];
    $date = $_POST['date'];
    $image = $_POST['image'];
    $category = $_POST['category'];

    if (isset($title) && !empty(trim($title)) && isset($content) && !empty(trim($content)) && isset($date) && !empty(trim($date)) && isset($image) && !empty(trim($image)) && isset($category) && !empty(trim($category)))
    {
        array_push($noticias, [
            'title' => $title, 
            'content' => $content, 
            'date' => $date, 
            'image' => $image, 
            'category' => $category
        ]);

        echo "Noticia añadida";
        echo '<p> style="color:green>Noticia añadida</p>';
    }
    else
    {
        echo '<p style="color:red>Todos los campos son obligatorios</p>';
    }        
}
else
{
    echo '<p style="color:red>Error: Formulario no se ha enviado</p>';
}
?>

<form method="POST" action="">
    <label for="title">Titulo: </label>
    <input type="text" id="title" name="title" required>

    <label for="content">Contenido: </label>
    <input type="text" id="content" name="content" required>
    
    <label for="date">Fecha: </label>
    <input type="text" id="date" name="date" required>

    <label for="image">Imagen: </label>
    <input type="text" id="image" name="image" required>

    <label for="category">Categoria: </label>
    <input type="text" id="category" name="category" required>

    <input type="submit" value="">
</form>