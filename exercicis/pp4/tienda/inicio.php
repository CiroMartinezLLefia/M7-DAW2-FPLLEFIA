<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenido a Mercadona</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFdvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
        crossorigin="anonymous">
</head>
<body>

<div class="container-fluid">
  <div class="container text-center my-5">
    <h1>Bienvenido a Mercadona</h1>
    <p>Por favor, completa el siguiente formulario para continuar tu compra.</p>
  </div>

  <div class="container">
    <form class="row g-3">
      <div class="col-md-6">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre" placeholder="Tu nombre">
      </div>
      <div class="col-md-6">
        <label for="apellidos" class="form-label">Apellidos</label>
        <input type="text" class="form-control" id="apellidos" placeholder="Tus apellidos">
      </div>
      <div class="col-md-6">
        <label for="telefono" class="form-label">Número de teléfono</label>
        <input type="tel" class="form-control" id="telefono" placeholder="Ej: 600123456">
      </div>
      <div class="col-md-6">
        <label for="dni" class="form-label">DNI</label>
        <input type="text" class="form-control" id="dni" placeholder="12345678A">
      </div>
      <div class="col-md-6">
        <label for="codigo" class="form-label">Código de socio</label>
        <input type="text" class="form-control" id="codigo" placeholder="Código de socio">
      </div>
      <div class="col-md-6">
        <label for="email" class="form-label">Correo electrónico</label>
        <input type="email" class="form-control" id="email" placeholder="tucorreo@example.com">
      </div>
      <div class="col-12 text-center mt-4">
        <button type="submit" class="btn btn-success">Continuar</button>
      </div>
    </form>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+I/RH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</body>
</html>
