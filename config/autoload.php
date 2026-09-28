<?php
declare(strict_types=1);

spl_autoload_register(function (string $clase): void {
    $ruta = __DIR__ . '/../clases/' . $clase . '.php';
    if (is_file($ruta)) {
        require_once $ruta;
    }
});

// Hora local (UTC−5) para el historial de la calculadora.
date_default_timezone_set('America/Bogota');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
