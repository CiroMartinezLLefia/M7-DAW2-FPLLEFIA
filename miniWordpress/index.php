<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mini-wp</title>
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>
    <?php
    include_once 'inc/header.php';
    ?>

    <main>
        <section class="news-grid-display">
            <?php
                include_once 'data.php';

                foreach($noticias as $noticia)
                {
                    echo '<article class="news-item>';
                    echo '<h2>' . htmlspecialchars($noticia['title']) . '</h2>';
                    echo '<img src="' . htmlspecialchars($noticia['image']) . '" alt="">';
                    echo '<p>' . htmlspecialchars($noticia['content']) . '</p>';
                    echo '<p>' . htmlspecialchars($noticia['date']) . '</p>';
                    echo '<p>' . htmlspecialchars($noticia['category']) . '</p>';
                    echo '</article>';
                }
            ?>            
        </section>
    </main>

    <?php
    include_once 'inc/footer.php'
    ?>
</body>
</html>