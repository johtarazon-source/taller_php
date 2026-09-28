<?php
declare(strict_types=1);

/**
 * Fondo animado estilo arcade: marcianos que marchan, tetrominós
 * que caen y Pac-Man perseguido por fantasmas.
 */
class FondoRecreativo
{
    private const INVASOR = [
        '..#.....#..',
        '...#...#...',
        '..#######..',
        '.##.###.##.',
        '###########',
        '#.#######.#',
        '#.#.....#.#',
        '...##.##...',
    ];

    private const PACMAN = [
        '..####..',
        '.######.',
        '#####...',
        '####....',
        '####....',
        '#####...',
        '.######.',
        '..####..',
    ];

    private const FANTASMA = [
        '..####..',
        '.######.',
        '#..##..#',
        '#..##..#',
        '########',
        '########',
        '########',
        '#.##.##.',
    ];

    private const TETROMINOS = [
        'I' => ['color' => '#00e5ff', 'forma' => ['####']],
        'O' => ['color' => '#ffd400', 'forma' => ['##', '##']],
        'T' => ['color' => '#b44dff', 'forma' => ['###', '.#.']],
        'S' => ['color' => '#3dff6e', 'forma' => ['.##', '##.']],
        'Z' => ['color' => '#ff3d5a', 'forma' => ['##.', '.##']],
        'L' => ['color' => '#ff9f1a', 'forma' => ['..#', '###']],
    ];

    /** [pieza, posición horizontal %, duración s, retraso s, giro] */
    private const CAIDAS = [
        ['T', 6, 22, 0, 0],
        ['I', 24, 26, -11, 90],
        ['O', 47, 20, -5, 0],
        ['S', 68, 24, -16, 0],
        ['L', 88, 21, -8, 180],
        ['Z', 36, 28, -20, 0],
    ];

    public function dibujar(): string
    {
        return $this->invasores() . $this->tetrominos() . $this->persecucion();
    }

    private function invasores(): string
    {
        $colores = ['#ff3d5a', '#3dff6e', '#00e5ff', '#ffd400', '#b44dff'];
        $html = '<div class="invasores">';
        foreach ($colores as $color) {
            $html .= (new DibujoPixel(self::INVASOR, $color))->dibujar(44);
        }
        return $html . '</div>';
    }

    private function tetrominos(): string
    {
        $html = '';
        foreach (self::CAIDAS as [$letra, $izquierda, $duracion, $retraso, $giro]) {
            $html .= '<div class="tetromino" style="left: ' . $izquierda . '%; --duracion: ' . $duracion . 's; --retraso: '
                . $retraso . 's; --giro: ' . $giro . 'deg;">' . $this->tetromino($letra, 24) . '</div>';
        }
        return $html;
    }

    private function persecucion(): string
    {
        $fantasmas = '';
        foreach (['#ff3d5a', '#ff9ff3', '#00e5ff', '#ff9f1a'] as $color) {
            $fantasmas .= (new DibujoPixel(self::FANTASMA, $color))->dibujar(34);
        }

        return '<div class="laberinto"><div class="puntos"></div><div class="persecucion">'
            . '<span class="pacman">' . (new DibujoPixel(self::PACMAN, '#ffd400'))->dibujar(38) . '</span>'
            . $fantasmas . '</div></div>';
    }

    private function tetromino(string $letra, int $celda): string
    {
        $pieza = self::TETROMINOS[$letra];
        $celdas = '';
        foreach ($pieza['forma'] as $y => $fila) {
            for ($x = 0; $x < strlen($fila); $x++) {
                if ($fila[$x] === '#') {
                    $celdas .= $this->bloque($x, $y, $pieza['color']);
                }
            }
        }

        $ancho = strlen($pieza['forma'][0]);
        $alto = count($pieza['forma']);

        return '<svg width="' . ($ancho * $celda) . '" height="' . ($alto * $celda) . '" viewBox="0 0 ' . $ancho . ' ' . $alto
            . '" shape-rendering="crispEdges">' . $celdas . '</svg>';
    }

    /** Un bloque con relieve: brillo arriba/izquierda y sombra abajo/derecha. */
    private function bloque(int $x, int $y, string $color): string
    {
        $x2 = $x + 1;
        $y2 = $y + 1;
        $a = 0.16;

        return '<rect x="' . $x . '" y="' . $y . '" width="1" height="1" fill="' . $color . '"/>'
            . '<polygon points="' . "$x,$y $x2,$y " . ($x2 - $a) . ',' . ($y + $a) . ' ' . ($x + $a) . ',' . ($y + $a) . ' '
            . ($x + $a) . ',' . ($y2 - $a) . " $x,$y2" . '" fill="#fff" fill-opacity=".45"/>'
            . '<polygon points="' . "$x2,$y2 $x,$y2 " . ($x + $a) . ',' . ($y2 - $a) . ' ' . ($x2 - $a) . ',' . ($y2 - $a) . ' '
            . ($x2 - $a) . ',' . ($y + $a) . " $x2,$y" . '" fill="#000" fill-opacity=".4"/>';
    }
}
