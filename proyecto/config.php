<?php
/**
 * Configuración de GameZone - Portal de Noticias de Videojuegos
 * 
 * Este archivo contiene la configuración de conexión a la base de datos
 * y otras constantes globales del proyecto.
 */

// Configuración de errores (desactivar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// =====================================================
// Cargar variables de entorno desde .env
// =====================================================
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignorar comentarios
        if (strpos(trim($line), '#') === 0) continue;
        
        // Parsear KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // No sobreescribir variables ya definidas
            if (!getenv($key)) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
            }
        }
    }
}

// =====================================================
// CONFIGURACIÓN DE BASE DE DATOS
// =====================================================
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'gamezone_db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

// Configuración del sitio
define('SITE_NAME', 'GameZone');
define('SITE_TAGLINE', 'Tu portal de noticias gaming');
define('SITE_URL', getenv('SITE_URL') ?: 'http://localhost/proyecto');
define('SITE_EMAIL', getenv('SITE_EMAIL') ?: 'contacto@gamezone.com');

// Rutas del proyecto
define('BASE_PATH', __DIR__);
define('ASSETS_PATH', BASE_PATH . '/assets');
define('UPLOADS_PATH', BASE_PATH . '/uploads');

// URLs de assets
define('ASSETS_URL', SITE_URL . '/assets');
define('UPLOADS_URL', SITE_URL . '/uploads');

// Configuración de paginación
define('NEWS_PER_PAGE', 9);
define('COMMENTS_PER_PAGE', 10);

// Roles de usuario
define('ROLE_USER', 'user');
define('ROLE_EDITOR', 'editor');
define('ROLE_ADMIN', 'admin');

/**
 * Conexión a la base de datos usando PDO
 * 
 * @return PDO|null Objeto PDO si la conexión es exitosa, null en caso contrario
 */
function getDBConnection(): ?PDO {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // En producción, loguear el error en lugar de mostrarlo
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            return null;
        }
    }
    
    return $pdo;
}

/**
 * Conexión a la base de datos usando MySQLi (alternativa)
 * 
 * @return mysqli|null Objeto mysqli si la conexión es exitosa, null en caso contrario
 */
function getMySQLiConnection(): ?mysqli {
    static $mysqli = null;
    
    if ($mysqli === null) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($mysqli->connect_error) {
            error_log("Error de conexión MySQLi: " . $mysqli->connect_error);
            return null;
        }
        
        $mysqli->set_charset(DB_CHARSET);
    }
    
    return $mysqli;
}

/**
 * Iniciar sesión de forma segura
 */
function initSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start([
            'cookie_httponly' => true,
            'cookie_secure' => isset($_SERVER['HTTPS']),
            'use_strict_mode' => true
        ]);
    }
}

/**
 * Generar o obtener token CSRF
 * 
 * @return string Token CSRF
 */
