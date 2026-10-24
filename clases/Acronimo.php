<?php
declare(strict_types=1);

class Acronimo
{
    /**
     * @return string[]
     */
    public function palabras(string $entrada): array
    {
        $entrada = preg_replace('/[\-‐–—_]+/u', ' ', $entrada);

        $entrada = preg_replace('/[^\p{L}\p{N}\s]+/u', '', $entrada);

        return preg_split('/\s+/u', trim($entrada), -1, PREG_SPLIT_NO_EMPTY);
    }

    public function generar(string $entrada): string
    {
        $acronimo = '';
        foreach ($this->palabras($entrada) as $palabra) {
            $acronimo .= mb_strtoupper(mb_substr($palabra, 0, 1));
        }

        return $acronimo;
    }
}
