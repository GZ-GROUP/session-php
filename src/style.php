<?php
// RF-1: Este archivo sirve el CSS aplicando el tema desde la cookie
header('Content-Type: text/css');
$tema = htmlspecialchars($_COOKIE['tema'] ?? 'claro');
?>

/* ============================
   Variables por tema
   ============================ */
:root {
    <?php if ($tema === 'oscuro'): ?>
    --bg:        #1a1a2e;
    --bg2:       #16213e;
    --superficie:#0f3460;
    --texto:     #e0e0e0;
    --texto-sec: #a0a0b0;
    --acento:    #e94560;
    --acento2:   #0f3460;
    --borde:     #334;
    --sombra:    rgba(0,0,0,.5);
    --btn-texto: #fff;
    <?php else: ?>
    --bg:        #f5f7fa;
    --bg2:       #ffffff;
    --superficie:#e9edf5;
    --texto:     #1a1a2e;
    --texto-sec: #5a5a7a;
    --acento:    #3a5bd9;
    --acento2:   #d0d8f5;
    --borde:     #ccd;
    --sombra:    rgba(0,0,0,.1);
    --btn-texto: #ffffff;
    <?php endif; ?>
}

/* ============================
   Base
   ============================ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: var(--bg);
    color: var(--texto);
    min-height: 100vh;
    padding: 2rem 1rem;
    transition: background .3s, color .3s;
}

.contenedor {
    max-width: 700px;
    margin: 0 auto;
    background: var(--bg2);
    border-radius: 12px;
    padding: 2.5rem;
    box-shadow: 0 4px 24px var(--sombra);
}

.contenedor-login {
    max-width: 420px;
}

h1 {
    font-size: 2rem;
    margin-bottom: 1.5rem;
    color: var(--acento);
}

/* ============================
   Formulario
   ============================ */
.campo {
    margin-bottom: 1.4rem;
}

.campo label {
    display: block;
    font-weight: 600;
    margin-bottom: .5rem;
    color: var(--texto-sec);
    font-size: .9rem;
    text-transform: uppercase;
    letter-spacing: .05em;
}

.campo input[type="text"],
.campo input[type="password"] {
    width: 100%;
    padding: .65rem 1rem;
    border: 2px solid var(--borde);
    border-radius: 8px;
    background: var(--superficie);
    color: var(--texto);
    font-size: 1rem;
    transition: border-color .2s;
}

.campo input:focus {
    outline: none;
    border-color: var(--acento);
}

.opciones-radio {
    display: flex;
    gap: 1.5rem;
}

.opciones-radio label {
    display: flex;
    align-items: center;
    gap: .4rem;
    font-weight: 400;
    text-transform: none;
    letter-spacing: 0;
    font-size: 1rem;
    color: var(--texto);
    cursor: pointer;
}

.opciones-radio input[type="radio"] {
    accent-color: var(--acento);
    width: 1.1rem;
    height: 1.1rem;
}

/* ============================
   Botones
   ============================ */
.botones {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-top: 1.5rem;
}

button, .btn {
    display: inline-block;
    padding: .7rem 1.6rem;
    background: var(--acento);
    color: var(--btn-texto);
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: opacity .2s, transform .1s;
}

button:hover, .btn:hover { opacity: .85; transform: translateY(-1px); }
button:active, .btn:active { transform: translateY(0); }

.btn-borrar {
    background: #c0392b;
}

.btn-logout {
    background: #555;
}

/* ============================
   Alerta de error
   ============================ */
.alerta-error {
    background: #fde8e8;
    color: #c0392b;
    border: 1px solid #f5b7b1;
    border-radius: 8px;
    padding: .8rem 1rem;
    margin-bottom: 1.2rem;
    font-weight: 600;
}

/* ============================
   Info cookies (index)
   ============================ */
.info-cookies {
    background: var(--superficie);
    border-radius: 8px;
    padding: 1rem 1.4rem;
    margin: 1.2rem 0;
    border-left: 4px solid var(--acento);
}

.info-cookies p { margin: .3rem 0; }

/* ============================
   Dashboard
   ============================ */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 2rem;
}

.subtitulo { color: var(--texto-sec); margin-top: .3rem; }

.tarjetas {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.tarjeta {
    background: var(--superficie);
    border-radius: 10px;
    padding: 1.2rem;
    text-align: center;
    border: 1px solid var(--borde);
}

.tarjeta .icono { font-size: 2rem; }
.tarjeta h2 { font-size: .85rem; color: var(--texto-sec); margin: .4rem 0 .3rem; text-transform: uppercase; letter-spacing: .05em; }
.tarjeta p  { font-size: .95rem; font-weight: 600; }
.numero-grande { font-size: 2.5rem; color: var(--acento); }

/* ============================
   Links
   ============================ */
a { color: var(--acento); }
a:hover { opacity: .75; }

p { margin-top: 1rem; }
