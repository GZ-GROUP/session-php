<?php
session_start();

// RF-2: Dashboard protegido; si no hay sesión redirigir a login
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// RF-2: Contador de visitas a la página
$_SESSION['visitas'] = ($_SESSION['visitas'] ?? 0) + 1;

// RF-3: htmlspecialchars() en toda salida de datos de usuario
$nombre  = htmlspecialchars($_SESSION['usuario']);
$rol     = htmlspecialchars($_SESSION['rol']);
$inicio  = htmlspecialchars($_SESSION['inicio']);
$visitas = (int) $_SESSION['visitas'];

// Leer preferencias de cookies
$tema   = htmlspecialchars($_COOKIE['tema']   ?? 'claro');
$idioma = htmlspecialchars($_COOKIE['idioma'] ?? 'es');
?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="estilos.php">
</head>
<body class="tema-<?= $tema ?>">

<div class="contenedor">

    <!-- RF-2: Bienvenida con nombre, rol y contador de visitas -->
    <header class="dashboard-header">
        <div>
            <h1>Dashboard</h1>
            <p class="subtitulo">
                Bienvenido, <strong><?= $nombre ?></strong>
                &mdash; Rol: <strong><?= $rol ?></strong>
            </p>
        </div>
        <a href="logout.php" class="btn btn-logout">Cerrar sesión</a>
    </header>

    <div class="tarjetas">

        <div class="tarjeta">
            <span class="icono">👤</span>
            <h2>Usuario</h2>
            <p><?= $nombre ?></p>
        </div>

        <div class="tarjeta">
            <span class="icono">🏷️</span>
            <h2>Rol</h2>
            <p><?= $rol ?></p>
        </div>

        <div class="tarjeta">
            <span class="icono">🕐</span>
            <h2>Sesión iniciada</h2>
            <p><?= $inicio ?></p>
        </div>

        <div class="tarjeta">
            <span class="icono">🔢</span>
            <h2>Visitas a esta página</h2>
            <p class="numero-grande"><?= $visitas ?></p>
        </div>

    </div>

    <div class="botones" style="margin-top:2rem;">
        <a href="preferencias.php" class="btn">Preferencias</a>
        <a href="logout.php" class="btn btn-borrar">Cerrar sesión</a>
    </div>

</div>

</body>
</html>
