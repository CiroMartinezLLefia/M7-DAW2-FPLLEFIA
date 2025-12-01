<?php 
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
    header('Location: ../../login.php');
    exit();
}

$result = $mysqli->query("SELECT * FROM noticies ORDER BY data_publicacio DESC");
$news = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEWS PANEL</title>
</head>
<body>
    <h1>GESTIÓ DE NOTÍCIES</h1>
    <a href="createNews.php">Crear nueva noticia</a>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Subtitulo</th>
            <th>Contenido</th>
            <th>Fecha de Publicación</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($news as $new): ?>
        <tr>
            <td><?php echo htmlspecialchars($new['id']); ?></td>
            <td><?php echo htmlspecialchars($new['titol']); ?></td>
            <td><?php echo htmlspecialchars($new['subtitol']); ?></td>
            <td><?php echo htmlspecialchars($new['contingut']); ?></td>
            <td><?php echo htmlspecialchars($new['data_publicacio']); ?></td>
            <td>
                <a href="editNews.php?id=<?php echo $new['id']; ?>">Editar</a> |
                <a href="deleteNews.php?id=<?php echo $new['id']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar esta noticia?');">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>