<?php
session_start();

// RF-2: Si ya hay sesión activa, ir al dashboard
if (isset($_SESSION['usuario'])) {
    header('Location: dashboard.php');
    exit;
}

// RF-2: Usuarios hardcodeados (al menos 3)
$usuarios_validos = [
    'admin'   => ['password' => 'admin123',  'rol' => 'Administrador'],
    'maria'   => ['password' => 'maria456',  'rol' => 'Editor'],
    'carlos'  => ['password' => 'carlos789', 'rol' => 'Visitante'],
];

$error = '';

// RF-3: Verificar método del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // RF-3: session_regenerate_id(true) al iniciar sesión
    $nombre = $_POST['usuario'] ?? '';
    $pass   = $_POST['password'] ?? '';

    if (isset($usuarios_validos[$nombre]) && $usuarios_validos[$nombre]['password'] === $pass) {
        // RF-3: session_regenerate_id para prevenir session fixation
        session_regenerate_id(true);

        // RF-2: Crear sesión con nombre, rol, fecha/hora de inicio
        $_SESSION['usuario']    = $nombre;
        $_SESSION['rol']        = $usuarios_validos[$nombre]['rol'];
        $_SESSION['inicio']     = date('Y-m-d H:i:s');
        $_SESSION['visitas']    = 0;

        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos.';
    }
}

// Leer preferencias de cookies para aplicar tema/idioma
$tema   = htmlspecialchars($_COOKIE['tema']   ?? 'claro');
$idioma = htmlspecialchars($_COOKIE['idioma'] ?? 'es');
?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="estilos.php">
</head>
<body class="tema-<?= $tema ?>">

<div class="contenedor contenedor-login">
    <h1>Iniciar sesión</h1>

    <?php if ($error !== ''): ?>
        <div class="alerta-error">
            <?= htmlspecialchars($error) /* RF-3 */ ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="login.php">

        <div class="campo">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario"
                   required autocomplete="username" maxlength="60">
        </div>

        <div class="campo">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password"
                   required autocomplete="current-password">
        </div>

        <div class="botones">
            <button type="submit">Entrar</button>
        </div>

    </form>

    <p><small>Usuarios de prueba: admin / maria / carlos</small></p>
    <p><a href="index.php">← Volver al inicio</a></p>
</div>

</body>
</html>
