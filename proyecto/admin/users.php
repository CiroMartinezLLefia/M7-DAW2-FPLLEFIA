<?php
/**
 * GameZone - Gestión de Usuarios (Admin)
 */
require_once __DIR__ . '/../config.php';
initSession();

// Solo administradores pueden gestionar usuarios
if (!isAdmin()) {
    setFlashMessage('error', 'No tienes permisos para gestionar usuarios.');
    redirect('index.php');
}

$pdo = getDBConnection();
$users = [];
$roleFilter = $_GET['role'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;
$totalUsers = 0;

if ($pdo) {
    try {
        $whereClause = '';
        $params = [];
        
        if ($roleFilter) {
            $whereClause = "WHERE role = ?";
            $params[] = $roleFilter;
        }
        
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM users $whereClause");
        $countStmt->execute($params);
        $totalUsers = $countStmt->fetchColumn();
        
        $sql = "SELECT * FROM users $whereClause ORDER BY created_at DESC LIMIT $perPage OFFSET $offset";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $users = $stmt->fetchAll();
        
    } catch (PDOException $e) {
        error_log("Error cargando usuarios: " . $e->getMessage());
    }
}

// Procesar cambio de rol
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $userId = intval($_POST['user_id'] ?? 0);
    $action = $_POST['action'];
    
    if ($userId > 0 && $pdo) {
        try {
            if ($action === 'change_role' && isset($_POST['new_role'])) {
                $newRole = $_POST['new_role'];
                if (in_array($newRole, ['user', 'editor', 'admin'])) {
                    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
                    $stmt->execute([$newRole, $userId]);
                    setFlashMessage('success', 'Rol actualizado correctamente.');
                }
            } elseif ($action === 'toggle_active') {
                $stmt = $pdo->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
                $stmt->execute([$userId]);
                setFlashMessage('success', 'Estado del usuario actualizado.');
            } elseif ($action === 'delete') {
                // No permitir eliminar el propio usuario
                if ($userId != getCurrentUser()['id']) {
                    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                    $stmt->execute([$userId]);
                    setFlashMessage('success', 'Usuario eliminado correctamente.');
                }
            }
        } catch (PDOException $e) {
            error_log("Error modificando usuario: " . $e->getMessage());
            setFlashMessage('error', 'Error al modificar el usuario.');
        }
        redirect('users.php' . ($roleFilter ? '?role=' . $roleFilter : ''));
    }
}

$totalPages = ceil($totalUsers / $perPage);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios — <?= SITE_NAME ?></title>
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
          <li><a href="news.php"><i class="bi bi-newspaper"></i>Noticias</a></li>
          <li><a href="categories.php"><i class="bi bi-tags"></i>Categorías</a></li>
          <li><a href="users.php" class="active"><i class="bi bi-people"></i>Usuarios</a></li>
          <li><a href="comments.php"><i class="bi bi-chat-dots"></i>Comentarios</a></li>
          <li><a href="faqs.php"><i class="bi bi-question-circle"></i>FAQs</a></li>
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
            <h1 class="h3 mb-1">Gestión de Usuarios</h1>
            <p class="text-muted mb-0"><?= $totalUsers ?> usuarios registrados</p>
          </div>
        </div>
        
        <?php $flash = getFlashMessage(); if ($flash): ?>
          <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show">
            <?= e($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>
        
        <!-- Filtros -->
        <div class="d-flex gap-2 mb-4">
          <a href="users.php" class="btn btn-<?= !$roleFilter ? 'primary' : 'outline' ?> btn-sm">Todos</a>
          <a href="users.php?role=admin" class="btn btn-<?= $roleFilter === 'admin' ? 'primary' : 'outline' ?> btn-sm">Admins</a>
          <a href="users.php?role=editor" class="btn btn-<?= $roleFilter === 'editor' ? 'primary' : 'outline' ?> btn-sm">Editores</a>
          <a href="users.php?role=user" class="btn btn-<?= $roleFilter === 'user' ? 'primary' : 'outline' ?> btn-sm">Usuarios</a>
        </div>
        
        <!-- Tabla -->
        <div class="card p-3">
          <table class="data-table">
            <thead>
              <tr>
                <th>Usuario</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Registro</th>
                <th>Último login</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $user): ?>
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <img src="../assets/img/avatars/<?= e($user['avatar'] ?: 'default-avatar.svg') ?>" 
                         class="rounded-circle" width="32" height="32"
                         onerror="this.src='../assets/img/avatars/default-avatar.svg'">
                    <div>
                      <div class="fw-bold"><?= e($user['display_name'] ?: $user['username']) ?></div>
                      <small class="text-muted">@<?= e($user['username']) ?></small>
                    </div>
                  </div>
                </td>
                <td class="text-secondary"><?= e($user['email']) ?></td>
                <td>
                  <form method="POST" class="d-inline">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <input type="hidden" name="action" value="change_role">
                    <select name="new_role" class="form-select form-select-sm" style="width: auto;" 
                            onchange="this.form.submit()" <?= $user['id'] == getCurrentUser()['id'] ? 'disabled' : '' ?>>
                      <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                      <option value="editor" <?= $user['role'] === 'editor' ? 'selected' : '' ?>>Editor</option>
                      <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                  </form>
                </td>
                <td>
                  <?php if ($user['is_active']): ?>
                    <span class="badge badge-published">Activo</span>
                  <?php else: ?>
                    <span class="badge badge-archived">Inactivo</span>
                  <?php endif; ?>
                </td>
                <td class="text-secondary"><?= formatDate($user['created_at']) ?></td>
                <td class="text-secondary"><?= $user['last_login'] ? formatDate($user['last_login']) : 'Nunca' ?></td>
                <td class="actions">
                  <?php if ($user['id'] != getCurrentUser()['id']): ?>
                  <form method="POST" class="d-inline">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <input type="hidden" name="action" value="toggle_active">
                    <button type="submit" class="btn btn-icon btn-secondary" title="<?= $user['is_active'] ? 'Desactivar' : 'Activar' ?>">
                      <i class="bi bi-<?= $user['is_active'] ? 'pause' : 'play' ?>"></i>
                    </button>
                  </form>
                  <form method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?')">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="btn btn-icon btn-danger" title="Eliminar">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        
        <!-- Paginación -->
        <?php if ($totalPages > 1): ?>
        <nav class="mt-4">
          <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $i ?><?= $roleFilter ? '&role=' . $roleFilter : '' ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
          </ul>
        </nav>
        <?php endif; ?>
      </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
