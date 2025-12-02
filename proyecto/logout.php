<?php
/**
 * GameZone - Cerrar Sesión
 */
require_once __DIR__ . '/config.php';
initSession();

// Destruir la sesión
$_SESSION = [];

// Eliminar la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();

// Mensaje flash (se establece en una nueva sesión)
session_start();
setFlashMessage('success', 'Has cerrado sesión correctamente. ¡Hasta pronto!');

// Redirigir al inicio
redirect('index.php');
