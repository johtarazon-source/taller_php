<?php
require_once '../config/autoload.php';

const MAX_NUMEROS = 40;

$pagina = new Pagina('Estadística', 'estadistica');
$cantidad = null;
$valores = [];
$resultado = null;
$error = null;

// Paso 1: el usuario decide cuántos números va a ingresar.
if (isset($_REQUEST['cantidad'])) {
    $cantidad = filter_var($_REQUEST['cantidad'], FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => MAX_NUMEROS],
    ]);
    if ($cantidad === false) {
        $error = 'La cantidad debe ser un entero entre 1 y ' . MAX_NUMEROS . '.';
        $cantidad = null;
    }
}

// Paso 2: se reciben los números y se calculan las medidas.
if ($cantidad !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $valores = array_map('strval', (array) ($_POST['numeros'] ?? []));
    $numeros = [];

    for ($i = 0; $i < $cantidad; $i++) {
        $texto = str_replace(',', '.', trim($valores[$i] ?? ''));
        if (!is_numeric($texto)) {
            $error = 'El número #' . ($i + 1) . ' no es un número real válido.';
            break;
        }
        $numeros[] = (float) $texto;
    }

    if ($error === null) {
        $stats = new Estadistica($numeros);
        $ordenados = $stats->obtenerNumeros();
        sort($ordenados);
        $resultado = [
            'ordenados'   => $ordenados,
            'promedio'    => $stats->promedio(),
            'mediana'     => $stats->mediana(),
            'moda'        => $stats->moda(),
            'frecuencias' => $stats->frecuencias(),
            'cantidad'    => $stats->contar(),
            'suma'        => array_sum($numeros),
        ];
    }
}

$pagina->encabezado();
$pagina->cabeceraApp(3, 'Promedio, Mediana y Moda de una serie de números');
?>

<?php Pagina::error($error); ?>

<section class="formulario revelar">
    <h2 class="panel-titulo">PASO 1 · ¿CUÁNTOS NÚMEROS?</h2>
    <form method="get" action="">
        <div class="formulario-grupo">
            <label for="cantidad">CANTIDAD DE NÚMEROS (1 A <?= MAX_NUMEROS ?>)</label>
            <input type="number" id="cantidad" name="cantidad" min="1" max="<?= MAX_NUMEROS ?>" required
                   placeholder="5" value="<?= Pagina::e($cantidad ?? '') ?>">
        </div>
        <div class="botones">
            <button type="submit" class="boton boton-secundario">▶ PREPARAR CASILLAS</button>
        </div>
    </form>
</section>

<?php if ($cantidad !== null): ?>
<section class="formulario revelar">
    <h2 class="panel-titulo">PASO 2 · INGRESA LOS <?= $cantidad ?> NÚMEROS</h2>
    <form method="post" action="?cantidad=<?= $cantidad ?>">
        <div class="fila-3" style="grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));">
            <?php for ($i = 0; $i < $cantidad; $i++): ?>
                <div class="formulario-grupo">
                    <label for="n<?= $i ?>">N.º <?= $i + 1 ?></label>
                    <input type="text" inputmode="decimal" id="n<?= $i ?>" name="numeros[]" required
                           placeholder="0.0" value="<?= Pagina::e($valores[$i] ?? '') ?>">
                </div>
            <?php endfor; ?>
        </div>
        <p class="ayuda" style="margin: -6px 0 18px;">Se aceptan enteros, negativos y decimales con punto o coma.</p>
        <div class="botones">
            <button type="submit" class="boton">▶ CALCULAR</button>
        </div>
    </form>
</section>
<?php endif; ?>

<?php if ($resultado !== null): ?>
<section class="resultado">
    <span class="resultado-banner">★ NIVEL SUPERADO ★</span>
    <h2 class="resultado-titulo">MARCADOR FINAL</h2>

    <div class="marcadores">
        <div class="marcador">
            <small>PROMEDIO</small>
            <strong><?= Pagina::e(Estadistica::formatear($resultado['promedio'], 4)) ?></strong>
            <p>Suma (<?= Pagina::e(Estadistica::formatear($resultado['suma'])) ?>) ÷ <?= $resultado['cantidad'] ?> datos</p>
        </div>
        <div class="marcador" style="--m: var(--verde);">
            <small>MEDIANA</small>
            <strong><?= Pagina::e(Estadistica::formatear($resultado['mediana'], 4)) ?></strong>
            <p>Valor central de los datos ordenados</p>
        </div>
        <div class="marcador" style="--m: var(--amarillo);">
            <small>MODA</small>
            <strong><?= empty($resultado['moda']) ? '—' : Pagina::e(implode(', ', $resultado['moda'])) ?></strong>
            <p><?= empty($resultado['moda']) ? 'No hay moda: ningún valor se repite más que otro' : 'Valor(es) que más se repiten' ?></p>
        </div>
    </div>

    <p class="resultado-etiqueta">DATOS ORDENADOS</p>
    <div class="fichas">
        <?php foreach ($resultado['ordenados'] as $num): ?>
            <?php $valor = Estadistica::formatear((float) $num); ?>
            <div class="ficha<?= in_array($valor, $resultado['moda'], true) ? ' ficha-final' : '' ?>">
                <span><?= Pagina::e($valor) ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <p class="resultado-etiqueta">FRECUENCIAS</p>
    <?php $maxFrecuencia = max($resultado['frecuencias']); ?>
    <div class="tabla-responsiva">
        <table>
            <thead>
                <tr><th>VALOR</th><th>VECES</th><th style="width: 50%;">BARRA</th></tr>
            </thead>
            <tbody>
                <?php foreach ($resultado['frecuencias'] as $valor => $veces): ?>
                    <?php $esModa = in_array((string) $valor, $resultado['moda'], true); ?>
                    <tr>
                        <td class="<?= $esModa ? 'td-resaltar' : '' ?>"><?= Pagina::e((string) $valor) ?></td>
                        <td><?= $veces ?></td>
                        <td>
                            <div class="barra-vida" style="--m: <?= $esModa ? 'var(--amarillo)' : 'var(--accent)' ?>;">
                                <span style="width: <?= round($veces / $maxFrecuencia * 100) ?>%;"></span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php endif; ?>

<?php $pagina->pie(); ?>
