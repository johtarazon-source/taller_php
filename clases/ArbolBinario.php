<?php
declare(strict_types=1);

class NodoArbol
{
    public string $valor;
    public ?NodoArbol $izq = null;
    public ?NodoArbol $der = null;

    public function __construct(string $valor)
    {
        $this->valor = $valor;
    }
}

class ArbolBinario
{
    private ?NodoArbol $raiz = null;

    public function construirPreIn(array $preorden, array $inorden): void
    {
        $this->raiz = $this->construirPreInRecursivo($preorden, $inorden, 0, count($preorden) - 1, 0, count($inorden) - 1);
    }

    private function construirPreInRecursivo(array $preorden, array $inorden, int $preStart, int $preEnd, int $inStart, int $inEnd): ?NodoArbol
    {
        if ($preStart > $preEnd || $inStart > $inEnd) {
            return null;
        }

        $raiz = new NodoArbol($preorden[$preStart]);
        $inIndex = array_search($preorden[$preStart], array_slice($inorden, $inStart, $inEnd - $inStart + 1), true);
        if ($inIndex === false) {
            $inIndex = 0;
        }
        $inIndex += $inStart;
        $leftCount = $inIndex - $inStart;

        $raiz->izq = $this->construirPreInRecursivo($preorden, $inorden, $preStart + 1, $preStart + $leftCount, $inStart, $inIndex - 1);
        $raiz->der = $this->construirPreInRecursivo($preorden, $inorden, $preStart + $leftCount + 1, $preEnd, $inIndex + 1, $inEnd);

        return $raiz;
    }

    public function construirInPost(array $inorden, array $postorden): void
    {
        $this->raiz = $this->construirInPostRecursivo($inorden, $postorden, 0, count($inorden) - 1, 0, count($postorden) - 1);
    }

    private function construirInPostRecursivo(array $inorden, array $postorden, int $inStart, int $inEnd, int $postStart, int $postEnd): ?NodoArbol
    {
        if ($inStart > $inEnd || $postStart > $postEnd) {
            return null;
        }

        $raiz = new NodoArbol($postorden[$postEnd]);
        $inIndex = array_search($postorden[$postEnd], array_slice($inorden, $inStart, $inEnd - $inStart + 1), true);
        if ($inIndex === false) {
            $inIndex = 0;
        }
        $inIndex += $inStart;
        $leftCount = $inIndex - $inStart;

        $raiz->izq = $this->construirInPostRecursivo($inorden, $postorden, $inStart, $inIndex - 1, $postStart, $postStart + $leftCount - 1);
        $raiz->der = $this->construirInPostRecursivo($inorden, $postorden, $inIndex + 1, $inEnd, $postStart + $leftCount, $postEnd - 1);

        return $raiz;
    }

    public function construirPrePost(array $preorden, array $postorden): void
    {
        $this->raiz = $this->construirPrePostRecursivo($preorden, $postorden, 0, count($preorden) - 1, 0, count($postorden) - 1);
    }

    private function construirPrePostRecursivo(array $preorden, array $postorden, int $preStart, int $preEnd, int $postStart, int $postEnd): ?NodoArbol
    {
        if ($preStart > $preEnd || $postStart > $postEnd) {
            return null;
        }

        $raiz = new NodoArbol($preorden[$preStart]);
        if ($preStart === $preEnd) {
            return $raiz;
        }

        $leftVal = $preorden[$preStart + 1];
        $postIndex = array_search($leftVal, array_slice($postorden, $postStart, $postEnd - $postStart), true);
        if ($postIndex === false) {
            $postIndex = 0;
        }
        $postIndex += $postStart;
        $leftCount = $postIndex - $postStart + 1;

        $raiz->izq = $this->construirPrePostRecursivo($preorden, $postorden, $preStart + 1, $preStart + $leftCount, $postStart, $postIndex);
        $raiz->der = $this->construirPrePostRecursivo($preorden, $postorden, $preStart + $leftCount + 1, $preEnd, $postIndex + 1, $postEnd - 1);

        return $raiz;
    }

    public function preorden(): string
    {
        $resultado = [];
        $this->preordenRecursivo($this->raiz, $resultado);
        return implode(' → ', $resultado);
    }

    private function preordenRecursivo(?NodoArbol $nodo, array &$resultado): void
    {
        if ($nodo === null) {
            return;
        }
        $resultado[] = $nodo->valor;
        $this->preordenRecursivo($nodo->izq, $resultado);
        $this->preordenRecursivo($nodo->der, $resultado);
    }

    public function inorden(): string
    {
        $resultado = [];
        $this->inordenRecursivo($this->raiz, $resultado);
        return implode(' → ', $resultado);
    }

    private function inordenRecursivo(?NodoArbol $nodo, array &$resultado): void
    {
        if ($nodo === null) {
            return;
        }
        $this->inordenRecursivo($nodo->izq, $resultado);
        $resultado[] = $nodo->valor;
        $this->inordenRecursivo($nodo->der, $resultado);
    }

    public function postorden(): string
    {
        $resultado = [];
        $this->postordenRecursivo($this->raiz, $resultado);
        return implode(' → ', $resultado);
    }

    private function postordenRecursivo(?NodoArbol $nodo, array &$resultado): void
    {
        if ($nodo === null) {
            return;
        }
        $this->postordenRecursivo($nodo->izq, $resultado);
        $this->postordenRecursivo($nodo->der, $resultado);
        $resultado[] = $nodo->valor;
    }

