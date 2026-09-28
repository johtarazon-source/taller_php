<?php
declare(strict_types=1);

class Acronimo
{
    /**
     * Separa la frase en palabras: los guiones cuentan como separadores
     * y el resto de signos de puntuación se eliminan.
     *
     * @return string[]
     */
    public function palabras(string $entrada): array
    {
        // Reemplazar guiones (y guion bajo) por espacios para tratarlos como separadores
        $entrada = preg_replace('/[\-‐–—_]+/u', ' ', $entrada);

        // Remover todo excepto letras, números y espacios
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
