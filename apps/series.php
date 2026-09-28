<?php
require_once '../config/autoload.php';

$pagina = new Pagina('Fibonacci y Factorial', 'series');
$series = new Series();
$numero = '';
$tipo = 'fibonacci';
$resultado = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = trim($_POST['numero'] ?? '');
    $tipo = ($_POST['tipo'] ?? '') === 'factorial' ? 'factorial' : 'fibonacci';
    $n = filter_var($numero, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 50]]);

    if ($n === false) {
        $error = 'Ingresa un número entero entre 1 y 50.';
    } else {
        $resultado = $tipo === 'fibonacci' ? $series->fibonacci($n) : $series->factorial($n);
    }
}

$pagina->encabezado();
$pagina->cabeceraApp(2, 'Sucesión de Fibonacci o Factorial de un número');
?>

<?php Pagina::error($error); ?>

<section class="formulario revelar">
    <form method="post" action="">
        <div class="formulario-grupo">
            <span class="etiqueta">ELIGE LA OPERACIÓN</span>
            <div class="selector">
                <input type="radio" id="tipo-fib" name="tipo" value="fibonacci" <?= $tipo === 'fibonacci' ? 'checked' : '' ?>>
                <label for="tipo-fib"><strong>FIBONACCI</strong>0, 1, 1, 2, 3, 5, 8… cada término suma los dos anteriores.</label>

                <input type="radio" id="tipo-fac" name="tipo" value="factorial" <?= $tipo === 'factorial' ? 'checked' : '' ?>>
                <label for="tipo-fac"><strong>FACTORIAL</strong>1!, 2!, 3!… n! = n × (n−1) × … × 1</label>
            </div>
        </div>
        <div class="formulario-grupo">
            <label for="numero">NÚMERO (1 A 50)</label>
            <input type="number" id="numero" name="numero" min="1" max="50" required placeholder="10" value="<?= Pagina::e($numero) ?>">
        </div>
        <div class="botones">
            <button type="submit" class="boton">▶ CALCULAR SERIE</button>
        </div>
    </form>
</section>

<?php if ($resultado !== null && !empty($resultado)): ?>
<section class="resultado">
    <span class="resultado-banner">★ NIVEL SUPERADO ★</span>
    <h2 class="resultado-titulo"><?= $tipo === 'fibonacci' ? 'SUCESIÓN DE FIBONACCI' : 'SERIE FACTORIAL' ?></h2>

    <p class="resultado-etiqueta"><?= $tipo === 'fibonacci' ? 'ÚLTIMO TÉRMINO' : Pagina::e($numero) . '! =' ?></p>
    <div class="resultado-valor"><?= Pagina::e((string) end($resultado)) ?></div>

    <?php if ($tipo === 'factorial'): ?>
        <p class="nota"><?= Pagina::e($numero) ?>! = <?= Pagina::e(implode(' × ', range((int) $numero, 1))) ?></p>
    <?php endif; ?>

    <p class="resultado-etiqueta">SERIE GENERADA · <?= count($resultado) ?> TÉRMINOS</p>
    <div class="fichas">
        <?php $ultimo = array_key_last($resultado); ?>
        <?php foreach ($resultado as $i => $valor): ?>
            <div class="ficha<?= $i === $ultimo ? ' ficha-final' : '' ?>" style="animation-delay: <?= min($i, 40) * 0.02 ?>s">
                <small><?= $tipo === 'fibonacci' ? 'F' . ($i + 1) : ($i + 1) . '!' ?></small>
                <span><?= Pagina::e((string) $valor) ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php $pagina->pie(); ?>
