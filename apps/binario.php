<?php
require_once '../config/autoload.php';

$pagina = new Pagina('Decimal a Binario', 'binario');
$binario = new Binario();
$numero = '';
$resultado = null;
$pasos = [];
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = trim($_POST['numero'] ?? '');
    $entero = filter_var($numero, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => -PHP_INT_MAX, 'max_range' => PHP_INT_MAX],
    ]);

    if ($entero === false) {
        $error = 'Ingresa un número entero (sin decimales).';
    } else {
        $resultado = $binario->convertir($entero);
        $pasos = $binario->obtenerPasos($entero);
    }
}

$pagina->encabezado();
$pagina->cabeceraApp(5, 'Convierte un número decimal a su representación binaria');
?>

<?php Pagina::error($error); ?>

<section class="formulario revelar">
    <form method="post" action="">
        <div class="formulario-grupo">
            <label for="numero">NÚMERO ENTERO EN BASE 10</label>
            <input type="number" id="numero" name="numero" step="1" required placeholder="156" value="<?= Pagina::e($numero) ?>">
            <p class="ayuda">Acepta negativos: el signo se conserva delante del resultado.</p>
        </div>
        <div class="botones">
            <button type="submit" class="boton">▶ CONVERTIR</button>
        </div>
    </form>
</section>

<?php if ($resultado !== null): ?>
<section class="resultado">
    <span class="resultado-banner">★ NIVEL SUPERADO ★</span>
    <h2 class="resultado-titulo"><?= Pagina::e($numero) ?> EN BINARIO</h2>

    <div class="resultado-valor"><?= Pagina::e($resultado) ?></div>

    <p class="resultado-etiqueta">BITS AGRUPADOS DE 4 EN 4</p>
    <div class="bits" aria-hidden="true">
        <?php if (str_starts_with($resultado, '-')): ?>
            <div class="bits-grupo"><span class="bit">−</span></div>
        <?php endif; ?>
        <?php foreach ($binario->grupos($resultado) as $grupo): ?>
            <div class="bits-grupo">
                <?php foreach (str_split($grupo) as $bit): ?>
                    <span class="bit<?= $bit === '1' ? ' bit-1' : '' ?>"><?= $bit ?></span>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php if (!empty($pasos)): ?>
<section class="panel">
    <h2 class="panel-titulo">DIVISIONES SUCESIVAS ENTRE 2</h2>
    <div class="tabla-responsiva">
        <table>
            <thead>
                <tr><th>PASO</th><th>NÚMERO</th><th>÷ 2 = COCIENTE</th><th>RESIDUO</th></tr>
            </thead>
            <tbody>
                <?php foreach ($pasos as $paso): ?>
                    <tr>
                        <td><?= $paso['paso'] ?></td>
                        <td><?= $paso['numero'] ?></td>
                        <td><?= $paso['cociente'] ?></td>
                        <td class="td-resaltar"><?= $paso['residuo'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <p class="nota"><strong>TIP:</strong> se divide entre 2 hasta que el cociente sea 0; los residuos leídos de abajo hacia arriba forman el número binario.</p>
</section>
<?php endif; ?>
<?php endif; ?>

<?php $pagina->pie(); ?>
