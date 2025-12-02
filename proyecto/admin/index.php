<?php
/**
 * GameZone - Panel de Administración
 */
require_once __DIR__ . '/../config.php';
initSession();

// Verificar permisos de administrador/editor
if (!canEdit()) {
    setFlashMessage('error', 'No tienes permisos para acceder al panel de administración.');
    redirect('../login.php');
}

$pdo = getDBConnection();
$stats = [
    'news' => 0,
    'users' => 0,
    'comments' => 0,
    'views' => 0
];
$recentNews = [];
$recentUsers = [];

if ($pdo) {
    try {
        // Estadísticas
        $stats['news'] = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
        $stats['users'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $stats['comments'] = $pdo->query("SELECT COUNT(*) FROM comments")->fetchColumn();
        $stats['views'] = $pdo->query("SELECT SUM(views) FROM news")->fetchColumn() ?: 0;
        
        // Noticias recientes
        $recentNews = $pdo->query("
            SELECT n.*, u.display_name as author_name
            FROM news n
            LEFT JOIN users u ON n.author_id = u.id
            ORDER BY n.created_at DESC
            LIMIT 5
        ")->fetchAll();
        
        // Usuarios recientes
        $recentUsers = $pdo->query("
            SELECT * FROM users ORDER BY created_at DESC LIMIT 5
        ")->fetchAll();
        
    } catch (PDOException $e) {
        error_log("Error en dashboard: " . $e->getMessage());
    }
}

$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración — <?= SITE_NAME ?></title>
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
          <li>
            <a href="index.php" class="active">
              <i class="bi bi-speedometer2"></i>
              Dashboard
            </a>
          </li>
          <li>
            <a href="news.php">
              <i class="bi bi-newspaper"></i>
              Noticias
            </a>
          </li>
          <li>
            <a href="categories.php">
              <i class="bi bi-tags"></i>
              Categorías
            </a>
          </li>
          <?php if (isAdmin()): ?>
          <li>
            <a href="users.php">
              <i class="bi bi-people"></i>
              Usuarios
            </a>
          </li>
          <?php endif; ?>
          <li>
            <a href="comments.php">
              <i class="bi bi-chat-dots"></i>
              Comentarios
            </a>
          </li>
          <li>
            <a href="faqs.php">
              <i class="bi bi-question-circle"></i>
              FAQs
            </a>
          </li>
          <li>
            <a href="messages.php">
              <i class="bi bi-envelope"></i>
              Mensajes
            </a>
          </li>
        </ul>
        
        <hr class="border-subtle">
        
        <ul class="admin-nav">
          <li>
            <a href="../index.php">
              <i class="bi bi-box-arrow-left"></i>
              Volver al sitio
            </a>
          </li>
          <li>
            <a href="../logout.php" class="text-danger">
              <i class="bi bi-power"></i>
              Cerrar Sesión
            </a>
          </li>
        </ul>
      </aside>
      
      <!-- Contenido principal -->
      <main class="admin-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h1 class="h3 mb-1">Dashboard</h1>
            <p class="text-muted mb-0">Bienvenido, <?= e($currentUser['display_name']) ?></p>
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
        
        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
          <div class="col-md-3">
            <div class="stat-card">
              <div class="stat-icon primary"><i class="bi bi-newspaper"></i></div>
              <div class="stat-value"><?= number_format($stats['news']) ?></div>
              <div class="stat-label">Noticias</div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-card">
              <div class="stat-icon success"><i class="bi bi-people"></i></div>
              <div class="stat-value"><?= number_format($stats['users']) ?></div>
              <div class="stat-label">Usuarios</div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-card">
              <div class="stat-icon warning"><i class="bi bi-chat-dots"></i></div>
              <div class="stat-value"><?= number_format($stats['comments']) ?></div>
              <div class="stat-label">Comentarios</div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-card">
              <div class="stat-icon danger"><i class="bi bi-eye"></i></div>
              <div class="stat-value"><?= number_format($stats['views']) ?></div>
              <div class="stat-label">Visitas Totales</div>
            </div>
          </div>
        </div>
        
        <!-- Tablas de datos -->
        <div class="row g-4">
          <!-- Noticias recientes -->
          <div class="col-lg-8">
            <div class="card p-3">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Noticias Recientes</h5>
                <a href="news.php" class="btn btn-outline btn-sm">Ver todas</a>
              </div>
              
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($recentNews)): ?>
                    <?php foreach ($recentNews as $news): ?>
                    <tr>
                      <td>
                        <a href="../news-detail.php?slug=<?= e($news['slug']) ?>" class="text-light" target="_blank">
                          <?= e(truncateText($news['title'], 40)) ?>
                        </a>
                      </td>
                      <td class="text-secondary"><?= e($news['author_name']) ?></td>
                      <td>
                        <span class="badge badge-<?= $news['status'] ?>"><?= ucfirst($news['status']) ?></span>
                      </td>
                      <td class="text-secondary"><?= formatDate($news['created_at']) ?></td>
                      <td class="actions">
                        <a href="news-edit.php?id=<?= $news['id'] ?>" class="btn btn-icon btn-secondary" title="Editar">
                          <i class="bi bi-pencil"></i>
                        </a>
                        <a href="news-delete.php?id=<?= $news['id'] ?>" class="btn btn-icon btn-danger" title="Eliminar"
                           onclick="return confirm('¿Seguro que deseas eliminar esta noticia?')">
                          <i class="bi bi-trash"></i>
                        </a>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" class="text-center text-muted py-4">No hay noticias aún</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
          
          <!-- Usuarios recientes -->
          <div class="col-lg-4">
            <div class="card p-3">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Usuarios Recientes</h5>
                <?php if (isAdmin()): ?>
                <a href="users.php" class="btn btn-outline btn-sm">Ver todos</a>
                <?php endif; ?>
              </div>
              
              <div class="list-group list-group-flush">
                <?php if (!empty($recentUsers)): ?>
                  <?php foreach ($recentUsers as $user): ?>
                  <div class="list-group-item bg-transparent border-subtle d-flex align-items-center gap-3 px-0">
                    <img src="../assets/img/avatars/<?= e($user['avatar'] ?: 'default-avatar.svg') ?>" 
                         alt="<?= e($user['username']) ?>"
                         class="rounded-circle"
                         width="40" height="40"
                         onerror="this.src='../assets/img/avatars/default-avatar.svg'">
                    <div class="flex-grow-1">
                      <div class="fw-bold"><?= e($user['display_name'] ?: $user['username']) ?></div>
                      <small class="text-muted"><?= e($user['email']) ?></small>
                    </div>
                    <span class="badge badge-<?= $user['role'] ?>"><?= ucfirst($user['role']) ?></span>
                  </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p class="text-muted text-center py-3">No hay usuarios aún</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
