<?php

require_once "./config/db.php";
require_once "./config/session.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $correo = trim($_POST['correo'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($correo) && !empty($password)) {

        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {

            $usuario = $resultado->fetch_assoc();

            if (password_verify($password, $usuario['password'])) {

                session_regenerate_id(true);

                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['rol'] = $usuario['rol'];
                $_SESSION['inicio'] = date('Y-m-d H:i:s');

                $_SESSION['visitas'] = 0;

                header("Location: dashboard.php");
                exit;
            }
        }

        $error = "Correo o contraseña inválidos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-base-200">

<form method="POST" class="card bg-base-100 shadow-xl p-8 w-96">

    <h1 class="text-2xl font-bold mb-5">Iniciar Sesión</h1>

    <?php if ($error): ?>
        <div class="alert alert-error mb-4">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <input
        type="email"
        name="correo"
        placeholder="Correo"
        class="input input-bordered mb-3"
        required
    >

    <input
        type="password"
        name="password"
        placeholder="Contraseña"
        class="input input-bordered mb-4"
        required
    >

    <button class="btn btn-primary">
        Ingresar
    </button>

</form>

</body>
</html>