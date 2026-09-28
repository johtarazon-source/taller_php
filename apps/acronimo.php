<?php
require_once '../config/autoload.php';

$pagina = new Pagina('Acrónimos', 'acronimo');
$acronimo = new Acronimo();
$entrada = '';
$resultado = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entrada = trim($_POST['entrada'] ?? '');
    $resultado = $acronimo->generar($entrada);
    if ($resultado === '') {
        $error = 'Escribe una frase con al menos una letra o número.';
        $resultado = null;
    }
}

$ejemplos = ['As Soon As Possible', 'Liquid-crystal display', "Thank George It's Friday!", 'Portable Network Graphics'];

$pagina->encabezado();
$pagina->cabeceraApp(1, 'Convierte frases largas en sus acrónimos');
?>

<?php Pagina::error($error); ?>

<section class="formulario revelar">
    <form method="post" action="">
        <div class="formulario-grupo">
            <label for="entrada">FRASE O NOMBRE LARGO</label>
            <input type="text" id="entrada" name="entrada" maxlength="300" required
                   placeholder="Portable Network Graphics" value="<?= Pagina::e($entrada) ?>">
            <p class="ayuda">Los guiones separan palabras; los demás signos de puntuación se ignoran.</p>
        </div>
        <div class="botones">
            <button type="submit" class="boton">▶ GENERAR ACRÓNIMO</button>
        </div>
    </form>

    <form class="ejemplos" method="post" action="">
        <span>PROBAR:</span>
        <?php foreach ($ejemplos as $ejemplo): ?>
            <button class="pastilla" type="submit" name="entrada" value="<?= Pagina::e($ejemplo) ?>"><?= Pagina::e($ejemplo) ?></button>
        <?php endforeach; ?>
    </form>
</section>

<?php if ($resultado !== null): ?>
<section class="resultado">
    <span class="resultado-banner">★ NIVEL SUPERADO ★</span>
    <h2 class="resultado-titulo">ACRÓNIMO</h2>

    <div class="letras" aria-label="<?= Pagina::e($resultado) ?>">
        <?php foreach (mb_str_split($resultado) as $i => $letra): ?>
            <span class="letra" style="animation-delay: <?= $i * 0.08 ?>s"><?= Pagina::e($letra) ?></span>
        <?php endforeach; ?>
    </div>

    <p class="resultado-etiqueta">PALABRAS DETECTADAS: <?= count($acronimo->palabras($entrada)) ?></p>
    <div class="palabras">
        <?php foreach ($acronimo->palabras($entrada) as $palabra): ?>
            <span class="palabra"><b><?= Pagina::e(mb_strtoupper(mb_substr($palabra, 0, 1))) ?></b><?= Pagina::e(mb_substr($palabra, 1)) ?></span>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php $pagina->pie(); ?>
