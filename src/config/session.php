<?php

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'httponly' => true,
    'secure' => false,
    'samesite' => 'Lax'
]);

session_start();