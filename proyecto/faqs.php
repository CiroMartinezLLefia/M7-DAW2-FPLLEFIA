<?php include __DIR__ . '/header.php'; ?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Preguntas Frecuentes — 2High2Work</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
  </head>
  <body>
    <main class="container py-5 animate-fade">
      <h2 class="mb-4">Preguntas Frecuentes</h2>
      <div class="accordion" id="faqs">
        <div class="accordion-item">
          <h2 class="accordion-header" id="q1">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">¿Pregunta de ejemplo?</button>
          </h2>
          <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqs">
            <div class="accordion-body">Respuesta de ejemplo para la FAQ.</div>
          </div>
        </div>
      </div>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
