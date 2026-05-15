<?php
// ─── Leer cookies de preferencias ────────────────────────────────────────
$tema_actual    = $_COOKIE['pref_tema']    ?? 'light';
$idioma_actual  = $_COOKIE['pref_idioma']  ?? 'es';
$usuario_actual = $_COOKIE['pref_usuario'] ?? '';

// Solo los valores permitidos
if (!in_array($tema_actual, ['light', 'synthwave'])) $tema_actual = 'light';
if (!in_array($idioma_actual, ['es', 'en']))          $idioma_actual = 'es';

// ─── Textos según idioma ──────────────────────────────────────────────────
$t = [
    'es' => [
        'titulo'      => 'Iniciar Sesión',
        'legend'      => 'Iniciar Sesión',
        'lbl_user'    => 'Usuario',
        'lbl_pass'    => 'Contraseña',
        'ph_user'     => 'Nombre de Usuario',
        'ph_pass'     => 'Contraseña',
        'btn_ingresar'=> 'Ingresar',
        'alerta'      => 'Contraseña o Correo Inválido',
        'bienvenido'  => 'Bienvenido',
        'preferencias'=> 'Preferencias',
    ],
    'en' => [
        'titulo'      => 'Login',
        'legend'      => 'Login',
        'lbl_user'    => 'Username',
        'lbl_pass'    => 'Password',
        'ph_user'     => 'Username',
        'ph_pass'     => 'Password',
        'btn_ingresar'=> 'Sign In',
        'alerta'      => 'Invalid Password or Email',
        'bienvenido'  => 'Welcome',
        'preferencias'=> 'Preferences',
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
</head>
<body>
    <div class="hero bg-base-200 min-h-screen">
        <div class="hero-content text-center">
            <div class="max-w-md">
                <div class="flex w-full flex-col gap-3">

                    <!-- ── Bienvenida si hay nombre de usuario guardado ── -->
                    <?php if ($usuario_actual !== ''): ?>
                    <div role="alert" class="alert alert-info">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span><?= $tx['bienvenido'] ?>, <strong><?= htmlspecialchars($usuario_actual) ?></strong>!</span>
                    </div>
                    <?php endif; ?>

                    <!-- ── Alerta de error (mostrar solo cuando sea necesario) ── -->
                    <?php if (isset($_GET['error']) && $_GET['error'] === '1'): ?>
                    <div role="alert" class="alert alert-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span><?= $tx['alerta'] ?></span>
                    </div>
                    <?php endif; ?>

                    <!-- ── Formulario de login ── -->
                    <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4">
                        <legend class="fieldset-legend"><?= $tx['legend'] ?></legend>

                        <label class="label"><?= $tx['lbl_user'] ?></label>
                        <input type="text" class="input" placeholder="<?= $tx['ph_user'] ?>" />

                        <label class="label"><?= $tx['lbl_pass'] ?></label>
                        <input type="password" class="input" placeholder="<?= $tx['ph_pass'] ?>" />

                        <button class="btn btn-neutral mt-4"><?= $tx['btn_ingresar'] ?></button>
                    </fieldset>

                    <!-- ── Controles de tema e idioma + enlace a preferencias ── -->
                    <div class="flex items-center justify-between gap-2 flex-wrap">

                        <!-- Toggle tema -->
                        <label class="toggle text-base-content" title="<?= $idioma_actual === 'es' ? 'Cambiar tema' : 'Toggle theme' ?>">
                            <input
                                type="checkbox"
                                value="synthwave"
                                class="theme-controller"
                                <?= $tema_actual === 'synthwave' ? 'checked' : '' ?>
                            />
                            <!-- Sol -->
                            <svg aria-label="sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                                    <circle cx="12" cy="12" r="4"></circle>
                                    <path d="M12 2v2"></path><path d="M12 20v2"></path>
                                    <path d="m4.93 4.93 1.41 1.41"></path><path d="m17.66 17.66 1.41 1.41"></path>
                                    <path d="M2 12h2"></path><path d="M20 12h2"></path>
                                    <path d="m6.34 17.66-1.41 1.41"></path><path d="m19.07 4.93-1.41 1.41"></path>
                                </g>
                            </svg>
                            <!-- Luna -->
                            <svg aria-label="moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                                    <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                                </g>
                            </svg>
                        </label>

                        <!-- Toggle idioma -->
                        <label class="swap font-bold">
                            <input type="checkbox" <?= $idioma_actual === 'en' ? 'checked' : '' ?> />
                            <div class="swap-on">EN</div>
                            <div class="swap-off">ES</div>
                        </label>

                        <!-- Enlace a preferencias -->
                        <a href="preferencias.php" class="btn btn-sm btn-ghost">
                            ⚙️ <?= $tx['preferencias'] ?>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
</html>