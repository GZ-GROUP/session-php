<?php
// ─── Dependencias ──────────────────────────────────────────────────────────
require_once "./config/db.php";
require_once "./config/session.php";

// Si ya hay sesión activa, ir al dashboard directamente
if (isset($_SESSION['nombre'])) {
    header("Location: dashboard.php");
    exit;
}

// ─── Leer cookies de preferencias (tema / idioma) ─────────────────────────
$tema   = in_array($_COOKIE['pref_tema']   ?? '', ['light', 'synthwave'])
        ? $_COOKIE['pref_tema'] : 'light';
$idioma = in_array($_COOKIE['pref_idioma'] ?? '', ['es', 'en'])
        ? $_COOKIE['pref_idioma'] : 'es';

// ─── Textos bilingües ──────────────────────────────────────────────────────
$textos = [
    'es' => [
        'titulo'         => 'Crear cuenta',
        'subtitulo'      => 'Regístrate para acceder al sistema',
        'lbl_nombre'     => 'Nombre completo',
        'ph_nombre'      => 'Tu nombre',
        'lbl_correo'     => 'Correo electrónico',
        'ph_correo'      => 'correo@ejemplo.com',
        'lbl_pass'       => 'Contraseña',
        'ph_pass'        => 'Mínimo 8 caracteres',
        'lbl_confirmar'  => 'Confirmar contraseña',
        'ph_confirmar'   => 'Repite tu contraseña',
        'btn_registrar'  => 'Crear cuenta',
        'ya_cuenta'      => '¿Ya tienes cuenta?',
        'ir_login'       => 'Iniciar sesión',
        'ir_login_btn'   => 'Ir al login',
        // Errores
        'err_vacios'     => 'Todos los campos son obligatorios.',
        'err_nombre'     => 'El nombre solo puede contener letras y espacios (máx. 100 caracteres).',
        'err_correo'     => 'El formato del correo electrónico no es válido.',
        'err_pass_corta' => 'La contraseña debe tener al menos 8 caracteres.',
        'err_pass_match' => 'Las contraseñas no coinciden.',
        'err_duplicado'  => 'Ese correo ya está registrado. ¿Quieres iniciar sesión?',
        'err_db'         => 'Error interno al guardar. Intenta de nuevo.',
        // Éxito
        'ok_msg'         => '¡Cuenta creada con éxito! Ya puedes iniciar sesión.',
    ],
    'en' => [
        'titulo'         => 'Create account',
        'subtitulo'      => 'Sign up to access the system',
        'lbl_nombre'     => 'Full name',
        'ph_nombre'      => 'Your name',
        'lbl_correo'     => 'Email address',
        'ph_correo'      => 'email@example.com',
        'lbl_pass'       => 'Password',
        'ph_pass'        => 'At least 8 characters',
        'lbl_confirmar'  => 'Confirm password',
        'ph_confirmar'   => 'Repeat your password',
        'btn_registrar'  => 'Create account',
        'ya_cuenta'      => 'Already have an account?',
        'ir_login'       => 'Log in',
        'ir_login_btn'   => 'Go to login',
        // Errors
        'err_vacios'     => 'All fields are required.',
        'err_nombre'     => 'Name can only contain letters and spaces (max 100 chars).',
        'err_correo'     => 'The email address format is not valid.',
        'err_pass_corta' => 'Password must be at least 8 characters.',
        'err_pass_match' => 'Passwords do not match.',
        'err_duplicado'  => 'That email is already registered. Want to log in?',
        'err_db'         => 'Internal error while saving. Please try again.',
        // Success
        'ok_msg'         => 'Account created successfully! You can now log in.',
    ],
];
$t = $textos[$idioma] ?? $textos['es'];

// ─── Estado del formulario ─────────────────────────────────────────────────
$error      = '';
$success    = false;
$val_nombre = '';
$val_correo = '';

