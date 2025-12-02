<?php
/**
 * GameZone - Página de Registro
 */
require_once __DIR__ . '/config.php';
initSession();

// Si ya está logueado, redirigir
if (isLoggedIn()) {
    redirect('index.php');
}

$errors = [];
$username = '';
$email = '';
$display_name = '';

// Procesar formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $display_name = trim($_POST['display_name'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    
    // Validaciones
    if (empty($username)) {
        $errors['username'] = 'El nombre de usuario es obligatorio.';
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $errors['username'] = 'El nombre de usuario debe tener entre 3 y 50 caracteres.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors['username'] = 'El nombre de usuario solo puede contener letras, números y guiones bajos.';
    }
    
    if (empty($email)) {
        $errors['email'] = 'El email es obligatorio.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'El email no es válido.';
    }
    
    if (empty($password)) {
        $errors['password'] = 'La contraseña es obligatoria.';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'La contraseña debe tener al menos 6 caracteres.';
    }
    
    if ($password !== $password_confirm) {
        $errors['password_confirm'] = 'Las contraseñas no coinciden.';
    }
    
    // Si no hay errores de validación, intentar registrar
    if (empty($errors)) {
        $pdo = getDBConnection();
        
        if ($pdo) {
            try {
                // Verificar si el usuario o email ya existen
                $checkStmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                $checkStmt->execute([$username, $email]);
                
                if ($checkStmt->fetch()) {
                    $errors['general'] = 'El nombre de usuario o email ya está en uso.';
                } else {
                    // Crear el usuario
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    
                    $insertStmt = $pdo->prepare("
                        INSERT INTO users (username, email, password, role, display_name, created_at) 
                        VALUES (?, ?, ?, 'user', ?, NOW())
                    ");
                    
                    $insertStmt->execute([
                        $username,
                        $email,
                        $hashedPassword,
                        $display_name ?: $username
                    ]);
                    
                    setFlashMessage('success', '¡Registro exitoso! Ya puedes iniciar sesión.');
                    redirect('login.php');
                }
            } catch (PDOException $e) {
                error_log("Error de registro: " . $e->getMessage());
                $errors['general'] = 'Error al crear la cuenta. Inténtalo de nuevo.';
            }
        } else {
            $errors['general'] = 'Error de conexión a la base de datos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta — <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    
    <main class="auth-container">
        <div class="auth-card animate-slide-up" style="max-width: 480px;">
            <div class="auth-header">
                <div class="logo mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">GZ</div>
                <h2>Crear Cuenta</h2>
                <p>Únete a la comunidad de <?= SITE_NAME ?></p>
            </div>
            
            <?php if (!empty($errors['general'])): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle"></i>
                    <?= e($errors['general']) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="register.php">
                <div class="form-group mb-3">
                    <label class="form-label" for="username">Nombre de usuario *</label>
                    <input 
                        type="text" 
                        class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" 
                        id="username" 
                        name="username" 
                        value="<?= e($username) ?>"
                        placeholder="gamer123"
                        required
                        autofocus
                    >
                    <?php if (isset($errors['username'])): ?>
                        <div class="invalid-feedback d-block"><?= e($errors['username']) ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label" for="display_name">Nombre para mostrar</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="display_name" 
                        name="display_name" 
                        value="<?= e($display_name) ?>"
                        placeholder="Tu nombre público (opcional)"
                    >
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label" for="email">Email *</label>
                    <input 
                        type="email" 
                        class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                        id="email" 
                        name="email" 
                        value="<?= e($email) ?>"
                        placeholder="tu@email.com"
                        required
                    >
                    <?php if (isset($errors['email'])): ?>
                        <div class="invalid-feedback d-block"><?= e($errors['email']) ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label" for="password">Contraseña *</label>
                            <input 
                                type="password" 
                                class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                id="password" 
                                name="password" 
                                placeholder="Mínimo 6 caracteres"
                                required
                            >
                            <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback d-block"><?= e($errors['password']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label" for="password_confirm">Repetir contraseña *</label>
                            <input 
                                type="password" 
                                class="form-control <?= isset($errors['password_confirm']) ? 'is-invalid' : '' ?>" 
                                id="password_confirm" 
                                name="password_confirm" 
                                placeholder="Repite la contraseña"
                                required
                            >
                            <?php if (isset($errors['password_confirm'])): ?>
                                <div class="invalid-feedback d-block"><?= e($errors['password_confirm']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                    <label class="form-check-label text-secondary" for="terms">
                        Acepto los <a href="#">términos de servicio</a> y la <a href="#">política de privacidad</a>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-person-plus me-2"></i>
                    Crear Cuenta
                </button>
            </form>
            
            <div class="auth-divider">o</div>
            
            <div class="auth-footer">
                ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
            </div>
        </div>
    </main>
    
    <?php include __DIR__ . '/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
