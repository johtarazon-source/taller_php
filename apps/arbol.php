<?php
require_once '../config/autoload.php';

$pagina = new Pagina('Árbol Binario', 'arbol');
$nombres = ['preorden' => 'PREORDEN', 'inorden' => 'INORDEN', 'postorden' => 'POSTORDEN'];
$entradas = ['preorden' => '', 'inorden' => '', 'postorden' => ''];
$resultado = null;
$error = null;

/** Convierte "A → B → D", "A, B, D" o "ABD" en ['A', 'B', 'D']. */
function leerRecorrido(string $texto): array
{
    $texto = trim($texto);
    if ($texto === '') {
        return [];
    }
    $partes = preg_split('/[\s,;|>\-→]+/u', $texto, -1, PREG_SPLIT_NO_EMPTY);
    // Sin separadores ("ABDEC"): cada carácter es un nodo.
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

    // Si llegan los tres recorridos, por defecto se usa preorden + inorden.
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

        // Los recorridos usados deben coincidir con los del árbol construido.
        foreach ($par as $clave) {
            if ($calculados[$clave] !== implode(' → ', $recorridos[$clave])) {
                $error = 'Los recorridos de ' . $nombres[$par[0]] . ' y ' . $nombres[$par[1]] . ' no corresponden a un mismo árbol binario.';
                break;
            }
        }

        if ($error === null) {
            $resultado = [
                'par'        => $par,
                'calculados' => $calculados,
                'ingresados' => $recorridos,
                'svg'        => $arbol->dibujarSvg(),
                'nodos'      => $arbol->contarNodos(),
                'altura'     => $arbol->altura(),
            ];
        }
    }
}

$pagina->encabezado();
$pagina->cabeceraApp(6, 'Reconstruye un árbol binario a partir de sus recorridos');
?>

<?php Pagina::error($error); ?>

<section class="formulario revelar">
    <form method="post" action="">
        <div class="fila-3">
            <?php foreach ($nombres as $clave => $nombre): ?>
                <div class="formulario-grupo">
                    <label for="<?= $clave ?>"><?= $nombre ?></label>
                    <input type="text" id="<?= $clave ?>" name="<?= $clave ?>" placeholder="A B D E C" value="<?= Pagina::e($entradas[$clave]) ?>">
                </div>
            <?php endforeach; ?>
        </div>
        <p class="ayuda" style="margin: -6px 0 18px;">
            Llena <strong>mínimo dos</strong> recorridos. Separa los nodos con espacios, comas o flechas (A → B → D).
            Si llenas los tres, se usa preorden + inorden.
        </p>
        <div class="botones">
            <button type="submit" class="boton">▶ CONSTRUIR ÁRBOL</button>
        </div>
    </form>

    <form class="ejemplos" method="post" action="">
        <span>EJEMPLO:</span>
        <input type="hidden" name="preorden" value="A → B → D → E → C">
        <input type="hidden" name="inorden" value="D → B → E → A → C">
        <input type="hidden" name="postorden" value="D → E → B → C → A">
        <button class="pastilla" type="submit">Pre: A B D E C · In: D B E A C · Post: D E B C A</button>
    </form>
</section>

<?php if ($resultado !== null): ?>
<section class="resultado">
    <span class="resultado-banner">★ NIVEL SUPERADO ★</span>
    <h2 class="resultado-titulo">
        ÁRBOL CONSTRUIDO CON <?= $nombres[$resultado['par'][0]] ?> + <?= $nombres[$resultado['par'][1]] ?>
    </h2>

    <?php if ($resultado['par'] === ['preorden', 'postorden']): ?>
        <div class="mensaje mensaje-info">
            <strong>NOTA</strong>
            <span>Con preorden y postorden el árbol puede no ser único: cuando un nodo tiene un solo hijo, se ubica a la izquierda.</span>
        </div>
    <?php endif; ?>

    <div class="lienzo-arbol"><?= $resultado['svg'] ?></div>
    <div class="leyenda">
        <span><i class="l-raiz"></i>Raíz</span>
        <span><i class="l-interno"></i>Nodo interno</span>
        <span><i class="l-hoja"></i>Hoja</span>
        <span>Nodos: <b><?= $resultado['nodos'] ?></b></span>
        <span>Altura: <b><?= $resultado['altura'] ?></b></span>
    </div>
</section>

<div class="marcadores">
    <?php $colores = ['preorden' => 'var(--accent)', 'inorden' => 'var(--verde)', 'postorden' => 'var(--amarillo)']; ?>
    <?php foreach ($nombres as $clave => $nombre): ?>
        <?php
        $ingresado = $resultado['ingresados'][$clave] ?? null;
        $coincide = $ingresado === null || implode(' → ', $ingresado) === $resultado['calculados'][$clave];
        ?>
        <div class="marcador" style="--m: <?= $colores[$clave] ?>;">
            <small>
                <?= $nombre ?>
                <?php if ($ingresado !== null && !in_array($clave, $resultado['par'], true)): ?>
                    <span class="insignia<?= $coincide ? '' : ' insignia-mal' ?>"><?= $coincide ? '✔ COINCIDE' : '✖ NO COINCIDE' ?></span>
                <?php endif; ?>
            </small>
            <p class="recorrido"><?= Pagina::e($resultado['calculados'][$clave]) ?></p>
            <p><?= in_array($clave, $resultado['par'], true) ? 'Usado para construir' : ($ingresado === null ? 'Calculado a partir del árbol' : 'Verificado contra el árbol') ?></p>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php $pagina->pie(); ?>