// ─── Procesar POST ─────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre    = trim($_POST['nombre']    ?? '');
    $correo    = trim($_POST['correo']    ?? '');
    $password  = $_POST['password']       ?? '';
    $confirmar = $_POST['confirmar']      ?? '';

    // Preservar para repoblar el formulario en caso de error
    $val_nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
    $val_correo = htmlspecialchars($correo, ENT_QUOTES, 'UTF-8');

    // ── Validaciones ──────────────────────────────────────────────────────
    if ($nombre === '' || $correo === '' || $password === '' || $confirmar === '') {
        $error = $t['err_vacios'];

    } elseif (!preg_match('/^[\p{L} ]{1,100}$/u', $nombre)) {
        $error = $t['err_nombre'];

    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = $t['err_correo'];

    } elseif (strlen($password) < 8) {
        $error = $t['err_pass_corta'];

    } elseif ($password !== $confirmar) {
        $error = $t['err_pass_match'];

    } else {
        // ── Verificar correo duplicado (prepared statement) ───────────────
        $chk = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $chk->bind_param("s", $correo);
        $chk->execute();
        $chk->store_result();

        if ($chk->num_rows > 0) {
            $error = $t['err_duplicado'];
        } else {
            // ── Insertar usuario ──────────────────────────────────────────
            // RF-3: password_hash bcrypt; nombre saneado antes de persistir
            $hash_pass    = password_hash($password, PASSWORD_DEFAULT);
            $nombre_clean = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');

            $ins = $conn->prepare(
                "INSERT INTO usuarios (nombre, correo, password, rol) VALUES (?, ?, ?, 'usuario')"
            );
            $ins->bind_param("sss", $nombre_clean, $correo, $hash_pass);

            if ($ins->execute()) {
                $success = true;
            } else {
                $error = $t['err_db'];
            }
            $ins->close();
        }
        $chk->close();
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>" data-theme="<?= htmlspecialchars($tema) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($t['titulo']) ?></title>

    <!-- DaisyUI v5 + Tailwind (igual que el resto del proyecto) -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-base-200">

<div class="card bg-base-100 shadow-xl p-8 w-full max-w-md mx-4 my-8">

    <!-- ── Encabezado ──────────────────────────────────────────────────── -->
    <h1 class="text-2xl font-bold mb-1">
        <?= htmlspecialchars($t['titulo']) ?>
    </h1>
    <p class="text-base-content/60 text-sm mb-6">
        <?= htmlspecialchars($t['subtitulo']) ?>
    </p>

    <!-- ── Alerta de error ─────────────────────────────────────────────── -->
    <?php if ($error !== ''): ?>
    <div role="alert" class="alert alert-error mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span><?= htmlspecialchars($error) ?></span>
    </div>
    <?php endif; ?>

    <!-- ── Alerta de éxito + botón directo al login ─────────────────────── -->
    <?php if ($success): ?>
    <div role="alert" class="alert alert-success mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span><?= htmlspecialchars($t['ok_msg']) ?></span>
    </div>
    <a href="login.php" class="btn btn-primary w-full">
        <?= htmlspecialchars($t['ir_login_btn']) ?>
    </a>

    <?php else: ?>

    <!-- ── Formulario de registro ───────────────────────────────────────── -->
    <form method="POST" action="signup.php" novalidate class="flex flex-col gap-4">

        <!-- Nombre completo -->
        <div>
            <label class="label pb-1">
                <span class="label-text font-medium">
                    <?= htmlspecialchars($t['lbl_nombre']) ?>
                </span>
            </label>
            <input
                type="text"
                name="nombre"
                placeholder="<?= htmlspecialchars($t['ph_nombre']) ?>"
                value="<?= $val_nombre ?>"
                class="input input-bordered w-full"
                maxlength="100"
                required
                autocomplete="name"
            >
        </div>

        <!-- Correo electrónico -->
        <div>
            <label class="label pb-1">
                <span class="label-text font-medium">
                    <?= htmlspecialchars($t['lbl_correo']) ?>
                </span>
            </label>
            <input
                type="email"
                name="correo"
                placeholder="<?= htmlspecialchars($t['ph_correo']) ?>"
                value="<?= $val_correo ?>"
                class="input input-bordered w-full"
                required
                autocomplete="email"
            >
        </div>

        <!-- Contraseña -->
        <div>
            <label class="label pb-1">
                <span class="label-text font-medium">
                    <?= htmlspecialchars($t['lbl_pass']) ?>
                </span>
            </label>
            <input
                type="password"
                name="password"
                placeholder="<?= htmlspecialchars($t['ph_pass']) ?>"
                class="input input-bordered w-full"
                minlength="8"
                required
                autocomplete="new-password"
            >
        </div>

        <!-- Confirmar contraseña -->
        <div>
            <label class="label pb-1">
                <span class="label-text font-medium">
                    <?= htmlspecialchars($t['lbl_confirmar']) ?>
                </span>
            </label>
            <input
                type="password"
                name="confirmar"
                placeholder="<?= htmlspecialchars($t['ph_confirmar']) ?>"
                class="input input-bordered w-full"
                minlength="8"
                required
                autocomplete="new-password"
            >
        </div>

        <!-- Botón de envío -->
        <button type="submit" class="btn btn-primary w-full mt-2">
            <?= htmlspecialchars($t['btn_registrar']) ?>
        </button>
    </form>

    <?php endif; ?>

    <!-- ── Enlace al login ──────────────────────────────────────────────── -->
    <p class="text-center text-sm text-base-content/60 mt-6">
        <?= htmlspecialchars($t['ya_cuenta']) ?>
        <a href="login.php" class="link link-primary font-medium">
            <?= htmlspecialchars($t['ir_login']) ?>
        </a>
    </p>

</div>

</body>
</html>