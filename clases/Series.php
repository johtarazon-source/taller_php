<?php
declare(strict_types=1);

class Series
{
    public function fibonacci(int $n): array
    {
        if ($n <= 0) {
            return [];
        }
        if ($n === 1) {
            return [0];
        }

        $serie = [0, 1];
        for ($i = 2; $i < $n; $i++) {
            $serie[] = $serie[$i - 1] + $serie[$i - 2];
        }

        return $serie;
    }

    public function factorial(int $n): array
    {
        if ($n < 0) {
            return [];
        }

        $resultado = [1];
        for ($i = 2; $i <= $n; $i++) {
            $resultado[] = $this->multiplicar($resultado[count($resultado) - 1], $i);
        }

        return $resultado;
    }

    private function multiplicar(int|string $a, int $b): int|string
    {
        if (is_string($a)) {
            return bcmul($a, (string) $b);
        }
        $resultado = $a * $b;
        if ($resultado > PHP_INT_MAX) {
            return bcmul((string) $a, (string) $b);
        }
        return $resultado;
    }

    public function obtenerFactorialTotal(int $n): int|string
    {
        if ($n < 0) {
            return 0;
        }
        if ($n <= 1) {
            return 1;
        }

        $resultado = 1;
        for ($i = 2; $i <= $n; $i++) {
            $resultado = $this->multiplicar($resultado, $i);
        }

        return $resultado;
    }
}
