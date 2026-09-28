<?php
declare(strict_types=1);

class Calculadora
{
    public const HISTORIAL_KEY = 'calc_historial';

    public const SIMBOLOS = [
        'suma'           => '+',
        'resta'          => '−',
        'multiplicacion' => '×',
        'division'       => '÷',
        'porcentaje'     => '%',
    ];

    public static function sumar(float $a, float $b): float
    {
        return $a + $b;
    }

    public static function restar(float $a, float $b): float
    {
        return $a - $b;
    }

    public static function multiplicar(float $a, float $b): float
    {
        return $a * $b;
    }

    public static function dividir(float $a, float $b): float
    {
        if ($b == 0) {
            throw new Exception('División por cero no permitida');
        }
        return $a / $b;
    }

    /** a % b = el a por ciento de b (por ejemplo 20 % 150 = 30). */
    public static function porcentaje(float $valor, float $porcentaje): float
    {
        return ($valor / 100) * $porcentaje;
    }

    /** Ejecuta la operación indicada por su nombre. */
    public static function operar(float $a, string $operador, float $b): float
    {
        return match ($operador) {
            'suma'           => self::sumar($a, $b),
            'resta'          => self::restar($a, $b),
            'multiplicacion' => self::multiplicar($a, $b),
            'division'       => self::dividir($a, $b),
            'porcentaje'     => self::porcentaje($a, $b),
            default          => throw new Exception('Operación no válida'),
        };
    }

    /** Muestra un número sin ceros sobrantes (0.30000000000000004 → 0.3). */
    public static function formatear(float $numero): string
    {
        $texto = rtrim(rtrim(sprintf('%.10F', $numero), '0'), '.');
        return $texto === '-0' ? '0' : $texto;
    }

    public static function agregarHistorial(string $operacion, float $resultado): void
    {
        if (!isset($_SESSION[self::HISTORIAL_KEY])) {
            $_SESSION[self::HISTORIAL_KEY] = [];
        }

        $_SESSION[self::HISTORIAL_KEY][] = [
            'operacion' => $operacion,
            'resultado' => $resultado,
            'fecha'     => date('H:i:s'),
        ];

        if (count($_SESSION[self::HISTORIAL_KEY]) > 50) {
            array_shift($_SESSION[self::HISTORIAL_KEY]);
        }
    }

    public static function obtenerHistorial(): array
    {
        return isset($_SESSION[self::HISTORIAL_KEY]) ? $_SESSION[self::HISTORIAL_KEY] : [];
    }

    public static function limpiarHistorial(): void
    {
        unset($_SESSION[self::HISTORIAL_KEY]);
    }
}
