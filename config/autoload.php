<?php
declare(strict_types=1);

spl_autoload_register(function (string $clase): void {
    $ruta = __DIR__ . '/../clases/' . $clase . '.php';
    if (is_file($ruta)) {
        require_once $ruta;
    }
});

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
