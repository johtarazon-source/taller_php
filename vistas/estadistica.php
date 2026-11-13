<?php
require_once __DIR__ . '/../config/autoload.php';
require_once __DIR__ . '/../clases/Pagina.php';
require_once __DIR__ . '/../clases/Estadistica.php';

const MAX_NUMEROS = 40;

$cantidad = null;
$valores = [];
$resultado = null;
$error = null;

if (isset($_REQUEST['cantidad'])) {
    $cantidad = filter_var($_REQUEST['cantidad'], FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => MAX_NUMEROS],
    ]);
    if ($cantidad === false) {
        $error = 'La cantidad debe ser un entero entre 1 y ' . MAX_NUMEROS . '.';
        $cantidad = null;
    }
}

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
            'cantidad'    => $stats->contar(),
            'suma'        => array_sum($numeros),
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark">
    <title>Estadística - Taller PHP Arcade</title>
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
    <a href="estadistica.php" class="activo">
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
            <span class="app-numero">NIVEL 03 <em>★★☆</em></span>
            <h1>Estadística</h1>
            <p><span class="mision">MISIÓN ▶</span> Promedio, Mediana y Moda de una serie de números</p>
        </div>
    </div>
</section>

<?php Pagina::error($error); ?>

<section class="formulario revelar">
    <h2 class="panel-titulo">PASO 1 · ¿CUÁNTOS NÚMEROS?</h2>
    <form method="get" action="">
        <div class="formulario-grupo">
            <label for="cantidad">CANTIDAD DE NÚMEROS (1 A 40)</label>
            <input type="number" id="cantidad" name="cantidad" min="1" max="40" required placeholder="5" value="<?= Pagina::e($cantidad ?? '') ?>">
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
        <div class="fila-3">
            <?php for ($i = 0; $i < $cantidad; $i++): ?>
                <div class="formulario-grupo">
                    <label for="n<?= $i ?>">N.º <?= $i + 1 ?></label>
                    <input type="text" inputmode="decimal" id="n<?= $i ?>" name="numeros[]" required placeholder="0.0" value="<?= Pagina::e($valores[$i] ?? '') ?>">
                </div>
            <?php endfor; ?>
        </div>
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
        <div class="marcador"><small>PROMEDIO</small><strong><?= Pagina::e(Estadistica::formatear($resultado['promedio'], 4)) ?></strong></div>
        <div class="marcador"><small>MEDIANA</small><strong><?= Pagina::e(Estadistica::formatear($resultado['mediana'], 4)) ?></strong></div>
        <div class="marcador"><small>MODA</small><strong><?= empty($resultado['moda']) ? '—' : Pagina::e(implode(', ', $resultado['moda'])) ?></strong></div>
    </div>
    <p class="resultado-etiqueta">DATOS ORDENADOS</p>
    <p class="nota"><?= Pagina::e(implode(', ', array_map(fn($n) => Estadistica::formatear((float) $n), $resultado['ordenados']))) ?></p>
</section>
<?php endif; ?>

</main>
<footer class="pie">
    <span class="pie-parpadeo">INSERT COIN ▮</span>
</footer>
</body>
</html>
