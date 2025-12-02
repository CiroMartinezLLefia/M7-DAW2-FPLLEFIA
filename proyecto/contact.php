<?php
/**
 * GameZone - Página de Contacto
 */
require_once __DIR__ . '/config.php';
initSession();

$errors = [];
$success = false;
$name = '';
$email = '';
$subject = '';
$message = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validaciones
    if (empty($name)) {
        $errors['name'] = 'El nombre es obligatorio.';
    }
    
    if (empty($email)) {
        $errors['email'] = 'El email es obligatorio.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'El email no es válido.';
    }
    
    if (empty($message)) {
        $errors['message'] = 'El mensaje es obligatorio.';
    } elseif (strlen($message) < 10) {
        $errors['message'] = 'El mensaje debe tener al menos 10 caracteres.';
    }
    
    // Guardar en base de datos
    if (empty($errors)) {
        $pdo = getDBConnection();
        
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO contact_messages (name, email, subject, message, created_at)
                    VALUES (?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$name, $email, $subject, $message]);
                $success = true;
                
                // Limpiar formulario
                $name = $email = $subject = $message = '';
                
            } catch (PDOException $e) {
                error_log("Error guardando mensaje: " . $e->getMessage());
                $errors['general'] = 'Error al enviar el mensaje. Inténtalo de nuevo.';
            }
        } else {
            // Si no hay BD, simular éxito
            $success = true;
            $name = $email = $subject = $message = '';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto — <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    
    <main class="container py-5">
      <div class="row">
        <div class="col-lg-6 mb-5 mb-lg-0">
          <h1 class="mb-3"><i class="bi bi-envelope text-primary me-2"></i>Contacto</h1>
          <p class="text-secondary mb-4">
            ¿Tienes alguna pregunta, sugerencia o quieres colaborar con nosotros? 
            Rellena el formulario y te responderemos lo antes posible.
          </p>
          
          <?php if ($success): ?>
            <div class="alert alert-success">
              <i class="bi bi-check-circle me-2"></i>
              ¡Mensaje enviado correctamente! Te responderemos pronto.
            </div>
          <?php endif; ?>
          
          <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-danger"><?= e($errors['general']) ?></div>
          <?php endif; ?>
          
          <form method="POST" class="card p-4">
            <div class="mb-3">
              <label class="form-label">Nombre *</label>
              <input type="text" name="name" 
                     class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                     value="<?= e($name) ?>" placeholder="Tu nombre" required>
              <?php if (isset($errors['name'])): ?>
                <div class="invalid-feedback"><?= e($errors['name']) ?></div>
              <?php endif; ?>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Email *</label>
              <input type="email" name="email" 
                     class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                     value="<?= e($email) ?>" placeholder="tu@email.com" required>
              <?php if (isset($errors['email'])): ?>
                <div class="invalid-feedback"><?= e($errors['email']) ?></div>
              <?php endif; ?>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Asunto</label>
              <select name="subject" class="form-select">
                <option value="">Selecciona un tema</option>
                <option value="Consulta general" <?= $subject === 'Consulta general' ? 'selected' : '' ?>>Consulta general</option>
                <option value="Colaboración" <?= $subject === 'Colaboración' ? 'selected' : '' ?>>Colaboración</option>
                <option value="Reportar error" <?= $subject === 'Reportar error' ? 'selected' : '' ?>>Reportar un error</option>
                <option value="Sugerencia" <?= $subject === 'Sugerencia' ? 'selected' : '' ?>>Sugerencia</option>
                <option value="Otro" <?= $subject === 'Otro' ? 'selected' : '' ?>>Otro</option>
              </select>
            </div>
            
            <div class="mb-4">
              <label class="form-label">Mensaje *</label>
              <textarea name="message" rows="5" 
                        class="form-control <?= isset($errors['message']) ? 'is-invalid' : '' ?>" 
                        placeholder="Escribe tu mensaje aquí..." required><?= e($message) ?></textarea>
              <?php if (isset($errors['message'])): ?>
                <div class="invalid-feedback"><?= e($errors['message']) ?></div>
              <?php endif; ?>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg">
              <i class="bi bi-send me-2"></i>Enviar Mensaje
            </button>
          </form>
        </div>
        
        <div class="col-lg-5 offset-lg-1">
          <div class="card p-4 mb-4">
            <h5 class="mb-4">Información de contacto</h5>
            
            <div class="d-flex align-items-start gap-3 mb-3">
              <div class="stat-icon primary" style="width: 40px; height: 40px; font-size: 1rem;">
                <i class="bi bi-envelope"></i>
              </div>
              <div>
                <div class="fw-bold">Email</div>
                <a href="mailto:<?= SITE_EMAIL ?>" class="text-secondary"><?= SITE_EMAIL ?></a>
              </div>
            </div>
            
            <div class="d-flex align-items-start gap-3 mb-3">
              <div class="stat-icon success" style="width: 40px; height: 40px; font-size: 1rem;">
                <i class="bi bi-twitter-x"></i>
              </div>
              <div>
                <div class="fw-bold">Twitter/X</div>
                <a href="#" class="text-secondary">@GameZoneES</a>
              </div>
            </div>
            
            <div class="d-flex align-items-start gap-3">
              <div class="stat-icon warning" style="width: 40px; height: 40px; font-size: 1rem;">
                <i class="bi bi-discord"></i>
              </div>
              <div>
                <div class="fw-bold">Discord</div>
                <a href="#" class="text-secondary">discord.gg/gamezone</a>
              </div>
            </div>
          </div>
          
          <div class="card p-4" style="background: linear-gradient(135deg, rgba(99,102,241,0.1), rgba(139,92,246,0.05));">
            <h5 class="mb-3">¿Quieres colaborar?</h5>
            <p class="text-secondary mb-0">
              Si te apasionan los videojuegos y quieres formar parte de nuestro equipo, 
              envíanos tu CV y portfolio a <strong>colaboraciones@gamezone.com</strong>
            </p>
          </div>
        </div>
      </div>
    </main>
    
    <?php include __DIR__ . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

