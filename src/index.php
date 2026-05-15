<?php
// RF-1: Leer cookies y aplicar tema/idioma seleccionado
// RF-3: htmlspecialchars() en toda salida de datos de usuario
$tema      = htmlspecialchars($_COOKIE['tema']      ?? 'claro');
$idioma    = htmlspecialchars($_COOKIE['idioma']    ?? 'es');
$visitante = htmlspecialchars($_COOKIE['visitante'] ?? '');

$textos = [
    'es' => [
        'titulo'       => 'Inicio',
        'bienvenida'   => 'Bienvenido',
        'desc'         => 'Esta es la página principal. Tu tema y idioma se aplican desde cookies.',
        'preferencias' => 'Ajustar preferencias',
        'login'        => 'Iniciar sesión',
        'tema_actual'  => 'Tema actual',
        'idioma_actual'=> 'Idioma actual',
        'visitante_lbl'=> 'Visitante',
    ],
    'en' => [
        'titulo'       => 'Home',
        'bienvenida'   => 'Welcome',
        'desc'         => 'This is the main page. Your theme and language are applied from cookies.',
        'preferencias' => 'Adjust preferences',
        'login'        => 'Login',
        'tema_actual'  => 'Current theme',
        'idioma_actual'=> 'Current language',
        'visitante_lbl'=> 'Visitor',
    ],
];
$t = $textos[$idioma] ?? $textos['es'];
?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['titulo'] ?></title>
    <link rel="stylesheet" href="estilos.php">
</head>
<body class="tema-<?= $tema ?>">

<div class="contenedor">
    <h1>
        <?= $t['bienvenida'] ?>
        <?php if ($visitante !== ''): ?>
            , <?= $visitante ?>!
        <?php endif; ?>
    </h1>

    <p><?= $t['desc'] ?></p>

    <div class="info-cookies">
        <p><strong><?= $t['tema_actual'] ?>:</strong> <?= $tema ?></p>
        <p><strong><?= $t['idioma_actual'] ?>:</strong> <?= $idioma ?></p>
        <?php if ($visitante !== ''): ?>
            <p><strong><?= $t['visitante_lbl'] ?>:</strong> <?= $visitante ?></p>
        <?php endif; ?>
    </div>

    <div class="botones">
        <a href="preferencias.php" class="btn"><?= $t['preferencias'] ?></a>
        <a href="login.php" class="btn"><?= $t['login'] ?></a>
    </div>
</div>

</body>
</html>
