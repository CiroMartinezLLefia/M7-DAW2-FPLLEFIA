<?php
/**
 * GameZone - Gestión de Noticias (Admin)
 */
require_once __DIR__ . '/../config.php';
initSession();

if (!canEdit()) {
    redirect('../login.php');
}

$pdo = getDBConnection();
$news = [];
$statusFilter = $_GET['status'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 15;
$offset = ($page - 1) * $perPage;
$totalNews = 0;

if ($pdo) {
    try {
        // Construir query
        $whereClause = '';
        $params = [];
        
        if ($statusFilter) {
            $whereClause = "WHERE n.status = ?";
            $params[] = $statusFilter;
        }
        
        // Contar total
        $countSql = "SELECT COUNT(*) FROM news n $whereClause";
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute($params);
        $totalNews = $countStmt->fetchColumn();
        
        // Obtener noticias
        $sql = "
            SELECT n.*, c.name as category_name, u.display_name as author_name
            FROM news n
            LEFT JOIN categories c ON n.category_id = c.id
            LEFT JOIN users u ON n.author_id = u.id
            $whereClause
            ORDER BY n.created_at DESC
            LIMIT $perPage OFFSET $offset
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $news = $stmt->fetchAll();
        
    } catch (PDOException $e) {
        error_log("Error en listado de noticias: " . $e->getMessage());
    }
}

$totalPages = ceil($totalNews / $perPage);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Noticias — <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="admin-layout">
      <!-- Sidebar -->
      <aside class="admin-sidebar">
        <a href="../index.php" class="navbar-brand mb-4 d-block">
          <div class="d-flex align-items-center gap-2">
            <div class="logo" style="width: 40px; height: 40px; font-size: 1rem;">GZ</div>
            <div>
              <span class="brand-name" style="font-size: 1.1rem;"><?= SITE_NAME ?></span>
              <small class="d-block text-muted" style="font-size: 0.7rem;">Panel Admin</small>
            </div>
          </div>
        </a>
        
        <ul class="admin-nav">
          <li><a href="index.php"><i class="bi bi-speedometer2"></i>Dashboard</a></li>
          <li><a href="news.php" class="active"><i class="bi bi-newspaper"></i>Noticias</a></li>
          <li><a href="categories.php"><i class="bi bi-tags"></i>Categorías</a></li>
          <?php if (isAdmin()): ?>
          <li><a href="users.php"><i class="bi bi-people"></i>Usuarios</a></li>
          <?php endif; ?>
          <li><a href="comments.php"><i class="bi bi-chat-dots"></i>Comentarios</a></li>
          <li><a href="faqs.php"><i class="bi bi-question-circle"></i>FAQs</a></li>
          <li><a href="messages.php"><i class="bi bi-envelope"></i>Mensajes</a></li>
        </ul>
        
        <hr class="border-subtle">
        
        <ul class="admin-nav">
          <li><a href="../index.php"><i class="bi bi-box-arrow-left"></i>Volver al sitio</a></li>
          <li><a href="../logout.php" class="text-danger"><i class="bi bi-power"></i>Cerrar Sesión</a></li>
        </ul>
      </aside>
      
      <!-- Contenido -->
      <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h1 class="h3 mb-1">Gestión de Noticias</h1>
            <p class="text-muted mb-0"><?= $totalNews ?> noticias en total</p>
          </div>
          <a href="news-edit.php" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Nueva Noticia
          </a>
        </div>
        
        <!-- Flash messages -->
        <?php $flash = getFlashMessage(); if ($flash): ?>
          <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show">
            <?= e($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>
        
        <!-- Filtros -->
        <div class="d-flex gap-2 mb-4">
          <a href="news.php" class="btn btn-<?= !$statusFilter ? 'primary' : 'outline' ?> btn-sm">Todas</a>
          <a href="news.php?status=published" class="btn btn-<?= $statusFilter === 'published' ? 'primary' : 'outline' ?> btn-sm">Publicadas</a>
          <a href="news.php?status=draft" class="btn btn-<?= $statusFilter === 'draft' ? 'primary' : 'outline' ?> btn-sm">Borradores</a>
          <a href="news.php?status=archived" class="btn btn-<?= $statusFilter === 'archived' ? 'primary' : 'outline' ?> btn-sm">Archivadas</a>
        </div>
        
        <!-- Tabla de noticias -->
        <div class="card p-3">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Categoría</th>
                <th>Autor</th>
                <th>Estado</th>
                <th>Vistas</th>
                <th>Fecha</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($news)): ?>
                <?php foreach ($news as $item): ?>
                <tr>
                  <td><?= $item['id'] ?></td>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <?php if ($item['is_featured']): ?>
                        <i class="bi bi-star-fill text-warning" title="Destacada"></i>
                      <?php endif; ?>
                      <a href="../news-detail.php?slug=<?= e($item['slug']) ?>" class="text-light" target="_blank">
                        <?= e(truncateText($item['title'], 50)) ?>
                      </a>
                    </div>
                  </td>
                  <td class="text-secondary"><?= e($item['category_name'] ?: 'Sin categoría') ?></td>
                  <td class="text-secondary"><?= e($item['author_name']) ?></td>
                  <td><span class="badge badge-<?= $item['status'] ?>"><?= ucfirst($item['status']) ?></span></td>
                  <td class="text-secondary"><?= number_format($item['views']) ?></td>
                  <td class="text-secondary"><?= formatDate($item['created_at']) ?></td>
                  <td class="actions">
                    <a href="news-edit.php?id=<?= $item['id'] ?>" class="btn btn-icon btn-secondary" title="Editar">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <a href="news-delete.php?id=<?= $item['id'] ?>" class="btn btn-icon btn-danger" title="Eliminar"
                       onclick="return confirm('¿Seguro que deseas eliminar esta noticia?')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="8" class="text-center text-muted py-4">
                    No hay noticias <?= $statusFilter ? 'con este estado' : '' ?>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        
        <!-- Paginación -->
        <?php if ($totalPages > 1): ?>
        <nav aria-label="Paginación" class="mt-4">
          <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $page - 1 ?><?= $statusFilter ? '&status=' . e($statusFilter) : '' ?>">
                <i class="bi bi-chevron-left"></i>
              </a>
            </li>
            <?php endif; ?>
            
            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $i ?><?= $statusFilter ? '&status=' . e($statusFilter) : '' ?>">
                <?= $i ?>
              </a>
            </li>
            <?php endfor; ?>
            
            <?php if ($page < $totalPages): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $page + 1 ?><?= $statusFilter ? '&status=' . e($statusFilter) : '' ?>">
                <i class="bi bi-chevron-right"></i>
              </a>
            </li>
            <?php endif; ?>
          </ul>
        </nav>
        <?php endif; ?>
      </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
