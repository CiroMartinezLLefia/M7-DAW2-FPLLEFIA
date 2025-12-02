<?php
/**
 * GameZone - Eliminar Noticia (Admin)
 */
require_once __DIR__ . '/../config.php';
initSession();

if (!canEdit()) {
    redirect('../login.php');
}

$newsId = intval($_GET['id'] ?? 0);

if ($newsId <= 0) {
    setFlashMessage('error', 'ID de noticia no válido.');
    redirect('news.php');
}

$pdo = getDBConnection();

if ($pdo) {
    try {
        // Verificar que la noticia existe
        $stmt = $pdo->prepare("SELECT id, title FROM news WHERE id = ?");
        $stmt->execute([$newsId]);
        $news = $stmt->fetch();
        
        if (!$news) {
            setFlashMessage('error', 'Noticia no encontrada.');
            redirect('news.php');
        }
        
        // Eliminar la noticia
        $deleteStmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
        $deleteStmt->execute([$newsId]);
        
        setFlashMessage('success', 'Noticia "' . $news['title'] . '" eliminada correctamente.');
        
    } catch (PDOException $e) {
        error_log("Error eliminando noticia: " . $e->getMessage());
        setFlashMessage('error', 'Error al eliminar la noticia.');
    }
}

redirect('news.php');
