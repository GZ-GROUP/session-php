<?php
require_once "./config/db.php";
require_once "./config/session.php";

// Si ya hay sesión activa, redirigir al dashboard
if (isset($_SESSION['nombre'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo   = trim($_POST['correo']   ?? '');
    // ✅ FIX 1: NO hacer trim() al password; espacios son parte válida de la clave
    $password = $_POST['password'] ?? '';

    if (!empty($correo) && !empty($password)) {
        // ✅ FIX 2: SELECT solo las columnas necesarias, no SELECT *
        $stmt = $conn->prepare(
            "SELECT nombre, rol, password FROM usuarios WHERE correo = ?"
        );
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();

            if (password_verify($password, $usuario['password'])) {
                // ✅ FIX 3: session_regenerate_id va ANTES de escribir en $_SESSION
                session_regenerate_id(true);

                // ✅ FIX 4 (CRÍTICO): dashboard.php verifica $_SESSION['nombre'],
                // no 'usuario' — la clave debe coincidir en ambos archivos
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['rol']    = $usuario['rol'];
                $_SESSION['inicio'] = date('Y-m-d H:i:s');
                $_SESSION['visitas'] = 0;

                header("Location: dashboard.php");
                exit;
            }
        }
        $stmt->close();

        // ✅ FIX 5: Mensaje genérico — no revelar si el correo existe o no
        $error = "Correo o contraseña inválidos.";
    }
}

// Leer tema de preferencias para data-theme
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
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body class="min-h-screen flex items-center justify-center bg-base-200">

<div class="card bg-base-100 shadow-xl p-8 w-full max-w-sm mx-4">

    <h1 class="text-2xl font-bold mb-1">Iniciar Sesión</h1>
    <p class="text-base-content/60 text-sm mb-6">Ingresa tus credenciales para continuar</p>

    <?php if ($error): ?>
    <div role="alert" class="alert alert-error mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span><?= htmlspecialchars($error) ?></span>
    </div>
    <?php endif; ?>

    <form method="POST" action="login.php" novalidate class="flex flex-col gap-4">

        <div>
            <label class="label pb-1">
                <span class="label-text font-medium">Correo electrónico</span>
            </label>
            <input
                type="email"
                name="correo"
                placeholder="correo@ejemplo.com"
                value="<?= isset($_POST['correo']) ? htmlspecialchars(trim($_POST['correo'])) : '' ?>"
                class="input input-bordered w-full"
                required
                autocomplete="email"
            >
        </div>

        <div>
            <label class="label pb-1">
                <span class="label-text font-medium">Contraseña</span>
            </label>
            <input
                type="password"
                name="password"
                placeholder="Tu contraseña"
                class="input input-bordered w-full"
                required
                autocomplete="current-password"
            >
        </div>

        <button type="submit" class="btn btn-primary w-full mt-2">
            Ingresar
        </button>
    </form>

    <p class="text-center text-sm text-base-content/60 mt-6">
        ¿No tienes cuenta?
        <a href="signup.php" class="link link-primary font-medium">Regístrate</a>
    </p>

</div>

</body>
</html>