<?php

require_once "./config/session.php";

if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
    exit;
}

$_SESSION['visitas']++;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="p-10 bg-base-200 min-h-screen">

<div class="card bg-base-100 shadow-xl p-8">

    <h1 class="text-3xl font-bold mb-4">
        Bienvenido
        <?= htmlspecialchars($_SESSION['nombre']) ?>
    </h1>

    <p class="mb-2">
        <strong>Rol:</strong>
        <?= htmlspecialchars($_SESSION['rol']) ?>
    </p>

    <p class="mb-2">
        <strong>Inicio de sesión:</strong>
        <?= htmlspecialchars($_SESSION['inicio']) ?>
    </p>

    <p class="mb-5">
        <strong>Visitas:</strong>
        <?= htmlspecialchars($_SESSION['visitas']) ?>
    </p>

    <a href="logout.php" class="btn btn-error w-fit">
        Cerrar Sesión
    </a>

</div>

</body>
</html>