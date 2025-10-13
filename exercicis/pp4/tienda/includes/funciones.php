<script>
        $nombre = $_GET['nombre'];
        $apellidos = $_GET['apellidos'];
        $dni = $_GET['dni'];
        $codigo = $_GET['codigo'];
        $email = $_GET['email'];
        $telefono = $_GET['telefono'];
        $img = $_GET['URL'];

        <?php include 'includes/productos.php';?>
        
        function generarTablaProductos(producto)
        {
                $productos.producto
        }

        /* Recibe un array de productos y genera una tabla con las siguientes columnas: 
        Nombre del producto (la primera letra en mayúscula) 
        Precio (formateado con number_format ) 
        Disponibilidad (Usa un operador ternario para mostrar "En stock" o "Agotado"). Si está agotado, la fila de la tabla debe aparecer en color rojo. 2. muestraInfoContacto($nombre, $telefono, $foto) : 
        Muestra la información de contacto proporcionada en el formulario en un bloque debajo de la tabla de productos. 
        El bloque debe incluir: 
        Nombre 
        // Teléfono 
        Foto del perfil (usando la URL proporcionada) */

</script>