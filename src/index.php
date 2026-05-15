<?php
// RF-1: Leer cookies y aplicar tema/idioma seleccionado
// RF-3: htmlspecialchars() en toda salida de datos de usuario

// ✅ Claves corregidas: pref_tema / pref_idioma / pref_usuario
//    (el resto del proyecto usa ese prefijo desde preferencias.php)
$tema      = in_array($_COOKIE['pref_tema']   ?? '', ['light', 'synthwave'])
           ? $_COOKIE['pref_tema'] : 'light';
$idioma    = in_array($_COOKIE['pref_idioma'] ?? '', ['es', 'en'])
           ? $_COOKIE['pref_idioma'] : 'es';
$visitante = htmlspecialchars($_COOKIE['pref_usuario'] ?? '', ENT_QUOTES, 'UTF-8');

$textos = [
    'es' => [
        'titulo'        => 'Inicio',
        'bienvenida'    => 'Bienvenido',
        'bienvenida_gen'=> 'Bienvenido al sistema',
        'desc'          => 'Tu tema y idioma se aplican automáticamente desde tus preferencias.',
        'preferencias'  => 'Ajustar preferencias',
        'login'         => 'Iniciar sesión',
        'registro'      => 'Crear cuenta',
        'tema_actual'   => 'Tema',
        'idioma_actual' => 'Idioma',
        'visitante_lbl' => 'Visitante',
        'cookie_titulo' => 'Preferencias activas',
        'no_cookies'    => 'Sin preferencias guardadas.',
    ],
    'en' => [
        'titulo'        => 'Home',
        'bienvenida'    => 'Welcome',
        'bienvenida_gen'=> 'Welcome to the system',
        'desc'          => 'Your theme and language are applied automatically from your preferences.',
        'preferencias'  => 'Adjust preferences',
        'login'         => 'Log in',
        'registro'      => 'Create account',
        'tema_actual'   => 'Theme',
        'idioma_actual' => 'Language',
        'visitante_lbl' => 'Visitor',
        'cookie_titulo' => 'Active preferences',
        'no_cookies'    => 'No saved preferences.',
    ],
];
$t = $textos[$idioma] ?? $textos['es'];

// Etiquetas legibles para mostrar en la tarjeta de cookies
$tema_label   = $tema   === 'synthwave' ? 'Oscuro (Synthwave)' : 'Claro (Light)';
$idioma_label = $idioma === 'en'        ? 'English'            : 'Español';
?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>" data-theme="<?= htmlspecialchars($tema) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($t['titulo']) ?> – Sesión y Cookies</title>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body class="min-h-screen bg-base-200 flex items-center justify-center p-4">

<div class="flex w-full max-w-md flex-col gap-4">

    <!-- ── Hero / Bienvenida ──────────────────────────────────────────── -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body items-center text-center gap-3">

            <!-- Avatar / icono -->
            <div class="avatar placeholder">
                <div class="bg-primary text-primary-content rounded-full w-16">
                    <span class="text-3xl">🏠</span>
                </div>
            </div>

            <!-- Título personalizado si hay visitante -->
            <h1 class="card-title text-2xl">
                <?php if ($visitante !== ''): ?>
                    <?= htmlspecialchars($t['bienvenida']) ?>, <?= $visitante ?>!
                <?php else: ?>
                    <?= htmlspecialchars($t['bienvenida_gen']) ?>
                <?php endif; ?>
            </h1>

            <p class="text-base-content/60 text-sm">
                <?= htmlspecialchars($t['desc']) ?>
            </p>

            <!-- Botones de acción -->
            <div class="card-actions flex-col w-full gap-2 mt-2">
                <a href="login.php" class="btn btn-primary w-full">
                    <?= htmlspecialchars($t['login']) ?>
                </a>
                <a href="signup.php" class="btn btn-outline w-full">
                    <?= htmlspecialchars($t['registro']) ?>
                </a>
                <a href="preferencias.php" class="btn btn-ghost btn-sm w-full">
                    ⚙ <?= htmlspecialchars($t['preferencias']) ?>
                </a>
            </div>
        </div>
    </div>

    <!-- ── Tarjeta de cookies activas ────────────────────────────────── -->
    <div class="card bg-base-100 shadow">
        <div class="card-body py-4 gap-2">
            <p class="font-semibold text-sm">🍪 <?= htmlspecialchars($t['cookie_titulo']) ?></p>

            <?php
            $hay_cookies = isset($_COOKIE['pref_tema'])
                        || isset($_COOKIE['pref_idioma'])
                        || (isset($_COOKIE['pref_usuario']) && $_COOKIE['pref_usuario'] !== '');

            if ($hay_cookies): ?>
            <ul class="flex flex-col gap-1 text-sm">

                <?php if (isset($_COOKIE['pref_tema'])): ?>
                <li class="flex justify-between items-center">
                    <span class="badge badge-ghost font-mono">pref_tema</span>
                    <span class="text-base-content/70"><?= htmlspecialchars($tema_label) ?></span>
                </li>
                <?php endif; ?>

                <?php if (isset($_COOKIE['pref_idioma'])): ?>
                <li class="flex justify-between items-center">
                    <span class="badge badge-ghost font-mono">pref_idioma</span>
                    <span class="text-base-content/70"><?= htmlspecialchars($idioma_label) ?></span>
                </li>
                <?php endif; ?>

                <?php if ($visitante !== ''): ?>
                <li class="flex justify-between items-center">
                    <span class="badge badge-ghost font-mono">pref_usuario</span>
                    <span class="text-base-content/70"><?= $visitante ?></span>
                </li>
                <?php endif; ?>

            </ul>
            <?php else: ?>
            <p class="text-base-content/40 text-sm"><?= htmlspecialchars($t['no_cookies']) ?></p>
            <?php endif; ?>
        </div>
    </div>

</div>

</body>
</html>