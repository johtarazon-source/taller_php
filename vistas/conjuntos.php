<?php
require_once __DIR__ . '/../config/autoload.php';
require_once __DIR__ . '/../clases/Pagina.php';
require_once __DIR__ . '/../clases/Conjuntos.php';

$resultado = null;
$error = null;
$entradaA = '';
$entradaB = '';

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

function mostrarConjunto(array $elementos): string
{
    return empty($elementos) ? '∅' : '{ ' . implode(', ', $elementos) . ' }';
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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark">
    <title>Conjuntos - Taller PHP Arcade</title>
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
    <a href="conjuntos.php" class="activo">
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
            <span class="app-numero">NIVEL 04 <em>★★☆</em></span>
            <h1>Conjuntos</h1>
            <p><span class="mision">MISIÓN ▶</span> Unión, Intersección y Diferencias de Conjuntos</p>
        </div>
    </div>
</section>

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
        <div class="botones">
            <button type="submit" class="boton">▶ CALCULAR OPERACIONES</button>
        </div>
    </form>
</section>

<?php if ($resultado !== null): ?>
<section class="resultado">
    <span class="resultado-banner">★ NIVEL SUPERADO ★</span>
    <h2 class="resultado-titulo">RESULTADOS</h2>
    <p class="recorrido">A = <?= Pagina::e(mostrarConjunto($resultado['A'])) ?></p>
    <p class="recorrido">B = <?= Pagina::e(mostrarConjunto($resultado['B'])) ?></p>
    <div class="marcadores">
        <div class="marcador"><small>UNIÓN · A ∪ B</small><strong><?= Pagina::e(mostrarConjunto($resultado['union'])) ?></strong></div>
        <div class="marcador"><small>INTERSECCIÓN · A ∩ B</small><strong><?= Pagina::e(mostrarConjunto($resultado['interseccion'])) ?></strong></div>
        <div class="marcador"><small>DIFERENCIA · A − B</small><strong><?= Pagina::e(mostrarConjunto($resultado['diferencia_AB'])) ?></strong></div>
        <div class="marcador"><small>DIFERENCIA · B − A</small><strong><?= Pagina::e(mostrarConjunto($resultado['diferencia_BA'])) ?></strong></div>
    </div>
</section>
<?php endif; ?>

</main>
<footer class="pie">
    <span class="pie-parpadeo">INSERT COIN ▮</span>
</footer>
</body>
</html>
