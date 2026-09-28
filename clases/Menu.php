<?php
declare(strict_types=1);

class Menu
{
    private array $aplicaciones = [
        [
            'clave'       => 'acronimo',
            'titulo'      => 'Acrónimos',
            'descripcion' => 'Convierte frases largas como "Portable Network Graphics" en su acrónimo.',
            'color'       => '#ff6b6b',
            'estrellas'   => 1,
            'dibujo'      => [
                '..####..',
                '.##..##.',
                '##....##',
                '##....##',
                '########',
                '##....##',
                '##....##',
                '........',
            ],
        ],
        [
            'clave'       => 'series',
            'titulo'      => 'Fibonacci y Factorial',
            'descripcion' => 'Genera la sucesión de Fibonacci o la serie factorial de un número.',
            'color'       => '#ffd93d',
            'estrellas'   => 2,
            'dibujo'      => [
                '..####..',
                '.##..##.',
                '##.##.##',
                '##.##.##',
                '##.##.##',
                '##.##.##',
                '.##..##.',
                '..####..',
            ],
        ],
        [
            'clave'       => 'estadistica',
            'titulo'      => 'Estadística',
            'descripcion' => 'Promedio, mediana y moda de una serie de números reales.',
            'color'       => '#6bff95',
            'estrellas'   => 2,
            'dibujo'      => [
                '......##',
                '......##',
                '...##.##',
                '...##.##',
                '##.##.##',
                '##.##.##',
                '##.##.##',
                '########',
            ],
        ],
        [
            'clave'       => 'conjuntos',
            'titulo'      => 'Conjuntos',
            'descripcion' => 'Unión, intersección y diferencias A−B y B−A de dos conjuntos.',
            'color'       => '#4dd8ff',
            'estrellas'   => 2,
            'dibujo'      => [
                '#####...',
                '#...#...',
                '#..#####',
                '#..##..#',
                '#####..#',
                '...#...#',
                '...#####',
                '........',
            ],
        ],
        [
            'clave'       => 'binario',
            'titulo'      => 'Decimal a Binario',
            'descripcion' => 'Convierte un número entero a binario mostrando cada división.',
            'color'       => '#8c7bff',
            'estrellas'   => 1,
            'dibujo'      => [
                '.##...#.',
                '#..#.##.',
                '#..#..#.',
                '#..#..#.',
                '#..#..#.',
                '#..#..#.',
                '.##..###',
                '........',
            ],
        ],
        [
            'clave'       => 'arbol',
            'titulo'      => 'Árbol Binario',
            'descripcion' => 'Reconstruye y dibuja un árbol a partir de sus recorridos.',
            'color'       => '#d77bff',
            'estrellas'   => 3,
            'dibujo'      => [
                '...##...',
                '..####..',
                '.######.',
                '########',
                '.######.',
                '...##...',
                '...##...',
                '..####..',
            ],
        ],
        [
            'clave'       => 'calculadora',
            'titulo'      => 'Calculadora',
            'descripcion' => 'Operaciones básicas y porcentaje con historial persistente.',
            'color'       => '#ff7bc0',
            'estrellas'   => 2,
            'dibujo'      => [
                '########',
                '#......#',
                '########',
                '#.#.#.##',
                '########',
                '#.#.#.##',
                '########',
                '........',
            ],
        ],
    ];

    public function obtenerAplicaciones(): array
    {
        return $this->aplicaciones;
    }

    public function buscar(string $clave): ?array
    {
        foreach ($this->aplicaciones as $app) {
            if ($app['clave'] === $clave) {
                return $app;
            }
        }
        return null;
    }

    /** Posición (1..7) de la aplicación dentro del menú. */
    public function numero(string $clave): int
    {
        foreach ($this->aplicaciones as $i => $app) {
            if ($app['clave'] === $clave) {
                return $i + 1;
            }
        }
        return 0;
    }
}
