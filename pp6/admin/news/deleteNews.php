<?php
session_start();
require_once '../../config.php';

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
    header('Location: ../../login.php');
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID de notícia no proporcionat.');
} else {
    $news_id = intval($_GET['id']);
}

$stmt = $mysqli->prepare("DELETE FROM noticies WHERE id = ?");
if (!$stmt) {
    die('Error en la preparació de la consulta: ' . $mysqli->error);
}

$stmt->bind_param('i', $_GET['id']);
if ($stmt->execute()) {
    header('Location: adminNews.php');
    exit();
} else {
    die('Error en l\'eliminació de la notícia: ' . $stmt->error);
}
?>