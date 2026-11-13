<?php
require_once __DIR__ . '/../config/autoload.php';
require_once __DIR__ . '/../clases/Pagina.php';
require_once __DIR__ . '/../clases/Series.php';

$series = new Series();
$resultado = null;
$error = null;
$tipo = 'fibonacci';
$numero = '';

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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark">
    <title>Fibonacci y Factorial - Taller PHP Arcade</title>
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
    <a href="series.php" class="activo">
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
    <a href="calculadora.php">
        <span class="nav-num">7</span>Calculadora
    </a>
</nav>

<main class="contenido">

<section class="app-cabecera revelar">
    <a class="volver" href="../index.php">◀ SELECCIÓN DE NIVEL</a>
    <div class="app-cabecera-fila">
        <div>
            <span class="app-numero">NIVEL 02 <em>★★☆</em></span>
            <h1>Fibonacci y Factorial</h1>
            <p><span class="mision">MISIÓN ▶</span> Sucesión de Fibonacci o Factorial de un número</p>
        </div>
    </div>
</section>

<?php Pagina::error($error); ?>

<section class="formulario revelar">
    <form method="post" action="">
        <div class="formulario-grupo">
            <span class="etiqueta">ELIGE LA OPERACIÓN</span>
            <div class="selector">
                <input type="radio" id="tipo-fib" name="tipo" value="fibonacci" <?= $tipo === 'fibonacci' ? 'checked' : '' ?>>
                <label for="tipo-fib"><strong>FIBONACCI</strong></label>

                <input type="radio" id="tipo-fac" name="tipo" value="factorial" <?= $tipo === 'factorial' ? 'checked' : '' ?>>
                <label for="tipo-fac"><strong>FACTORIAL</strong></label>
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
    <p class="resultado-etiqueta">RESULTADO</p>
    <div class="resultado-valor"><?= Pagina::e((string) end($resultado)) ?></div>
    <p class="resultado-etiqueta">SERIE GENERADA · <?= count($resultado) ?> TÉRMINOS</p>
    <p class="nota"><?= Pagina::e(implode(', ', $resultado)) ?></p>
</section>
<?php endif; ?>

</main>
<footer class="pie">
    <span class="pie-parpadeo">INSERT COIN ▮</span>
</footer>
</body>
</html>
