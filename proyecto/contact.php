<?php include __DIR__ . '/header.php'; ?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contacto — 2High2Work</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
  </head>
  <body>
    <main class="container py-5 animate-fade">
      <h2 class="mb-4">Contacto</h2>
      <div class="row">
        <div class="col-md-6">
          <form>
            <div class="mb-3">
              <label class="form-label">Nombre</label>
              <input class="form-control" placeholder="Tu nombre">
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" placeholder="tu@correo.test">
            </div>
            <div class="mb-3">
              <label class="form-label">Mensaje</label>
              <textarea class="form-control" rows="5"></textarea>
            </div>
            <button class="btn btn-primary">Enviar</button>
          </form>
        </div>
        <div class="col-md-6">
          <h6>Información de contacto</h6>
          <p class="muted">Dirección placeholder • Teléfono • Email</p>
        </div>
      </div>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
