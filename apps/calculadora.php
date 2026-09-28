<?php
require_once '../config/autoload.php';

$pagina = new Pagina('Calculadora', 'calculadora');
$estado = new EstadoCalculadora();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['accion'] ?? '') === 'limpiar_historial') {
        Calculadora::limpiarHistorial();
    } elseif (isset($_POST['tecla'])) {
        $estado->pulsar((string) $_POST['tecla']);
    }

    // Patrón POST/Redirect/GET: al recargar la página no se repite la última tecla.
    header('Location: calculadora.php');
    exit;
}

$historial = Calculadora::obtenerHistorial();

// Distribución del teclado: [tecla, texto, clase extra, descripción]
$teclado = [
    ['limpiar', 'C', 'tecla-borrar', 'Limpiar'],
    ['retroceso', '⌫', '', 'Borrar último dígito'],
    ['porcentaje', '%', 'tecla-op', 'Porcentaje'],
    ['division', '÷', 'tecla-op', 'Dividir'],
    ['7', '7', '', ''], ['8', '8', '', ''], ['9', '9', '', ''],
    ['multiplicacion', '×', 'tecla-op', 'Multiplicar'],
    ['4', '4', '', ''], ['5', '5', '', ''], ['6', '6', '', ''],
    ['resta', '−', 'tecla-op', 'Restar'],
    ['1', '1', '', ''], ['2', '2', '', ''], ['3', '3', '', ''],
    ['suma', '+', 'tecla-op', 'Sumar'],
    ['signo', '±', '', 'Cambiar signo'],
    ['0', '0', '', ''],
    ['.', '.', '', 'Punto decimal'],
    ['igual', '=', 'tecla-igual', 'Calcular'],
];

$pagina->encabezado();
$pagina->cabeceraApp(7, 'Operaciones básicas con historial persistente');
?>

<div class="consola revelar">

    <section class="maquina">
        <div class="maquina-marca"><span>CALC-8BIT</span><span>● ON</span></div>

        <div class="pantalla" aria-live="polite">
            <div class="pantalla-op"><?= Pagina::e($estado->pantallaOperacion()) ?></div>
            <div class="pantalla-res<?= $estado->hayError() ? ' es-error' : '' ?>"><?= Pagina::e($estado->pantallaResultado()) ?></div>
        </div>

        <form class="teclado" method="post" action="calculadora.php">
            <?php foreach ($teclado as [$tecla, $texto, $clase, $descripcion]): ?>
                <button type="submit" name="tecla" value="<?= Pagina::e($tecla) ?>"
                        class="tecla <?= $clase ?>"<?= $descripcion !== '' ? ' aria-label="' . Pagina::e($descripcion) . '"' : '' ?>><?= $texto ?></button>
            <?php endforeach; ?>
        </form>

        <p class="nota"><strong>%</strong> calcula «A por ciento de B»: 20 % 150 = 30.</p>
    </section>

    <section class="panel">
        <div class="cabecera-panel">
            <h2 class="panel-titulo">HISTORIAL (<?= count($historial) ?>)</h2>
            <?php if (!empty($historial)): ?>
                <form method="post" action="calculadora.php">
                    <input type="hidden" name="accion" value="limpiar_historial">
                    <button type="submit" class="boton boton-peligro boton-chico">✖ BORRAR HISTORIAL</button>
                </form>
            <?php endif; ?>
        </div>

        <?php if (empty($historial)): ?>
            <div class="historial-vacio">
                <b>SIN PARTIDAS</b>
                Las operaciones que realices aparecerán aquí.
            </div>
        <?php else: ?>
            <ul class="historial">
                <?php foreach (array_reverse($historial) as $item): ?>
                    <li class="historial-item">
                        <div>
                            <div class="historial-operacion"><?= Pagina::e($item['operacion']) ?> =</div>
                            <div class="historial-fecha"><?= Pagina::e($item['fecha']) ?></div>
                        </div>
                        <div class="historial-resultado"><?= Pagina::e(Calculadora::formatear((float) $item['resultado'])) ?></div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

</div>

<?php $pagina->pie(); ?>
