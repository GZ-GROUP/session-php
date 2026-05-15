<?php
require_once "./config/session.php";

// RF-2: Dashboard protegido — clave de sesión alineada con login.php ($_SESSION['nombre'])
if (!isset($_SESSION['nombre'])) {
    header('Location: login.php');
    exit;
}

// RF-2: Contador de visitas a la página
$_SESSION['visitas'] = ($_SESSION['visitas'] ?? 0) + 1;

// RF-3: htmlspecialchars() en toda salida de datos de usuario
// ✅ Usa 'nombre', no 'usuario' — debe coincidir con lo que guarda login.php
$nombre  = htmlspecialchars($_SESSION['nombre'], ENT_QUOTES, 'UTF-8');
$rol     = htmlspecialchars($_SESSION['rol']    ?? 'usuario', ENT_QUOTES, 'UTF-8');
$inicio  = htmlspecialchars($_SESSION['inicio'] ?? '—', ENT_QUOTES, 'UTF-8');
$visitas = (int) $_SESSION['visitas'];

// Leer preferencias de cookies (claves corregidas: pref_tema / pref_idioma)
$tema   = in_array($_COOKIE['pref_tema']   ?? '', ['light', 'synthwave'])
        ? $_COOKIE['pref_tema'] : 'light';
$idioma = in_array($_COOKIE['pref_idioma'] ?? '', ['es', 'en'])
        ? $_COOKIE['pref_idioma'] : 'es';
?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>" data-theme="<?= htmlspecialchars($tema) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body class="min-h-screen bg-base-200 p-6">

<div class="max-w-2xl mx-auto flex flex-col gap-5">

    <!-- ── Navbar / Header ───────────────────────────────────────────── -->
    <div class="navbar bg-base-100 rounded-box shadow px-4">
        <div class="flex-1">
            <span class="text-lg font-bold">Dashboard</span>
            <span class="badge badge-neutral ml-3"><?= $rol ?></span>
        </div>
        <div class="flex-none gap-2">
            <a href="preferencias.php" class="btn btn-ghost btn-sm">⚙ Preferencias</a>
            <a href="logout.php" class="btn btn-error btn-sm">Cerrar sesión</a>
        </div>
    </div>

    <!-- ── Bienvenida ────────────────────────────────────────────────── -->
    <div class="card bg-base-100 shadow">
        <div class="card-body py-5">
            <h2 class="card-title text-xl">
                Bienvenido, <?= $nombre ?>
            </h2>
            <p class="text-base-content/60 text-sm">
                Sesión iniciada el <?= $inicio ?>
            </p>
        </div>
    </div>

    <!-- ── Tarjetas de estadísticas ──────────────────────────────────── -->
    <div class="grid grid-cols-2 gap-4">

        <div class="card bg-base-100 shadow">
            <div class="card-body items-center text-center gap-1 py-6">
                <span class="text-3xl">👤</span>
                <p class="text-xs text-base-content/50 uppercase tracking-wide mt-1">Usuario</p>
                <p class="font-semibold"><?= $nombre ?></p>
            </div>
        </div>

        <div class="card bg-base-100 shadow">
            <div class="card-body items-center text-center gap-1 py-6">
                <span class="text-3xl">🏷️</span>
                <p class="text-xs text-base-content/50 uppercase tracking-wide mt-1">Rol</p>
                <p class="font-semibold"><?= $rol ?></p>
            </div>
        </div>

        <div class="card bg-base-100 shadow">
            <div class="card-body items-center text-center gap-1 py-6">
                <span class="text-3xl">🕐</span>
                <p class="text-xs text-base-content/50 uppercase tracking-wide mt-1">Sesión iniciada</p>
                <p class="font-semibold text-sm"><?= $inicio ?></p>
            </div>
        </div>

        <div class="card bg-primary text-primary-content shadow">
            <div class="card-body items-center text-center gap-1 py-6">
                <span class="text-3xl">🔢</span>
                <p class="text-xs uppercase tracking-wide mt-1 opacity-70">Visitas</p>
                <p class="font-bold text-4xl"><?= $visitas ?></p>
            </div>
        </div>

    </div>

    <!-- ── Acciones rápidas ───────────────────────────────────────────── -->
    <div class="flex gap-3">
        <a href="preferencias.php" class="btn btn-outline flex-1">⚙ Preferencias</a>
        <a href="logout.php"       class="btn btn-error flex-1">⏻ Cerrar sesión</a>
    </div>

</div>

</body>
</html>