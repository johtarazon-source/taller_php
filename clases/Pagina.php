<?php
declare(strict_types=1);

class Pagina
{
    public static function e(mixed $valor): string
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }

    public static function error(?string $mensaje): void
    {
        if ($mensaje === null || $mensaje === '') {
            return;
        }
        echo '<div class="mensaje mensaje-error revelar" role="alert"><strong>¡AUCH! −1 ♥</strong><span>'
            . self::e($mensaje) . '</span></div>';
    }
}