    /**
     * Comprueba que dos recorridos tengan los mismos valores, sin repetir.
     * Devuelve el mensaje de error o null si son válidos.
     */
    public static function validar(array $recorrido1, array $recorrido2): ?string
    {
        if (count($recorrido1) !== count(array_unique($recorrido1)) || count($recorrido2) !== count(array_unique($recorrido2))) {
            return 'Los valores de los nodos no pueden repetirse.';
        }
        if (count($recorrido1) !== count($recorrido2)) {
            return 'Los recorridos deben tener la misma cantidad de nodos.';
        }
        $a = $recorrido1;
        $b = $recorrido2;
        sort($a);
        sort($b);
        if ($a !== $b) {
            return 'Los recorridos deben contener exactamente los mismos nodos.';
        }
        return null;
    }

    public function contarNodos(): int
    {
        return $this->contar($this->raiz);
    }

    private function contar(?NodoArbol $nodo): int
    {
        return $nodo === null ? 0 : 1 + $this->contar($nodo->izq) + $this->contar($nodo->der);
    }

    public function altura(): int
    {
        return $this->medirAltura($this->raiz);
    }

    private function medirAltura(?NodoArbol $nodo): int
    {
        return $nodo === null ? 0 : 1 + max($this->medirAltura($nodo->izq), $this->medirAltura($nodo->der));
    }

    /**
     * Dibuja el árbol en SVG: la columna de cada nodo es su posición
     * en el recorrido inorden y la fila es su profundidad.
     */
    public function dibujarSvg(): string
    {
        if ($this->raiz === null) {
            return '';
        }

        $posiciones = [];
        $columna = 0;
        $this->ubicar($this->raiz, 0, $columna, $posiciones);

        $separacionX = 64;
        $separacionY = 84;
        $margen = 40;
        $ancho = max(1, $columna) * $separacionX + $margen;
        $alto = $this->altura() * $separacionY;

        $aristas = '';
        $nodos = '';
        foreach ($posiciones as $info) {
            $x = $margen / 2 + $info['col'] * $separacionX + $separacionX / 2;
            $y = $info['fila'] * $separacionY + 40;

            foreach ($info['hijos'] as $hijo) {
                $hx = $margen / 2 + $posiciones[$hijo]['col'] * $separacionX + $separacionX / 2;
                $hy = $posiciones[$hijo]['fila'] * $separacionY + 40;
                $aristas .= '<line class="arista" x1="' . $x . '" y1="' . $y . '" x2="' . $hx . '" y2="' . $hy . '"/>';
            }

            $clase = 'nodo';
            if ($info['fila'] === 0) {
                $clase .= ' nodo-raiz';
            } elseif ($info['hijos'] === []) {
                $clase .= ' nodo-hoja';
            }

            $texto = htmlspecialchars($info['valor'], ENT_QUOTES, 'UTF-8');
            $nodos .= '<g class="' . $clase . '"><rect x="' . ($x - 22) . '" y="' . ($y - 22) . '" width="44" height="44"/>'
                . '<text x="' . $x . '" y="' . ($y + 1) . '">' . $texto . '</text></g>';
        }

        return '<svg width="' . $ancho . '" height="' . $alto . '" viewBox="0 0 ' . $ancho . ' ' . $alto
            . '" role="img" aria-label="Árbol binario reconstruido">' . $aristas . $nodos . '</svg>';
    }

    private function ubicar(?NodoArbol $nodo, int $fila, int &$columna, array &$posiciones): ?int
    {
        if ($nodo === null) {
            return null;
        }

        $izquierdo = $this->ubicar($nodo->izq, $fila + 1, $columna, $posiciones);
        $indice = count($posiciones);
        $posiciones[$indice] = ['valor' => $nodo->valor, 'fila' => $fila, 'col' => $columna++, 'hijos' => []];
        $derecho = $this->ubicar($nodo->der, $fila + 1, $columna, $posiciones);

        $posiciones[$indice]['hijos'] = array_values(array_filter([$izquierdo, $derecho], fn($h) => $h !== null));

        return $indice;
    }

    public function dibujar(): string
    {
        if ($this->raiz === null) {
            return '';
        }
        return $this->dibujarRecursivo($this->raiz, '', true);
    }

    private function dibujarRecursivo(?NodoArbol $nodo, string $prefijo, bool $esUltimo): string
    {
        if ($nodo === null) {
            return '';
        }

        $resultado = $prefijo;
        $resultado .= $esUltimo ? 'L-- ' : '+-- ';
        $resultado .= $nodo->valor . "\n";

        $nuevosPrefijos = [];
        if ($nodo->izq !== null) {
            $nuevosPrefijos[] = [
                'nodo'       => $nodo->izq,
                'esUltimo'   => $nodo->der === null,
                'prefijo'    => $prefijo . ($esUltimo ? '    ' : '|   '),
            ];
        }
        if ($nodo->der !== null) {
            $nuevosPrefijos[] = [
                'nodo'       => $nodo->der,
                'esUltimo'   => true,
                'prefijo'    => $prefijo . ($esUltimo ? '    ' : '|   '),
            ];
        }

        foreach ($nuevosPrefijos as $info) {
            $resultado .= $this->dibujarRecursivo($info['nodo'], $info['prefijo'], $info['esUltimo']);
        }

        return $resultado;
    }
}