function getCSRFToken(): string {
    initSession();
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verificar token CSRF
 * 
 * @param string $token Token a verificar
 * @return bool True si es válido
 */
function verifyCSRFToken(string $token): bool {
    initSession();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Verificar si el usuario está logueado
 * 
 * @return bool True si el usuario está logueado
 */
function isLoggedIn(): bool {
    initSession();
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}


/**
 * Obtener datos del usuario actual
 * 
 * @return array|null Datos del usuario o null si no está logueado
 */
function getCurrentUser(): ?array {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? '',
        'email' => $_SESSION['email'] ?? '',
        'role' => $_SESSION['role'] ?? ROLE_USER,
        'display_name' => $_SESSION['display_name'] ?? $_SESSION['username'] ?? 'Usuario',
        'avatar' => $_SESSION['avatar'] ?? 'default-avatar.svg'
    ];
}

/**
 * Verificar si el usuario tiene un rol específico
 * 
 * @param string|array $roles Rol o roles a verificar
 * @return bool True si el usuario tiene el rol
 */
function hasRole($roles): bool {
    $user = getCurrentUser();
    if (!$user) return false;
    
    if (is_string($roles)) {
        $roles = [$roles];
    }
    
    return in_array($user['role'], $roles);
}

/**
 * Verificar si el usuario es administrador
 * 
 * @return bool True si es admin
 */
function isAdmin(): bool {
    return hasRole(ROLE_ADMIN);
}

/**
 * Verificar si el usuario puede editar contenido
 * 
 * @return bool True si puede editar (editor o admin)
 */
function canEdit(): bool {
    return hasRole([ROLE_EDITOR, ROLE_ADMIN]);
}

/**
 * Redireccionar a una URL
 * 
 * @param string $url URL de destino
 */
function redirect(string $url): void {
    header("Location: $url");
    exit();
}

/**
 * Mostrar mensaje flash
 * 
 * @param string $type Tipo de mensaje (success, error, warning, info)
 * @param string $message Mensaje a mostrar
 */
function setFlashMessage(string $type, string $message): void {
    initSession();
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Obtener y eliminar mensaje flash
 * 
 * @return array|null Mensaje flash o null
 */
function getFlashMessage(): ?array {
    initSession();
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Escapar HTML para prevenir XSS
 * 
 * @param string $string Cadena a escapar
 * @return string Cadena escapada
 */
function e(string $string): string {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Generar slug a partir de un texto
 * 
 * @param string $text Texto a convertir
 * @return string Slug generado
 */
function generateSlug(string $text): string {
    // Convertir a minúsculas
    $text = mb_strtolower($text, 'UTF-8');
    // Reemplazar caracteres especiales
    $text = preg_replace('/[áàäâ]/u', 'a', $text);
    $text = preg_replace('/[éèëê]/u', 'e', $text);
    $text = preg_replace('/[íìïî]/u', 'i', $text);
    $text = preg_replace('/[óòöô]/u', 'o', $text);
    $text = preg_replace('/[úùüû]/u', 'u', $text);
    $text = preg_replace('/[ñ]/u', 'n', $text);
    // Reemplazar espacios y caracteres no alfanuméricos
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    // Eliminar guiones al inicio y final
    $text = trim($text, '-');
    return $text;
}

/**
 * Formatear fecha en español
 * 
 * @param string $date Fecha a formatear
 * @param string $format Formato deseado
 * @return string Fecha formateada
 */
function formatDate(string $date, string $format = 'd M, Y'): string {
    $months = [
        'Jan' => 'Ene', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Abr',
        'May' => 'May', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Ago',
        'Sep' => 'Sep', 'Oct' => 'Oct', 'Nov' => 'Nov', 'Dec' => 'Dic'
    ];
    
    $formatted = date($format, strtotime($date));
    
    foreach ($months as $en => $es) {
        $formatted = str_replace($en, $es, $formatted);
    }
    
    return $formatted;
}

/**
 * Truncar texto manteniendo palabras completas
 * 
 * @param string $text Texto a truncar
 * @param int $length Longitud máxima
 * @param string $suffix Sufijo a añadir
 * @return string Texto truncado
 */
function truncateText(string $text, int $length = 150, string $suffix = '...'): string {
    $text = strip_tags($text);
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    
    $text = mb_substr($text, 0, $length);
    $lastSpace = mb_strrpos($text, ' ');
    
    if ($lastSpace !== false) {
        $text = mb_substr($text, 0, $lastSpace);
    }
    
    return $text . $suffix;
}

/**
 * Convertir fecha a formato "hace X tiempo"
 * 
 * @param string $datetime Fecha y hora
 * @return string Tiempo relativo en español
 */
function timeAgo(string $datetime): string {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return 'Hace un momento';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return "Hace $mins " . ($mins == 1 ? 'minuto' : 'minutos');
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return "Hace $hours " . ($hours == 1 ? 'hora' : 'horas');
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return "Hace $days " . ($days == 1 ? 'día' : 'días');
    } elseif ($diff < 2592000) {
        $weeks = floor($diff / 604800);
        return "Hace $weeks " . ($weeks == 1 ? 'semana' : 'semanas');
    } elseif ($diff < 31536000) {
        $months = floor($diff / 2592000);
        return "Hace $months " . ($months == 1 ? 'mes' : 'meses');
    } else {
        $years = floor($diff / 31536000);
        return "Hace $years " . ($years == 1 ? 'año' : 'años');
    }
}
