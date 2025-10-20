<?php
session_start();
include_once 'data.php';

if (isset($_GET['clear'])) {
    unset($_SESSION['noticias']);
    header('Location: index.php');
    exit;
}

if (isset($_GET['delete'])) {
    $index = intval($_GET['delete']);
    if (isset($_SESSION['noticias'][$index])) 
    {
        unset($_SESSION['noticias'][$index]);
        $_SESSION['noticias'] = array_values($_SESSION['noticias']);
    }
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['noticias'])) {
    $_SESSION['noticias'] = $noticias;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $date = $_POST['date'];
    $image = $_POST['image'];
    $category = $_POST['category'];

    if (
        !empty(trim($title)) &&
        !empty(trim($content)) &&
        !empty(trim($date)) &&
        !empty(trim($image)) &&
        !empty(trim($category))
    ) {
        $_SESSION['noticias'][] = [
            'title' => $title,
            'content' => $content,
            'date' => $date,
            'image' => $image,
            'category' => $category
        ];
    }

    header('Location: index.php');
    exit;
}

$noticias = $_SESSION['noticias'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mini-wp</title>
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>
    <?php include_once 'inc/header.php'; ?>

    <main>
        <section class="news-grid-display">
            <?php
                foreach ($noticias as $i => $noticia) {
                    echo '<article class="news-item">';
                    echo '<h2>' . htmlspecialchars($noticia['title']) . '</h2>';
                    echo '<img src="' . htmlspecialchars($noticia['image']) . '" alt="">';
                    echo '<p>' . htmlspecialchars($noticia['content']) . '</p>';
                    echo '<p>' . htmlspecialchars($noticia['date']) . '</p>';
                    echo '<p>' . htmlspecialchars($noticia['category']) . '</p>';
                    echo '<a href="index.php?delete=' . $i . '" class="delete-btn" style="color:red;">Eliminar</a>';
                    echo '</article>';
                }
            ?>            
        </section>
    </main>

    <?php include_once 'inc/footer.php'; ?>
</body>
</html>
