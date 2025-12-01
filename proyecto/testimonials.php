<?php include __DIR__ . '/header.php'; ?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Testimonios — 2High2Work</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
  </head>
  <body>
    <main class="container py-5 animate-fade">
      <h2 class="mb-4">Testimonios</h2>
      <div class="row g-4">
        <div class="col-md-6">
          <div class="card border-0 shadow-sm p-3 h-100">
            <div class="d-flex align-items-start">
              <img src="assets/img/avatar-placeholder.svg" width="64" height="64" class="rounded-circle me-3" alt="">
              <div>
                <h6 class="mb-1">Nombre Cliente</h6>
                  <small class="muted">Cargo • Empresa</small>
                  <div class="rating" data-rating="5" aria-label="Valoración: 5 de 5 estrellas">
                    <span class="star" role="img" aria-hidden="true">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 .587l3.668 7.431 8.2 1.193-5.934 5.79 1.402 8.17L12 18.896l-7.336 3.877 1.402-8.17L.132 9.211l8.2-1.193z"/></svg>
                    </span>
                    <span class="star" role="img" aria-hidden="true">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 .587l3.668 7.431 8.2 1.193-5.934 5.79 1.402 8.17L12 18.896l-7.336 3.877 1.402-8.17L.132 9.211l8.2-1.193z"/></svg>
                    </span>
                    <span class="star" role="img" aria-hidden="true">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 .587l3.668 7.431 8.2 1.193-5.934 5.79 1.402 8.17L12 18.896l-7.336 3.877 1.402-8.17L.132 9.211l8.2-1.193z"/></svg>
                    </span>
                    <span class="star" role="img" aria-hidden="true">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 .587l3.668 7.431 8.2 1.193-5.934 5.79 1.402 8.17L12 18.896l-7.336 3.877 1.402-8.17L.132 9.211l8.2-1.193z"/></svg>
                    </span>
                    <span class="star" role="img" aria-hidden="true">
                      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 .587l3.668 7.431 8.2 1.193-5.934 5.79 1.402 8.17L12 18.896l-7.336 3.877 1.402-8.17L.132 9.211l8.2-1.193z"/></svg>
                    </span>
                    <span class="value">5.0</span>
                  </div>
                  <p class="mt-2 mb-0">"Breve testimonio de ejemplo que puedes reemplazar por entradas reales."</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
    <script>
      // Rellena visualmente las estrellas según el atributo data-rating
      (function(){
        document.querySelectorAll('.rating').forEach(function(el){
          var r = parseFloat(el.getAttribute('data-rating')) || 0;
          var stars = el.querySelectorAll('.star');
          stars.forEach(function(s,i){
            if(i < Math.floor(r)) s.classList.add('filled');
            else s.classList.remove('filled');
          });
          var val = el.querySelector('.value'); if(val) val.textContent = (Math.round(r*10)/10).toFixed(1);
        });
      })();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
