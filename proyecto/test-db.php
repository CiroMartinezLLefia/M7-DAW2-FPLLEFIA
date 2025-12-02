<?php
/**
 * Script de prueba de conexión a base de datos
 * ELIMINAR después de verificar que funciona
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test de conexión a MySQL</h2>";

// Cargar .env manualmente
$envFile = __DIR__ . '/.env';
echo "<p>Buscando .env en: $envFile</p>";

if (file_exists($envFile)) {
    echo "<p style='color:green'>✅ Archivo .env encontrado</p>";
    
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value));
        }
    }
} else {
    echo "<p style='color:red'>❌ Archivo .env NO encontrado</p>";
}

$host = getenv('DB_HOST') ?: 'NO DEFINIDO';
$name = getenv('DB_NAME') ?: 'NO DEFINIDO';
$user = getenv('DB_USER') ?: 'NO DEFINIDO';
$pass = getenv('DB_PASS') ? '****' : 'NO DEFINIDO';

echo "<h3>Variables cargadas:</h3>";
echo "<ul>";
echo "<li>DB_HOST: $host</li>";
echo "<li>DB_NAME: $name</li>";
echo "<li>DB_USER: $user</li>";
echo "<li>DB_PASS: $pass</li>";
echo "</ul>";

echo "<h3>Intentando conexión...</h3>";

try {
    $dsn = "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME') . ";charset=utf8mb4";
    $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "<p style='color:green; font-size:20px'>✅ ¡CONEXIÓN EXITOSA!</p>";
    
    // Probar una query
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Usuarios en la base de datos: " . $result['total'] . "</p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red; font-size:20px'>❌ ERROR DE CONEXIÓN</p>";
    echo "<p style='color:red'>" . $e->getMessage() . "</p>";
}

echo "<hr><p><strong>⚠️ ELIMINA este archivo después de verificar</strong></p>";
?>
