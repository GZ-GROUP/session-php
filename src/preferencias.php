<?php
// ─── Procesar formulario ───────────────────────────────────────────────────
$mensaje = '';
$tipo_alerta = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['guardar'])) {
        // Duración: 30 días en segundos
        $expira = time() + (30 * 24 * 60 * 60);

        $tema    = in_array($_POST['tema'] ?? '', ['light', 'synthwave']) ? $_POST['tema'] : 'light';
        $idioma  = in_array($_POST['idioma'] ?? '', ['es', 'en'])         ? $_POST['idioma'] : 'es';
        $usuario = trim($_POST['usuario'] ?? '');
        $usuario = substr(htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8'), 0, 50);

        // RF-3: httponly = true (7mo parámetro)
        setcookie('pref_tema',    $tema,    $expira, '/', '', false, true);
        setcookie('pref_idioma',  $idioma,  $expira, '/', '', false, true);
        setcookie('pref_usuario', $usuario, $expira, '/', '', false, true);

        $mensaje     = ($idioma === 'en') ? 'Preferences saved for 30 days.' : 'Preferencias guardadas por 30 días.';
        $tipo_alerta = 'success';

        // Actualizar también para que se refleje en la misma página sin recargar
        $_COOKIE['pref_tema']    = $tema;
        $_COOKIE['pref_idioma']  = $idioma;
        $_COOKIE['pref_usuario'] = $usuario;
    }

    if (isset($_POST['borrar'])) {
        // RF-3: httponly = true también al borrar
        setcookie('pref_tema',    '', time() - 3600, '/', '', false, true);
        setcookie('pref_idioma',  '', time() - 3600, '/', '', false, true);
        setcookie('pref_usuario', '', time() - 3600, '/', '', false, true);
        unset($_COOKIE['pref_tema'], $_COOKIE['pref_idioma'], $_COOKIE['pref_usuario']);

        $mensaje     = 'Preferencias eliminadas.';
        $tipo_alerta = 'warning';
    }
}

// ─── Leer cookies actuales (con valores por defecto) ──────────────────────
$tema_actual    = $_COOKIE['pref_tema']    ?? 'light';
$idioma_actual  = $_COOKIE['pref_idioma']  ?? 'es';
$usuario_actual = $_COOKIE['pref_usuario'] ?? '';

