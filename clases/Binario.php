<?php
declare(strict_types=1);

class Binario
{
    public function convertir(int $numero): string
    {
        $valor = abs($numero);
        if ($valor === 0) {
            return '0';
        }

        $binario = '';
        foreach ($this->obtenerPasos($valor) as $paso) {
            $binario = $paso['residuo'] . $binario;
        }

        return ($numero < 0 ? '-' : '') . $binario;
    }

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

    public function grupos(string $binario): array
    {
        $bits = ltrim($binario, '-');
        $largo = (int) ceil(strlen($bits) / 4) * 4;
        return str_split(str_pad($bits, $largo, '0', STR_PAD_LEFT), 4);
    }
}
