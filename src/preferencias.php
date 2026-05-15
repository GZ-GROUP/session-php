<?php
// RF-3: Verificar método del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // RF-1: Borrar todas las preferencias
    if (isset($_POST['borrar'])) {
        setcookie('tema',      '', time() - 3600, '/', '', false, true);
        setcookie('idioma',    '', time() - 3600, '/', '', false, true);
        setcookie('visitante', '', time() - 3600, '/', '', false, true);
        header('Location: preferencias.php');
        exit;
    }

    // RF-3: Cookies con httponly = true | RF-1: duración 30 días
    $opciones = [
        'expires'  => time() + (30 * 24 * 60 * 60),
        'path'     => '/',
        'secure'   => false,
        'httponly' => true,
        'samesite' => 'Lax',
    ];

    $tema      = $_POST['tema']      ?? 'claro';
    $idioma    = $_POST['idioma']    ?? 'es';
    $visitante = $_POST['visitante'] ?? '';

    setcookie('tema',      $tema,      $opciones);
    setcookie('idioma',    $idioma,    $opciones);
    setcookie('visitante', $visitante, $opciones);

    header('Location: index.php');
    exit;
}

// RF-3: htmlspecialchars() en toda salida de datos de usuario
$tema_actual      = htmlspecialchars($_COOKIE['tema']      ?? 'claro');
$idioma_actual    = htmlspecialchars($_COOKIE['idioma']    ?? 'es');
$visitante_actual = htmlspecialchars($_COOKIE['visitante'] ?? '');

$textos = [
    'es' => [
        'titulo'    => 'Preferencias',
        'tema'      => 'Tema visual',
        'claro'     => 'Claro',
        'oscuro'    => 'Oscuro',
        'idioma'    => 'Idioma',
        'es_label'  => 'Español',
        'en_label'  => 'Inglés',
        'visitante' => 'Nombre de visitante (sin login)',
        'guardar'   => 'Guardar preferencias',
        'borrar'    => 'Borrar todas las preferencias',
        'volver'    => '← Volver al inicio',
    ],
    'en' => [
        'titulo'    => 'Preferences',
        'tema'      => 'Visual theme',
        'claro'     => 'Light',
        'oscuro'    => 'Dark',
        'idioma'    => 'Language',
        'es_label'  => 'Spanish',
        'en_label'  => 'English',
        'visitante' => 'Visitor name (no login)',
        'guardar'   => 'Save preferences',
        'borrar'    => 'Delete all preferences',
        'volver'    => '← Back to home',
    ],
];
$t = $textos[$idioma_actual] ?? $textos['es'];
?>
<!DOCTYPE html>
<html lang="<?= $idioma_actual ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['titulo'] ?></title>
    <link rel="stylesheet" href="estilos.php">
</head>
<body class="tema-<?= $tema_actual ?>">

<div class="contenedor">
    <h1><?= $t['titulo'] ?></h1>

    <form method="POST" action="preferencias.php">

        <div class="campo">
            <label><?= $t['tema'] ?></label>
            <div class="opciones-radio">
                <label>
                    <input type="radio" name="tema" value="claro"
                        <?= $tema_actual === 'claro' ? 'checked' : '' ?>>
                    <?= $t['claro'] ?>
                </label>
                <label>
                    <input type="radio" name="tema" value="oscuro"
                        <?= $tema_actual === 'oscuro' ? 'checked' : '' ?>>
                    <?= $t['oscuro'] ?>
                </label>
            </div>
        </div>

        <div class="campo">
            <label><?= $t['idioma'] ?></label>
            <div class="opciones-radio">
                <label>
                    <input type="radio" name="idioma" value="es"
                        <?= $idioma_actual === 'es' ? 'checked' : '' ?>>
                    <?= $t['es_label'] ?>
                </label>
                <label>
                    <input type="radio" name="idioma" value="en"
                        <?= $idioma_actual === 'en' ? 'checked' : '' ?>>
                    <?= $t['en_label'] ?>
                </label>
            </div>
        </div>

        <div class="campo">
            <label for="visitante"><?= $t['visitante'] ?></label>
            <input type="text" id="visitante" name="visitante"
                   value="<?= $visitante_actual ?>" maxlength="60">
        </div>

        <div class="botones">
            <button type="submit"><?= $t['guardar'] ?></button>
            <button type="submit" name="borrar" value="1" class="btn-borrar">
                <?= $t['borrar'] ?>
            </button>
        </div>

    </form>

    <p><a href="index.php"><?= $t['volver'] ?></a></p>
</div>

</body>
</html>
