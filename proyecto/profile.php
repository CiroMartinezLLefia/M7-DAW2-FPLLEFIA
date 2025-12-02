<?php
/**
 * GameZone - Perfil de Usuario
 * Página para ver y editar el perfil
 */
require_once __DIR__ . '/config.php';
initSession();

// Verificar autenticación
if (!isLoggedIn()) {
    setFlashMessage('error', 'Debes iniciar sesión para ver tu perfil');
    redirect('login.php');
}

$pdo = getDBConnection();
$user = getCurrentUser();
$errors = [];
$success = false;

// Procesar actualización del perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== getCSRFToken()) {
        $errors[] = 'Token de seguridad inválido';
    } else {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'update_profile') {
            $email = trim($_POST['email'] ?? '');
            
            // Validar email
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email inválido';
            }
            
            // Verificar que el email no esté en uso por otro usuario
            if (empty($errors) && $pdo) {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmt->execute([$email, $user['id']]);
                if ($stmt->fetch()) {
                    $errors[] = 'Este email ya está en uso';
                }
            }
            
            // Actualizar perfil
            if (empty($errors) && $pdo) {
                $stmt = $pdo->prepare("UPDATE users SET email = ?, updated_at = NOW() WHERE id = ?");
                if ($stmt->execute([$email, $user['id']])) {
                    setFlashMessage('success', 'Perfil actualizado correctamente');
                    redirect('profile.php');
                } else {
                    $errors[] = 'Error al actualizar el perfil';
                }
            }
        } elseif ($action === 'change_password') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validar contraseña actual
            if (!password_verify($currentPassword, $user['password'])) {
                $errors[] = 'La contraseña actual es incorrecta';
            }
            
            // Validar nueva contraseña
            if (strlen($newPassword) < 6) {
                $errors[] = 'La nueva contraseña debe tener al menos 6 caracteres';
            }
            
            if ($newPassword !== $confirmPassword) {
                $errors[] = 'Las contraseñas no coinciden';
            }
            
            // Actualizar contraseña
            if (empty($errors) && $pdo) {
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
                if ($stmt->execute([$hashedPassword, $user['id']])) {
                    setFlashMessage('success', 'Contraseña actualizada correctamente');
                    redirect('profile.php');
                } else {
                    $errors[] = 'Error al actualizar la contraseña';
                }
            }
        }
    }
}

// Obtener estadísticas del usuario
$stats = [
    'comments' => 0,
    'member_since' => $user['created_at'] ?? date('Y-m-d')
];

if ($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM comments WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        $stats['comments'] = $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error obteniendo stats: " . $e->getMessage());
    }
}

// Obtener comentarios recientes del usuario
$recentComments = [];
if ($pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT c.*, n.title as news_title, n.slug as news_slug
            FROM comments c
            LEFT JOIN news n ON c.news_id = n.id
            WHERE c.user_id = ?
            ORDER BY c.created_at DESC
            LIMIT 5
        ");
        $stmt->execute([$user['id']]);
        $recentComments = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error obteniendo comentarios: " . $e->getMessage());
    }
}

