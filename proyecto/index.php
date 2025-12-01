<?php
// Página principal — plantilla Bootstrap con placeholders
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>2High2Work — Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
  </head>
  <body>
    <?php include __DIR__ . '/header.php'; ?>

    <main>
      <section class="hero py-5 animate-fade">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <h1 class="display-5">2High2Work</h1>
              <p class="lead muted">Soluciones creativas y modernas para tus proyectos. Reemplaza este texto por la identidad de la empresa cuando la tengas.</p>
              <p class="mt-4">
                <a href="portfolio.php" class="btn btn-primary btn-lg me-2">Ver portfolio</a>
                <a href="contact.php" class="btn btn-outline-primary btn-lg">Contactar</a>
              </p>
            </div>
            <div class="col-lg-6 text-center">
              <img src="assets/img/placeholder-hero.svg" alt="Ilustración" class="img-fluid" style="max-height:320px">
            </div>
          </div>
        </div>
      </section>

      <section class="container py-5 animate-fade">
        <h2 class="mb-4">Últimas noticias</h2>
        <div class="row g-4">
          <div class="col-md-4">
            <article class="card h-100 border-0 shadow-sm animate-fade">
              <img src="assets/img/placeholder-hero.svg" class="card-img-top glow-on-hover" alt="">
              <div class="card-body">
                <h5 class="card-title">Título de noticia 1</h5>
                <p class="card-text muted">Extracto breve de la primera noticia. Un resumen atractivo para captar la atención del lector.</p>
                <div class="d-flex justify-content-between align-items-center mt-3">
                  <small class="muted">1 Dic, 2025</small>
                  <a href="news.php#news-1" class="stretched-link">Leer más</a>
                </div>
              </div>
            </article>
          </div>
          <div class="col-md-4">
            <article class="card h-100 border-0 shadow-sm animate-fade">
              <img src="assets/img/placeholder-hero.svg" class="card-img-top glow-on-hover" alt="">
              <div class="card-body">
                <h5 class="card-title">Segunda noticia destacada</h5>
                <p class="card-text muted">Resumen corto de la segunda noticia. Información concisa que invita a leer el artículo completo.</p>
                <div class="d-flex justify-content-between align-items-center mt-3">
                  <small class="muted">28 Nov, 2025</small>
                  <a href="news.php#news-2" class="stretched-link">Leer más</a>
                </div>
              </div>
            </article>
          </div>
          <div class="col-md-4">
            <article class="card h-100 border-0 shadow-sm animate-fade">
              <img src="assets/img/placeholder-hero.svg" class="card-img-top glow-on-hover" alt="">
              <div class="card-body">
                <h5 class="card-title">Tercera novedad</h5>
                <p class="card-text muted">Breve descripción de la tercera noticia. Ideal para mostrar variedad y actualidad del sitio.</p>
                <div class="d-flex justify-content-between align-items-center mt-3">
                  <small class="muted">20 Nov, 2025</small>
                  <a href="news.php#news-3" class="stretched-link">Leer más</a>
                </div>
              </div>
            </article>
          </div>
        </div>
      </section>

      <section class="py-5 border-top animate-fade">
        <div class="container">
          <h3 class="mb-4">Preguntas frecuentes</h3>
          <div class="row">
            <div class="col-md-6">
              <p class="muted">Consulta rápida de preguntas frecuentes. Ve a la sección de FAQs para más detalles.</p>
            </div>
            <div class="col-md-6 text-md-end">
              <a href="faqs.php" class="btn btn-outline-primary">Ver FAQs</a>
            </div>
          </div>
        </div>
      </section>
    </main>

    <?php include __DIR__ . '/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
