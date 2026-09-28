<?php
declare(strict_types=1);

class Conjuntos
{
    private array $A;
    private array $B;

    public function __construct(array $a, array $b)
    {
        $this->A = array_values(array_unique(array_filter($a, fn($x) => is_numeric($x))));
        $this->B = array_values(array_unique(array_filter($b, fn($x) => is_numeric($x))));
        sort($this->A);
        sort($this->B);
    }

    public function union(): array
    {
        $result = array_unique(array_merge($this->A, $this->B));
        sort($result);
        return array_values($result);
    }

    public function interseccion(): array
    {
        $result = array_intersect($this->A, $this->B);
        sort($result);
        return array_values($result);
    }

    public function diferencia_A_B(): array
    {
        $result = array_diff($this->A, $this->B);
        sort($result);
        return array_values($result);
    }

    public function diferencia_B_A(): array
    {
        $result = array_diff($this->B, $this->A);
        sort($result);
        return array_values($result);
    }

    public function obtenerA(): array
    {
        return $this->A;
    }

    public function obtenerB(): array
    {
        return $this->B;
    }
}
