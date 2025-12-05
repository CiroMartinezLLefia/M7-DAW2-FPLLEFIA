<?php
session_start();
require_once 'config.php';

// Si ya está logueado, redirigir a su puta casa
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
$success = '';

// Procesar formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $cognoms = trim($_POST['cognoms'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validaciones
    if (empty($nom) || empty($cognoms) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Tots els camps són obligatoris';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El correu electrònic no és vàlid';
    } elseif (strlen($password) < 6) {
        $error = 'La contrasenya ha de tenir mínim 6 caràcters';
    } elseif ($password !== $confirm_password) {
        $error = 'Les contrasenyes no coincideixen';
    } else {
        // Verificar si el email ya existe
        $stmt = $mysqli->prepare("SELECT id FROM usuaris WHERE email = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = 'Aquest correu electrònic ja està registrat';
            } else {
                // Insertar nuevo usuario
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $rol = 'alumne';
                
                $stmt_insert = $mysqli->prepare("INSERT INTO usuaris (nom, cognoms, email, password, rol) VALUES (?, ?, ?, ?, ?)");
                if ($stmt_insert) {
                    $stmt_insert->bind_param('sssss', $nom, $cognoms, $email, $hashed_password, $rol);
                    if ($stmt_insert->execute()) {
                        $success = 'Registre completat correctament! Ara pots iniciar sessió.';
                    } else {
                        $error = 'Error al registrar l\'usuari';
                    }
                    $stmt_insert->close();
                } else {
                    $error = 'Error al processar el registre';
                }
            }
            $stmt->close();
        } else {
            $error = 'Error de connexió';
        }
    }
}

require_once 'header.php';
?>

<section class="auth-section">
    <div class="auth-container">
        <div class="auth-card auth-card-wide">
            <div class="auth-header">
                <i class="fas fa-user-plus"></i>
                <h1>Registrar-se</h1>
                <p>Crea el teu compte a MusicSchool Academy</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="auth-error" style="color:#a94442;background:#f2dede;padding:10px;border-radius:4px;margin-bottom:12px;">
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="auth-success" style="color:#3c763d;background:#dff0d8;padding:10px;border-radius:4px;margin-bottom:12px;">
                    <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
            
            <form action="register.php" method="POST" class="auth-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nom">
                            <i class="fas fa-user"></i> Nom *
                        </label>
                        <input type="text" id="nom" name="nom" placeholder="El teu nom" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="cognoms">
                            <i class="fas fa-user"></i> Cognoms *
                        </label>
                        <input type="text" id="cognoms" name="cognoms" placeholder="Els teus cognoms" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Correu electrònic *
                    </label>
                    <input type="email" id="email" name="email" placeholder="exemple@email.com" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i> Contrasenya *
                        </label>
                        <input type="password" id="password" name="password" placeholder="Mínim 6 caràcters" required minlength="6">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">
                            <i class="fas fa-lock"></i> Confirmar Contrasenya *
                        </label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeteix la contrasenya" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-user-plus"></i> Registrar-se
                </button>
            </form>
            
            <div class="auth-footer">
                <p>Ja tens compte? <a href="login.php">Inicia sessió aquí</a></p>
            </div>
        </div>
        
        <div class="auth-image">
            <div class="auth-image-content">
                <i class="fas fa-music"></i>
                <h2>Uneix-te a nosaltres!</h2>
                <p>Registra't per començar el teu viatge musical amb els millors professors.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'footer.php'; ?>
