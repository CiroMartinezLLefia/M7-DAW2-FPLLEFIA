<?php
/**
 * GameZone - Página de Login
 */
require_once __DIR__ . '/config.php';
initSession();

// Si ya está logueado, redirigir
if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
$email = '';

// Procesar formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Por favor, completa todos los campos.';
    } else {
        $pdo = getDBConnection();
        
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("SELECT id, username, email, password, role, display_name, avatar, is_active FROM users WHERE email = ? LIMIT 1");
                $stmt->execute([$email]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($password, $user['password'])) {
                    if (!$user['is_active']) {
                        $error = 'Tu cuenta ha sido desactivada. Contacta con el administrador.';
                    } else {
                        // Login exitoso - crear sesión
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['role'] = $user['role'];
                        $_SESSION['display_name'] = $user['display_name'] ?: $user['username'];
                        $_SESSION['avatar'] = $user['avatar'];
                        
                        // Actualizar último login
                        $updateStmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                        $updateStmt->execute([$user['id']]);
                        
                        setFlashMessage('success', '¡Bienvenido de nuevo, ' . e($user['display_name'] ?: $user['username']) . '!');
                        
                        // Redirigir según el rol
                        if ($user['role'] === 'admin') {
                            redirect('admin/index.php');
                        } else {
                            redirect('index.php');
                        }
                    }
                } else {
                    $error = 'Email o contraseña incorrectos.';
                }
            } catch (PDOException $e) {
                error_log("Error de login: " . $e->getMessage());
                $error = 'Error al procesar el login. Inténtalo de nuevo.';
            }
        } else {
            $error = 'Error de conexión a la base de datos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    
    <main class="auth-container">
        <div class="auth-card animate-slide-up">
            <div class="auth-header">
                <div class="logo mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">GZ</div>
                <h2>Iniciar Sesión</h2>
                <p>Accede a tu cuenta de <?= SITE_NAME ?></p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle"></i>
                    <?= e($error) ?>
                </div>
            <?php endif; ?>
            
            <?php 
            $flash = getFlashMessage();
            if ($flash): 
            ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= e($flash['message']) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="login.php">
                <div class="form-group mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input 
                        type="email" 
                        class="form-control" 
                        id="email" 
                        name="email" 
                        value="<?= e($email) ?>"
                        placeholder="tu@email.com"
                        required
                        autofocus
                    >
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label" for="password">Contraseña</label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password" 
                        name="password" 
                        placeholder="Tu contraseña"
                        required
                    >
                </div>
                
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label text-secondary" for="remember">
                        Recordar sesión
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Iniciar Sesión
                </button>
            </form>
            
            <div class="auth-divider">o</div>
            
            <div class="auth-footer">
                ¿No tienes cuenta? <a href="register.php">Regístrate gratis</a>
            </div>
        </div>
    </main>
    
    <?php include __DIR__ . '/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
