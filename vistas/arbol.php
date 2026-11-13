<?php
require_once __DIR__ . '/../config/autoload.php';
require_once __DIR__ . '/../clases/Pagina.php';
require_once __DIR__ . '/../clases/ArbolBinario.php';

$nombres = ['preorden' => 'PREORDEN', 'inorden' => 'INORDEN', 'postorden' => 'POSTORDEN'];
$entradas = ['preorden' => '', 'inorden' => '', 'postorden' => ''];
$resultado = null;
$error = null;

function leerRecorrido(string $texto): array
{
    $texto = trim($texto);
    if ($texto === '') {
        return [];
    }
    $partes = preg_split('/[\s,;|>\-→]+/u', $texto, -1, PREG_SPLIT_NO_EMPTY);
    if (count($partes) === 1 && mb_strlen($partes[0]) > 1) {
        $partes = mb_str_split($partes[0]);
    }
    return array_map(fn($p) => mb_strtoupper($p), $partes);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recorridos = [];
    foreach ($entradas as $clave => $_) {
        $entradas[$clave] = trim($_POST[$clave] ?? '');
        $lista = leerRecorrido($entradas[$clave]);
        if (!empty($lista)) {
            $recorridos[$clave] = $lista;
        }
    }

    if (isset($recorridos['preorden'], $recorridos['inorden'])) {
        $par = ['preorden', 'inorden'];
    } elseif (isset($recorridos['inorden'], $recorridos['postorden'])) {
        $par = ['inorden', 'postorden'];
    } elseif (isset($recorridos['preorden'], $recorridos['postorden'])) {
        $par = ['preorden', 'postorden'];
    } else {
        $par = null;
        $error = 'Ingresa al menos dos de los tres recorridos.';
    }

    if ($par !== null) {
        $error = ArbolBinario::validar($recorridos[$par[0]], $recorridos[$par[1]]);
    }

    if ($error === null) {
        $arbol = new ArbolBinario();
        match ($par) {
            ['preorden', 'inorden']   => $arbol->construirPreIn($recorridos['preorden'], $recorridos['inorden']),
            ['inorden', 'postorden']  => $arbol->construirInPost($recorridos['inorden'], $recorridos['postorden']),
            ['preorden', 'postorden'] => $arbol->construirPrePost($recorridos['preorden'], $recorridos['postorden']),
        };

        $calculados = [
            'preorden'  => $arbol->preorden(),
            'inorden'   => $arbol->inorden(),
            'postorden' => $arbol->postorden(),
        ];

        foreach ($par as $clave) {
            if ($calculados[$clave] !== implode(' → ', $recorridos[$clave])) {
                $error = 'Los recorridos de ' . $nombres[$par[0]] . ' y ' . $nombres[$par[1]] . ' no corresponden a un mismo árbol binario.';
                break;
            }
        }

        if ($error === null) {
            $resultado = [
                'calculados' => $calculados,
                'svg'        => $arbol->dibujarSvg(),
                'nodos'      => $arbol->contarNodos(),
                'altura'     => $arbol->altura(),
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark">
    <title>Árbol Binario - Taller PHP Arcade</title>
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
    <a href="arbol.php" class="activo">
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
            <span class="app-numero">NIVEL 06 <em>★★★</em></span>
            <h1>Árbol Binario</h1>
            <p><span class="mision">MISIÓN ▶</span> Reconstruye un árbol binario a partir de sus recorridos</p>
        </div>
    </div>
</section>

<?php Pagina::error($error); ?>

<section class="formulario revelar">
    <form method="post" action="">
        <div class="fila-3">
            <div class="formulario-grupo">
                <label for="preorden">PREORDEN</label>
                <input type="text" id="preorden" name="preorden" placeholder="A B D E C" value="<?= Pagina::e($entradas['preorden']) ?>">
            </div>
            <div class="formulario-grupo">
                <label for="inorden">INORDEN</label>
                <input type="text" id="inorden" name="inorden" placeholder="A B D E C" value="<?= Pagina::e($entradas['inorden']) ?>">
            </div>
            <div class="formulario-grupo">
                <label for="postorden">POSTORDEN</label>
                <input type="text" id="postorden" name="postorden" placeholder="A B D E C" value="<?= Pagina::e($entradas['postorden']) ?>">
            </div>
        </div>
        <div class="botones">
            <button type="submit" class="boton">▶ CONSTRUIR ÁRBOL</button>
        </div>
    </form>
</section>

<?php if ($resultado !== null): ?>
<section class="resultado">
    <span class="resultado-banner">★ NIVEL SUPERADO ★</span>
    <h2 class="resultado-titulo">ÁRBOL CONSTRUIDO</h2>
    <div class="lienzo-arbol"><?= $resultado['svg'] ?></div>
    <div class="leyenda">
        <span>Nodos: <b><?= $resultado['nodos'] ?></b></span>
        <span>Altura: <b><?= $resultado['altura'] ?></b></span>
    </div>
    <div class="marcadores">
        <div class="marcador"><small>PREORDEN</small><p class="recorrido"><?= Pagina::e($resultado['calculados']['preorden']) ?></p></div>
        <div class="marcador"><small>INORDEN</small><p class="recorrido"><?= Pagina::e($resultado['calculados']['inorden']) ?></p></div>
        <div class="marcador"><small>POSTORDEN</small><p class="recorrido"><?= Pagina::e($resultado['calculados']['postorden']) ?></p></div>
    </div>
</section>
<?php endif; ?>

</main>
<footer class="pie">
    <span class="pie-parpadeo">INSERT COIN ▮</span>
</footer>
</body>
</html>
