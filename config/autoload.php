<?php
declare(strict_types=1);

// Hora local (UTC−5) para el historial de la calculadora.
date_default_timezone_set('America/Bogota');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
