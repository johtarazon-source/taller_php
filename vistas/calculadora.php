<?php
require_once __DIR__ . '/../config/autoload.php';
require_once __DIR__ . '/../clases/Pagina.php';
require_once __DIR__ . '/../clases/EstadoCalculadora.php';
require_once __DIR__ . '/../clases/Calculadora.php';

$estado = new EstadoCalculadora();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['accion'] ?? '') === 'limpiar_historial') {
        Calculadora::limpiarHistorial();
    } elseif (isset($_POST['tecla'])) {
        $estado->pulsar((string) $_POST['tecla']);
    }

    header('Location: calculadora.php');
    exit;
}

$historial = Calculadora::obtenerHistorial();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark">
    <title>Calculadora - Taller PHP Arcade</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&family=Chakra+Petch:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/estilos.css">
</head>
<body>
<div class="crt" aria-hidden="true"></div>

<input type="checkbox" id="menu-abierto" class="menu-check">

<header class="hud">
    <a class="marca" href="../index.php">
        <span class="marca-logo">&lt;?</span>
        <span class="marca-texto">TALLER<span>PHP</span></span>
    </a>

    <div class="hud-stats" aria-hidden="true">
        <span class="hud-dato"><small>1UP</small><b>P1</b></span>
        <span class="hud-dato"><small>NIVELES</small><b>07</b></span>
        <span class="hud-dato hud-vidas"><small>VIDAS</small><b>♥♥♥</b></span>
    </div>

    <label class="menu-toggle" for="menu-abierto" aria-label="Abrir menú">
        <span></span><span></span><span></span>
    </label>
</header>

<nav class="nav" aria-label="Aplicaciones">
    <a href="../index.php">
        <span class="nav-num">⌂</span>Inicio
    </a>
    <a href="acronimo.php">
        <span class="nav-num">1</span>Acrónimos
    </a>
    <a href="series.php">
        <span class="nav-num">2</span>Fibonacci y Factorial
    </a>
    <a href="estadistica.php">
        <span class="nav-num">3</span>Estadística
    </a>
    <a href="conjuntos.php">
        <span class="nav-num">4</span>Conjuntos
    </a>
    <a href="binario.php">
        <span class="nav-num">5</span>Decimal a Binario
    </a>
    <a href="arbol.php">
        <span class="nav-num">6</span>Árbol Binario
    </a>
    <a href="calculadora.php" class="activo">
        <span class="nav-num">7</span>Calculadora
    </a>
</nav>

<main class="contenido">

<section class="app-cabecera revelar">
    <a class="volver" href="../index.php">◀ SELECCIÓN DE NIVEL</a>
    <div class="app-cabecera-fila">
        <div>
            <span class="app-numero">NIVEL 07 <em>★★☆</em></span>
            <h1>Calculadora</h1>
            <p><span class="mision">MISIÓN ▶</span> Operaciones básicas con historial persistente</p>
        </div>
    </div>
</section>

<div class="consola revelar">

    <section class="maquina">
        <div class="maquina-marca"><span>CALC-8BIT</span><span>● ON</span></div>

        <div class="pantalla" aria-live="polite">
            <div class="pantalla-op"><?= Pagina::e($estado->pantallaOperacion()) ?></div>
            <div class="pantalla-res<?= $estado->hayError() ? ' es-error' : '' ?>"><?= Pagina::e($estado->pantallaResultado()) ?></div>
        </div>

        <form class="teclado" method="post" action="calculadora.php">
            <button type="submit" name="tecla" value="limpiar" class="tecla tecla-borrar" aria-label="Limpiar">C</button>
            <button type="submit" name="tecla" value="retroceso" class="tecla" aria-label="Borrar último dígito">⌫</button>
            <button type="submit" name="tecla" value="porcentaje" class="tecla tecla-op" aria-label="Porcentaje">%</button>
            <button type="submit" name="tecla" value="division" class="tecla tecla-op" aria-label="Dividir">÷</button>
            <button type="submit" name="tecla" value="7" class="tecla">7</button>
            <button type="submit" name="tecla" value="8" class="tecla">8</button>
            <button type="submit" name="tecla" value="9" class="tecla">9</button>
            <button type="submit" name="tecla" value="multiplicacion" class="tecla tecla-op" aria-label="Multiplicar">×</button>
            <button type="submit" name="tecla" value="4" class="tecla">4</button>
            <button type="submit" name="tecla" value="5" class="tecla">5</button>
            <button type="submit" name="tecla" value="6" class="tecla">6</button>
            <button type="submit" name="tecla" value="resta" class="tecla tecla-op" aria-label="Restar">−</button>
            <button type="submit" name="tecla" value="1" class="tecla">1</button>
            <button type="submit" name="tecla" value="2" class="tecla">2</button>
            <button type="submit" name="tecla" value="3" class="tecla">3</button>
            <button type="submit" name="tecla" value="suma" class="tecla tecla-op" aria-label="Sumar">+</button>
            <button type="submit" name="tecla" value="signo" class="tecla" aria-label="Cambiar signo">±</button>
            <button type="submit" name="tecla" value="0" class="tecla">0</button>
            <button type="submit" name="tecla" value="." class="tecla" aria-label="Punto decimal">.</button>
            <button type="submit" name="tecla" value="igual" class="tecla tecla-igual" aria-label="Calcular">=</button>
        </form>

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

</main>
<footer class="pie">
    <span class="pie-parpadeo">INSERT COIN ▮</span>
</footer>
</body>
</html>
