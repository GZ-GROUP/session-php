<?php
session_start();

// RF-2: Logout completo — los 3 pasos

// Paso 1: Vaciar el array de sesión
$_SESSION = [];

// Paso 2: Eliminar la cookie de sesión
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']   // RF-3: httponly = true
    );
}

// Paso 3: Destruir la sesión
session_destroy();

header('Location: index.php');
exit;
