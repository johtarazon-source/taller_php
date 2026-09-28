<?php
declare(strict_types=1);

class Binario
{
    /** Convierte un entero a binario con divisiones sucesivas entre 2. */
    public function convertir(int $numero): string
    {
        $valor = abs($numero);
        if ($valor === 0) {
            return '0';
        }

        $binario = '';
        foreach ($this->obtenerPasos($valor) as $paso) {
            // Los residuos se leen de abajo hacia arriba.
            $binario = $paso['residuo'] . $binario;
        }

        return ($numero < 0 ? '-' : '') . $binario;
    }

    /** Divisiones sucesivas del valor absoluto del número, en el orden en que se hacen. */
    public function obtenerPasos(int $numero): array
    {
        $n = abs($numero);
        if ($n === 0) {
            return [];
        }

        $pasos = [];
        $paso  = 1;

        while ($n > 0) {
            $cociente = intdiv($n, 2);
            $pasos[] = [
                'paso'     => $paso,
                'numero'   => $n,
                'cociente' => $cociente,
                'residuo'  => $n % 2,
            ];
            $n = $cociente;
            $paso++;
        }

        return $pasos;
    }

    /** Agrupa los bits de 4 en 4 desde la derecha: 101101 → 0010 1101 */
    public function grupos(string $binario): array
    {
        $bits = ltrim($binario, '-');
        $largo = (int) ceil(strlen($bits) / 4) * 4;
        return str_split(str_pad($bits, $largo, '0', STR_PAD_LEFT), 4);
    }
}