// ─── Textos según idioma ──────────────────────────────────────────────────
$t = [
    'es' => [
        'titulo'         => 'Preferencias',
        'legend'         => 'Configuración de Preferencias',
        'lbl_tema'       => 'Tema Visual',
        'opt_claro'      => 'Claro',
        'opt_oscuro'     => 'Oscuro (Synthwave)',
        'lbl_idioma'     => 'Idioma',
        'opt_es'         => 'Español',
        'opt_en'         => 'Inglés',
        'lbl_usuario'    => 'Nombre de Usuario Visitante',
        'ph_usuario'     => 'Tu nombre (sin login)',
        'btn_guardar'    => 'Guardar Preferencias',
        'btn_borrar'     => 'Borrar Preferencias',
        'btn_inicio'     => '← Volver al Inicio',
        'cookie_activas' => 'Cookies activas',
        'no_cookies'     => 'No hay cookies guardadas.',
    ],
    'en' => [
        'titulo'         => 'Preferences',
        'legend'         => 'Preferences Settings',
        'lbl_tema'       => 'Visual Theme',
        'opt_claro'      => 'Light',
        'opt_oscuro'     => 'Dark (Synthwave)',
        'lbl_idioma'     => 'Language',
        'opt_es'         => 'Spanish',
        'opt_en'         => 'English',
        'lbl_usuario'    => 'Visitor Username',
        'ph_usuario'     => 'Your name (no login)',
        'btn_guardar'    => 'Save Preferences',
        'btn_borrar'     => 'Delete Preferences',
        'btn_inicio'     => '← Back to Home',
        'cookie_activas' => 'Active cookies',
        'no_cookies'     => 'No saved cookies.',
    ],
];
$tx = $t[$idioma_actual];
?>
<!DOCTYPE html>
<html lang="<?= $idioma_actual ?>" data-theme="<?= htmlspecialchars($tema_actual) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $tx['titulo'] ?> – Sesión y Cookies</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hammersmith+One&family=Clear+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body>
<div class="hero bg-base-200 min-h-screen">
    <div class="hero-content text-center">
        <div class="max-w-md w-full">
            <div class="flex w-full flex-col gap-3">

                <!-- ── Alerta de resultado ── -->
                <?php if ($mensaje): ?>
                <div role="alert" class="alert alert-<?= $tipo_alerta ?>">
                    <?php if ($tipo_alerta === 'success'): ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <?php endif; ?>
                    <span><?= htmlspecialchars($mensaje) ?></span>
                </div>
                <?php endif; ?>

                <!-- ── Formulario de preferencias ── -->
                <form method="POST" action="preferencias.php">
                    <fieldset class="fieldset bg-base-200 border-base-300 rounded-box border p-4 text-left">
                        <legend class="fieldset-legend"><?= $tx['legend'] ?></legend>

                        <!-- Tema visual -->
                        <label class="label"><?= $tx['lbl_tema'] ?></label>
                        <select name="tema" class="select select-bordered w-full">
                            <option value="light"     <?= $tema_actual === 'light'     ? 'selected' : '' ?>><?= $tx['opt_claro'] ?></option>
                            <option value="synthwave" <?= $tema_actual === 'synthwave' ? 'selected' : '' ?>><?= $tx['opt_oscuro'] ?></option>
                        </select>

                        <!-- Idioma -->
                        <label class="label mt-2"><?= $tx['lbl_idioma'] ?></label>
                        <select name="idioma" class="select select-bordered w-full">
                            <option value="es" <?= $idioma_actual === 'es' ? 'selected' : '' ?>><?= $tx['opt_es'] ?></option>
                            <option value="en" <?= $idioma_actual === 'en' ? 'selected' : '' ?>><?= $tx['opt_en'] ?></option>
                        </select>

                        <!-- Nombre de usuario visitante -->
                        <label class="label mt-2"><?= $tx['lbl_usuario'] ?></label>
                        <input
                            type="text"
                            name="usuario"
                            class="input input-bordered w-full"
                            placeholder="<?= $tx['ph_usuario'] ?>"
                            value="<?= htmlspecialchars($usuario_actual) ?>"
                            maxlength="50"
                        />

                        <!-- Botones de acción -->
                        <div class="flex gap-2 mt-4">
                            <button type="submit" name="guardar" class="btn btn-neutral flex-1">
                                <?= $tx['btn_guardar'] ?>
                            </button>
                            <button type="submit" name="borrar" class="btn btn-error btn-outline flex-1">
                                <?= $tx['btn_borrar'] ?>
                            </button>
                        </div>
                    </fieldset>
                </form>

                <!-- ── Estado actual de cookies ── -->
                <div class="card bg-base-300 rounded-box p-4 text-left text-sm">
                    <p class="font-bold mb-2">🍪 <?= $tx['cookie_activas'] ?>:</p>
                    <?php
                    $cookies_mostrar = array_filter([
                        'pref_tema'    => $_COOKIE['pref_tema']    ?? null,
                        'pref_idioma'  => $_COOKIE['pref_idioma']  ?? null,
                        'pref_usuario' => $_COOKIE['pref_usuario'] ?? null,
                    ]);
                    if ($cookies_mostrar):
                    ?>
                    <ul class="space-y-1">
                        <?php foreach ($cookies_mostrar as $nombre => $valor): ?>
                        <li class="flex justify-between">
                            <span class="badge badge-ghost font-mono"><?= htmlspecialchars($nombre) ?></span>
                            <span class="text-base-content/70"><?= htmlspecialchars($valor) ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <p class="text-base-content/50"><?= $tx['no_cookies'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- ── Volver al inicio ── -->
                <a href="index.php" class="btn btn-ghost btn-sm self-start">
                    <?= $tx['btn_inicio'] ?>
                </a>

            </div>
        </div>
    </div>
</div>
</body>
</html>
