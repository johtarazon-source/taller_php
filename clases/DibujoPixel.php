<?php
declare(strict_types=1);

/**
 * Dibuja una figura pixelada en SVG a partir de un mapa de texto,
 * donde '#' es un pixel encendido y '.' un pixel vacío.
 */
class DibujoPixel
{
    /** @param string[] $mapa */
    public function __construct(
        private array $mapa,
        private string $color = 'currentColor'
    ) {
    }

    public function dibujar(int $tamano = 48): string
    {
        $alto = count($this->mapa);
        $ancho = max(array_map('strlen', $this->mapa));
        $pixeles = '';

        foreach ($this->mapa as $y => $fila) {
            for ($x = 0; $x < strlen($fila); $x++) {
                if ($fila[$x] === '#') {
                    $pixeles .= '<rect x="' . $x . '" y="' . $y . '" width="1" height="1"/>';
                }
            }
        }

        $color = htmlspecialchars($this->color, ENT_QUOTES, 'UTF-8');

        return '<svg class="dibujo-pixel" width="' . $tamano . '" height="' . $tamano . '" viewBox="0 0 ' . $ancho . ' ' . $alto
            . '" fill="' . $color . '" shape-rendering="crispEdges" aria-hidden="true">' . $pixeles . '</svg>';
    }
}
