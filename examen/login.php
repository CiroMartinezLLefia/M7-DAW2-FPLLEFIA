<?php
session_start();
require_once 'config.php';

// Si ya está logueado, redirigir según rol
if (!empty($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin/dashboard.php');
        exit;
    } else {
        header('Location: alumne/dashboard.php');
        exit;
    }
}

$error = '';
// Procesar formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (filter_var($email, FILTER_VALIDATE_EMAIL) && isset($mysqli)) {
        $stmt = $mysqli->prepare("SELECT id, nom, email, password, rol FROM usuaris WHERE email = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param('s', $email);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $user = $result ? $result->fetch_assoc() : null;

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nom'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['role'] = $user['rol'];

                    if ($_SESSION['role'] === 'admin') {
                        header('Location: admin/dashboard.php');
                    } else {
                        header('Location: alumne/dashboard.php');
                    }
                    exit;
                }
            }
            $stmt->close();
        }
    }
    $error = 'Error';
}

require_once 'header.php';
?>

<section class="auth-section">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <i class="fas fa-sign-in-alt"></i>
                <h1>Iniciar Sessió</h1>
                <p>Accedeix al teu compte de MusicSchool Academy</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="auth-error" style="color:#a94442;background:#f2dede;padding:10px;border-radius:4px;margin-bottom:12px;">
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
            
            <form action="login.php" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Correu electrònic
                    </label>
                    <input type="email" id="email" name="email" placeholder="exemple@email.com" required>
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Contrasenya
                    </label>
                    <input type="password" id="password" name="password" placeholder="La teva contrasenya" required>
                </div>
                
                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span>Recorda'm</span>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sessió
                </button>
            </form>
            
            <div class="auth-footer">
                <p>No tens compte? <a href="register.php">Registra't aquí</a></p>
            </div>
        </div>
        
        <div class="auth-image">
            <div class="auth-image-content">
                <i class="fas fa-music"></i>
                <h2>Benvingut de nou!</h2>
                <p>Accedeix per gestionar les teves classes i consultar els teus horaris.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'footer.php'; ?>
