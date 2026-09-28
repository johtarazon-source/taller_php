<?php
require_once '../config/autoload.php';

$pagina = new Pagina('Conjuntos', 'conjuntos');
$entradaA = '';
$entradaB = '';
$resultado = null;
$error = null;

/** Convierte "1, 2 3;4" en [1, 2, 3, 4]. Devuelve null si hay algo que no es entero. */
function leerConjunto(string $texto): ?array
{
    $partes = preg_split('/[\s,;{}]+/', $texto, -1, PREG_SPLIT_NO_EMPTY);
    $numeros = [];
    foreach ($partes as $parte) {
        if (!preg_match('/^-?\d+$/', $parte)) {
            return null;
        }
        $numeros[] = (int) $parte;
    }
    return $numeros;
}

/** Texto de un conjunto: { 1, 2, 3 } o ∅ */
function mostrarConjunto(array $elementos): string
{
    return empty($elementos) ? '∅' : '{ ' . implode(', ', $elementos) . ' }';
}

/** Escribe los elementos de una región del diagrama de Venn en varias líneas. */
function regionVenn(array $elementos, int $x): string
{
    if (empty($elementos)) {
        return '<text class="venn-texto" x="' . $x . '" y="170" text-anchor="middle">∅</text>';
    }
    $lineas = array_chunk($elementos, 3);
    if (count($lineas) > 5) {
        $resto = count($elementos) - 12;
        $lineas = array_slice($lineas, 0, 4);
        $lineas[] = ['+' . $resto . ' más'];
    }
    $y = 170 - (count($lineas) - 1) * 12;
    $svg = '';
    foreach ($lineas as $linea) {
        $svg .= '<text class="venn-texto" x="' . $x . '" y="' . $y . '" text-anchor="middle">' . Pagina::e(implode(', ', $linea)) . '</text>';
        $y += 24;
    }
    return $svg;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entradaA = trim($_POST['conjuntoA'] ?? '');
    $entradaB = trim($_POST['conjuntoB'] ?? '');
    $A = leerConjunto($entradaA);
    $B = leerConjunto($entradaB);

    if ($A === null || $B === null) {
        $error = 'El conjunto ' . ($A === null ? 'A' : 'B') . ' solo puede contener números enteros.';
    } elseif (empty($A) && empty($B)) {
        $error = 'Ingresa al menos un elemento en alguno de los conjuntos.';
    } else {
        $conjuntos = new Conjuntos($A, $B);
        $resultado = [
            'A' => $conjuntos->obtenerA(),
            'B' => $conjuntos->obtenerB(),
            'union' => $conjuntos->union(),
            'interseccion' => $conjuntos->interseccion(),
            'diferencia_AB' => $conjuntos->diferencia_A_B(),
            'diferencia_BA' => $conjuntos->diferencia_B_A(),
        ];
    }
}

$pagina->encabezado();
$pagina->cabeceraApp(4, 'Unión, Intersección y Diferencias de Conjuntos');
?>

<?php Pagina::error($error); ?>

<section class="formulario revelar">
    <form method="post" action="">
        <div class="fila-2">
            <div class="formulario-grupo">
                <label for="conjuntoA" style="color: var(--rojo);">CONJUNTO A</label>
                <input type="text" id="conjuntoA" name="conjuntoA" placeholder="1, 2, 3, 4, 5" value="<?= Pagina::e($entradaA) ?>">
            </div>
            <div class="formulario-grupo">
                <label for="conjuntoB" style="color: var(--cian);">CONJUNTO B</label>
                <input type="text" id="conjuntoB" name="conjuntoB" placeholder="4, 5, 6, 7" value="<?= Pagina::e($entradaB) ?>">
            </div>
        </div>
        <p class="ayuda" style="margin: -6px 0 18px;">Números enteros separados por comas o espacios. Los repetidos se cuentan una sola vez.</p>
        <div class="botones">
            <button type="submit" class="boton">▶ CALCULAR OPERACIONES</button>
        </div>
    </form>
</section>

<?php if ($resultado !== null): ?>
<section class="resultado">
    <span class="resultado-banner">★ NIVEL SUPERADO ★</span>
    <h2 class="resultado-titulo">MAPA DEL NIVEL</h2>

    <div class="fila-2" style="margin-bottom: 18px;">
        <p class="recorrido"><span style="color: var(--rojo);">A</span> = <?= Pagina::e(mostrarConjunto($resultado['A'])) ?></p>
        <p class="recorrido"><span style="color: var(--cian);">B</span> = <?= Pagina::e(mostrarConjunto($resultado['B'])) ?></p>
    </div>

    <svg class="venn" viewBox="0 0 560 320" role="img" aria-label="Diagrama de Venn de A y B">
        <circle class="venn-a" cx="205" cy="165" r="135"/>
        <circle class="venn-b" cx="355" cy="165" r="135"/>
        <text class="venn-letra" x="80" y="50">A</text>
        <text class="venn-letra" x="458" y="50">B</text>
        <?= regionVenn($resultado['diferencia_AB'], 135) ?>
        <?= regionVenn($resultado['interseccion'], 280) ?>
        <?= regionVenn($resultado['diferencia_BA'], 425) ?>
    </svg>
</section>

<div class="marcadores">
    <?php
    $operaciones = [
        ['A ∪ B', 'UNIÓN', 'Elementos que están en A o en B', $resultado['union'], 'var(--amarillo)'],
        ['A ∩ B', 'INTERSECCIÓN', 'Elementos que están en A y en B', $resultado['interseccion'], 'var(--verde)'],
        ['A − B', 'DIFERENCIA', 'Elementos de A que no están en B', $resultado['diferencia_AB'], 'var(--rojo)'],
        ['B − A', 'DIFERENCIA', 'Elementos de B que no están en A', $resultado['diferencia_BA'], 'var(--cian)'],
    ];
    ?>
    <?php foreach ($operaciones as [$simbolo, $nombre, $explicacion, $elementos, $color]): ?>
        <div class="marcador" style="--m: <?= $color ?>;">
            <small><?= $nombre ?> · <?= $simbolo ?></small>
            <strong style="font-size: 30px;"><?= Pagina::e(mostrarConjunto($elementos)) ?></strong>
            <p><?= $explicacion ?> · <?= count($elementos) ?> elemento(s)</p>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php $pagina->pie(); ?>
