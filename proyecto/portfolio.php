<?php include __DIR__ . '/header.php'; ?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfolio — 2High2Work</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
  </head>
  <body>
    <main class="container py-5 animate-fade">
      <h2 class="mb-4">Portfolio</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card shadow-sm h-100">
            <img src="assets/img/placeholder-hero.svg" class="card-img-top" alt="">
            <div class="card-body">
              <h5 class="card-title">Proyecto Ejemplo</h5>
              <p class="card-text">Descripción breve del proyecto con enlace.</p>
              <a href="#" class="stretched-link">Ver proyecto</a>
            </div>
          </div>
        </div>
      </div>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
