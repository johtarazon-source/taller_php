<?php
require_once 'config/autoload.php';

$pagina = new Pagina('Taller de PHP', 'inicio', '');
$pagina->encabezado();

$menu = new Menu();
$aplicaciones = $menu->obtenerAplicaciones();
?>

<section class="hero revelar">
    <span class="hero-insignia">★ HTML · CSS · PHP · POO ★</span>
    <h1 class="hero-titulo">
        <?php
        $coloresArcade = ['#00e5ff', '#ffd400', '#b44dff', '#3dff6e', '#ff3d5a', '#3d7bff', '#ff9f1a'];
        $indice = 0;
        foreach (['TALLER', 'PHP'] as $palabra) {
            echo '<span class="titulo-palabra">';
            foreach (mb_str_split($palabra) as $letra) {
                echo '<b class="letra-arcade" style="color: ' . $coloresArcade[$indice++ % 7] . ';">' . $letra . '</b>';
            }
            echo '</span> ';
        }
        ?>
        <small class="hero-edicion">ARCADE EDITION</small>
    </h1>
    <p class="hero-subtitulo">Siete niveles, siete retos de programación. Elige un nivel para empezar a jugar.</p>
    <p class="press-start">▶ SELECCIONA TU NIVEL ◀</p>
</section>

<h2 class="seccion-titulo">SELECCIÓN DE NIVEL</h2>

<div class="apps-grid">
    <?php foreach ($aplicaciones as $i => $app): ?>
        <a href="apps/<?= Pagina::e($app['clave']) ?>.php" class="app-card" style="--card-color: <?= Pagina::e($app['color']) ?>;">
            <div class="app-card-top">
                <?= (new DibujoPixel($app['dibujo'], $app['color']))->dibujar(56) ?>
                <div class="app-nivel">
                    NIVEL <?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?>
                    <span class="app-estrellas" title="Dificultad"><?= Pagina::estrellas($app['estrellas']) ?></span>
                </div>
            </div>
            <h3 class="app-titulo"><?= Pagina::e($app['titulo']) ?></h3>
            <p class="app-descripcion"><?= Pagina::e($app['descripcion']) ?></p>
            <span class="app-jugar">▶ JUGAR</span>
        </a>
    <?php endforeach; ?>
</div>

<?php $pagina->pie(); ?>
