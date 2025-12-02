<?php
/**
 * GameZone - Preguntas Frecuentes
 */
require_once __DIR__ . '/config.php';
initSession();

$pdo = getDBConnection();
$faqs = [];
$categories = [];

if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM faqs WHERE is_active = 1 ORDER BY category, display_order, id");
        $faqs = $stmt->fetchAll();
        
        // Agrupar por categoría
        $categories = [];
        foreach ($faqs as $faq) {
            $cat = $faq['category'] ?: 'General';
            if (!isset($categories[$cat])) {
                $categories[$cat] = [];
            }
            $categories[$cat][] = $faq;
        }
    } catch (PDOException $e) {
        error_log("Error cargando FAQs: " . $e->getMessage());
    }
}

// Si no hay FAQs en la base de datos, mostrar ejemplos
if (empty($categories)) {
    $categories = [
        'Cuenta' => [
            ['question' => '¿Cómo puedo crear una cuenta en GameZone?', 'answer' => 'Puedes crear una cuenta haciendo clic en "Registrarse" en la parte superior de la página y completando el formulario con tus datos.'],
            ['question' => '¿Puedo comentar en las noticias sin registrarme?', 'answer' => 'No, necesitas crear una cuenta gratuita para poder comentar en nuestras noticias y análisis.'],
        ],
        'General' => [
            ['question' => '¿Cómo puedo contactar con el equipo de GameZone?', 'answer' => 'Puedes usar nuestro formulario de contacto o enviarnos un email a contacto@gamezone.com'],
            ['question' => '¿GameZone tiene app móvil?', 'answer' => 'Actualmente no disponemos de aplicación móvil, pero nuestra web está optimizada para dispositivos móviles.'],
        ],
        'Colaboración' => [
            ['question' => '¿Puedo enviar noticias o colaborar con GameZone?', 'answer' => 'Sí, aceptamos colaboraciones. Envíanos tu propuesta a colaboraciones@gamezone.com'],
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntas Frecuentes — <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    
    <main class="container py-5">
      <div class="row">
        <div class="col-lg-8 mx-auto">
          <div class="text-center mb-5">
            <h1><i class="bi bi-question-circle text-primary me-2"></i>Preguntas Frecuentes</h1>
            <p class="text-secondary">Encuentra respuestas a las preguntas más comunes sobre <?= SITE_NAME ?></p>
          </div>
          
          <?php $catIndex = 0; foreach ($categories as $categoryName => $categoryFaqs): ?>
          <div class="mb-5">
            <h4 class="mb-3 text-primary"><?= e($categoryName) ?></h4>
            
            <div class="accordion" id="faqs-<?= $catIndex ?>">
              <?php foreach ($categoryFaqs as $index => $faq): ?>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" 
                          data-bs-toggle="collapse" data-bs-target="#faq-<?= $catIndex ?>-<?= $index ?>">
                    <?= e($faq['question']) ?>
                  </button>
                </h2>
                <div id="faq-<?= $catIndex ?>-<?= $index ?>" 
                     class="accordion-collapse collapse <?= $index === 0 && $catIndex === 0 ? 'show' : '' ?>" 
                     data-bs-parent="#faqs-<?= $catIndex ?>">
                  <div class="accordion-body">
                    <?= nl2br(e($faq['answer'])) ?>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php $catIndex++; endforeach; ?>
          
          <!-- CTA Contacto -->
          <div class="card p-4 text-center mt-5" style="background: linear-gradient(135deg, rgba(99,102,241,0.1), rgba(139,92,246,0.05));">
            <h5>¿No encuentras lo que buscas?</h5>
            <p class="text-secondary mb-3">Contáctanos y te ayudaremos con cualquier duda que tengas.</p>
            <a href="contact.php" class="btn btn-primary">
              <i class="bi bi-envelope me-2"></i>Contactar
            </a>
          </div>
        </div>
      </div>
    </main>
    
    <?php include __DIR__ . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