$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil — <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    
    <main class="container py-5">
        <div class="row">
            <!-- Sidebar con info del usuario -->
            <div class="col-lg-4 mb-4">
                <div class="card bg-dark border-secondary">
                    <div class="card-body text-center">
                        <img src="assets/img/avatar-placeholder.svg" 
                             alt="Avatar" 
                             class="rounded-circle mb-3" 
                             width="120" height="120">
                        <h4><?= e($user['username']) ?></h4>
                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'warning' : ($user['role'] === 'editor' ? 'info' : 'secondary') ?> mb-3">
                            <i class="bi bi-<?= $user['role'] === 'admin' ? 'shield-check' : ($user['role'] === 'editor' ? 'pen' : 'person') ?> me-1"></i>
                            <?= ucfirst(e($user['role'])) ?>
                        </span>
                        
                        <div class="border-top border-secondary pt-3 mt-3">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h5 class="mb-0"><?= $stats['comments'] ?></h5>
                                    <small class="text-muted">Comentarios</small>
                                </div>
                                <div class="col-6">
                                    <h6 class="mb-0"><?= formatDate($stats['member_since'], 'M Y') ?></h6>
                                    <small class="text-muted">Miembro desde</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if (canEdit()): ?>
                <div class="card bg-dark border-secondary mt-3">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="bi bi-speedometer2 text-primary me-2"></i>Acceso rápido
                        </h6>
                        <div class="d-grid gap-2">
                            <a href="admin/" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-grid me-1"></i>Panel Admin
                            </a>
                            <a href="admin/news-edit.php" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-plus me-1"></i>Nueva Noticia
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Contenido principal -->
            <div class="col-lg-8">
                <?php if ($flash): ?>
                <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
                    <?= e($flash['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-tab">
                            <i class="bi bi-person me-1"></i>Perfil
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#security-tab">
                            <i class="bi bi-shield-lock me-1"></i>Seguridad
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activity-tab">
                            <i class="bi bi-activity me-1"></i>Actividad
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <!-- Tab: Perfil -->
                    <div class="tab-pane fade show active" id="profile-tab">
                        <div class="card bg-dark border-secondary">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-person-gear me-2"></i>Información del perfil</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="profile.php">
                                    <input type="hidden" name="csrf_token" value="<?= getCSRFToken() ?>">
                                    <input type="hidden" name="action" value="update_profile">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Nombre de usuario</label>
                                        <input type="text" class="form-control bg-dark text-light border-secondary" 
                                               value="<?= e($user['username']) ?>" disabled>
                                        <small class="text-muted">El nombre de usuario no se puede cambiar</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control bg-dark text-light border-secondary" 
                                               id="email" name="email" value="<?= e($user['email']) ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Rol</label>
                                        <input type="text" class="form-control bg-dark text-light border-secondary" 
                                               value="<?= ucfirst(e($user['role'])) ?>" disabled>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg me-1"></i>Guardar cambios
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tab: Seguridad -->
                    <div class="tab-pane fade" id="security-tab">
                        <div class="card bg-dark border-secondary">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-key me-2"></i>Cambiar contraseña</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="profile.php">
                                    <input type="hidden" name="csrf_token" value="<?= getCSRFToken() ?>">
                                    <input type="hidden" name="action" value="change_password">
                                    
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Contraseña actual</label>
                                        <input type="password" class="form-control bg-dark text-light border-secondary" 
                                               id="current_password" name="current_password" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">Nueva contraseña</label>
                                        <input type="password" class="form-control bg-dark text-light border-secondary" 
                                               id="new_password" name="new_password" required minlength="6">
                                        <small class="text-muted">Mínimo 6 caracteres</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirmar contraseña</label>
                                        <input type="password" class="form-control bg-dark text-light border-secondary" 
                                               id="confirm_password" name="confirm_password" required>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-shield-check me-1"></i>Cambiar contraseña
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tab: Actividad -->
                    <div class="tab-pane fade" id="activity-tab">
                        <div class="card bg-dark border-secondary">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-chat-dots me-2"></i>Comentarios recientes</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($recentComments)): ?>
                                <p class="text-muted text-center py-4">
                                    <i class="bi bi-chat-square d-block" style="font-size: 2rem;"></i>
                                    Aún no has hecho ningún comentario
                                </p>
                                <?php else: ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($recentComments as $comment): ?>
                                    <div class="list-group-item bg-transparent border-secondary">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <p class="mb-1"><?= e(truncateText($comment['content'], 150)) ?></p>
                                                <?php if ($comment['news_title']): ?>
                                                <small>
                                                    <i class="bi bi-newspaper me-1"></i>En: 
                                                    <a href="news-detail.php?slug=<?= e($comment['news_slug']) ?>" class="text-primary">
                                                        <?= e(truncateText($comment['news_title'], 50)) ?>
                                                    </a>
                                                </small>
                                                <?php endif; ?>
                                            </div>
                                            <small class="text-muted"><?= timeAgo($comment['created_at']) ?></small>
                                        </div>
                                        <span class="badge bg-<?= $comment['status'] === 'approved' ? 'success' : ($comment['status'] === 'pending' ? 'warning' : 'danger') ?> mt-2">
                                            <?= $comment['status'] === 'approved' ? 'Aprobado' : ($comment['status'] === 'pending' ? 'Pendiente' : 'Rechazado') ?>
                                        </span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include __DIR__ . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
