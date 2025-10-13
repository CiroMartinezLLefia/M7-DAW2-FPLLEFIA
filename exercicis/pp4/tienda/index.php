<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mercadona Productos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFdvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
        crossorigin="anonymous">
</head>
<body>

<?php include 'includes/header.php';?>
<div class="container my-5">
  <h2 class="text-center mb-4">Productos disponibles</h2>

  <!-- Aquí va la tabla de productos -->
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Producto</th>
        <th>Precio</th>
        <th>Categoría</th>
        <th>Disponibilidad</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Leche Entera</td>
        <td>1,20 €</td>
        <td>Lácteos</td>
        <td>Disponible</td>
      </tr>
    </tbody>
  </table>

  <!-- Botón para abrir modal -->
  <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#perfilModal">
    Mi perfil 🖐
  </button>

  <!-- Modal de información de contacto -->
  <div class="modal fade" id="perfilModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
       aria-labelledby="perfilModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="perfilModalLabel">Información de contacto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <p><strong>Nombre:</strong><?php echo $nombre; ?></p>
          <p><strong>Teléfono:</strong><?php echo $telefono; ?></p>
          <p><strong>Email:</strong><?php echo $email; ?></p>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="text-center py-3 bg-light mt-5">
  © 2025 Mercadona - Todos los derechos reservados.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+I/RH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous">
</script>

<?php include 'includes/funciones.php';?>

</body>
</html>
