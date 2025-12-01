<?php include __DIR__ . '/header.php'; ?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Noticias — 2High2Work</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
  </head>
  <body>
    <main class="container py-5 animate-fade">
      <h2 class="mb-4">Noticias</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <article id="news-1" class="card h-100">
            <img src="assets/img/placeholder-hero.svg" class="card-img-top" alt="">
            <div class="card-body">
              <h5 class="card-title">Título de noticia 1</h5>
              <p class="card-text muted">Extracto breve de la noticia 1 para que el usuario sepa de qué va. Ideal como adelanto informativo.</p>
              <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="muted">1 Dic, 2025</small>
                <a href="#news-1" class="stretched-link">Leer más</a>
              </div>
            </div>
          </article>
        </div>
        <div class="col-md-4">
          <article id="news-2" class="card h-100">
            <img src="assets/img/placeholder-hero.svg" class="card-img-top" alt="">
            <div class="card-body">
              <h5 class="card-title">Segunda noticia destacada</h5>
              <p class="card-text muted">Resumen de la segunda noticia. Mantén el texto corto y directo para mejorar la lectura.</p>
              <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="muted">28 Nov, 2025</small>
                <a href="#news-2" class="stretched-link">Leer más</a>
              </div>
            </div>
          </article>
        </div>
        <div class="col-md-4">
          <article id="news-3" class="card h-100">
            <img src="assets/img/placeholder-hero.svg" class="card-img-top" alt="">
            <div class="card-body">
              <h5 class="card-title">Tercera novedad</h5>
              <p class="card-text muted">Breve descripción de la tercera noticia destacada. Útil para anuncios y novedades rápidas.</p>
              <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="muted">20 Nov, 2025</small>
                <a href="#news-3" class="stretched-link">Leer más</a>
              </div>
            </div>
          </article>
        </div>
      </div>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
