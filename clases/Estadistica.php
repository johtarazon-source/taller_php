<?php
declare(strict_types=1);

class Estadistica
{
    /** @var float[] */
    private array $numeros;

    /** @param float[] $numeros */
    public function __construct(array $numeros)
    {
        $this->numeros = array_values(array_filter($numeros, fn($n) => is_numeric($n)));
    }

    public function promedio(): float
    {
        if (count($this->numeros) === 0) {
            return 0;
        }
        return array_sum($this->numeros) / count($this->numeros);
    }

    public function mediana(): float
    {
        if (count($this->numeros) === 0) {
            return 0;
        }

        $sorted = $this->numeros;
        sort($sorted);
        $count = count($sorted);
        $mid   = floor($count / 2);

        if ($count % 2 === 1) {
            return $sorted[$mid];
        }

        return ($sorted[$mid - 1] + $sorted[$mid]) / 2;
    }

    /**
     * Veces que aparece cada valor, ordenado de menor a mayor.
     *
     * @return array<string, int>
     */
    public function frecuencias(): array
    {
        $ordenados = $this->numeros;
        sort($ordenados);

        $frecuencias = [];
        foreach ($ordenados as $n) {
            $clave = self::formatear((float) $n);
            $frecuencias[$clave] = ($frecuencias[$clave] ?? 0) + 1;
        }

        return $frecuencias;
    }

    /**
     * Valores que más se repiten. Si todos se repiten la misma cantidad
     * de veces (por ejemplo, ninguno se repite) no hay moda.
     *
     * @return string[]
     */
    public function moda(): array
    {
        if (empty($this->numeros)) {
            return [];
        }

        $frecuencias = $this->frecuencias();
        $maxFrecuencia = max($frecuencias);

        if (count($frecuencias) > 1 && min($frecuencias) === $maxFrecuencia) {
            return [];
        }

        return array_map('strval', array_keys($frecuencias, $maxFrecuencia, true));
    }

    /** Muestra un número sin ceros sobrantes. */
    public static function formatear(float $numero, int $decimales = 10): string
    {
        $texto = rtrim(rtrim(sprintf('%.' . $decimales . 'F', $numero), '0'), '.');
        return $texto === '-0' ? '0' : $texto;
    }

    public function contar(): int
    {
        return count($this->numeros);
    }

    /** @return float[] */
    public function obtenerNumeros(): array
    {
        return $this->numeros;
    }
}
