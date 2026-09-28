<?php
declare(strict_types=1);

class Pagina
{
    private Menu $menu;
    private string $titulo;
    private string $clave;
    private string $base;
    private string $color;

    public function __construct(string $titulo, string $clave = 'inicio', string $base = '../')
    {
        $this->menu   = new Menu();
        $this->titulo = $titulo;
        $this->clave  = $clave;
        $this->base   = $base;

        $app = $this->menu->buscar($clave);
        $this->color = $app['color'] ?? '#4dd8ff';
    }

    public static function e(mixed $valor): string
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }

    /** Estrellas de dificultad, por ejemplo ★★☆ */
    public static function estrellas(int $cantidad, int $maximo = 3): string
    {
        return str_repeat('★', $cantidad) . str_repeat('☆', $maximo - $cantidad);
    }

    public function encabezado(): void
    {
        $titulo = self::e($this->titulo);
        $base   = self::e($this->base);
        $color  = self::e($this->color);
        ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark">
    <title><?= $titulo ?> - Taller PHP Arcade</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&family=Chakra+Petch:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base ?>assets/css/estilos.css?v=<?= filemtime(__DIR__ . '/../assets/css/estilos.css') ?>">
</head>
<body style="--accent: <?= $color ?>;">
<div class="fondo" aria-hidden="true">
    <?= (new FondoRecreativo())->dibujar() ?>
</div>
<div class="crt" aria-hidden="true"></div>

<!-- Menú móvil sin JavaScript: el checkbox abre y cierra la barra de niveles -->
<input type="checkbox" id="menu-abierto" class="menu-check">

<header class="hud">
    <a class="marca" href="<?= $base ?>index.php">
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
    <a href="<?= $base ?>index.php" class="<?= $this->clave === 'inicio' ? 'activo' : '' ?>" style="--c: #ffffff;">
        <span class="nav-num">⌂</span>Inicio
    </a>
    <?php foreach ($this->menu->obtenerAplicaciones() as $i => $app): ?>
        <a href="<?= $base ?>apps/<?= self::e($app['clave']) ?>.php"
           class="<?= $app['clave'] === $this->clave ? 'activo' : '' ?>"
           style="--c: <?= self::e($app['color']) ?>;">
            <span class="nav-num"><?= $i + 1 ?></span><?= self::e($app['titulo']) ?>
        </a>
    <?php endforeach; ?>
</nav>

<main class="contenido">
        <?php
    }

    public function cabeceraApp(int $numero, string $subtitulo): void
    {
        $app = $this->menu->buscar($this->clave);
        $dibujo = $app !== null ? (new DibujoPixel($app['dibujo'], $app['color']))->dibujar(72) : '';
        ?>
    <section class="app-cabecera revelar">
        <a class="volver" href="<?= self::e($this->base) ?>index.php">◀ SELECCIÓN DE NIVEL</a>
        <div class="app-cabecera-fila">
            <div class="app-dibujo"><?= $dibujo ?></div>
            <div>
                <span class="app-numero">NIVEL <?= str_pad((string) $numero, 2, '0', STR_PAD_LEFT) ?>
                    <?php if ($app !== null): ?><em><?= self::estrellas($app['estrellas']) ?></em><?php endif; ?>
                </span>
                <h1><?= self::e($this->titulo) ?></h1>
                <p><span class="mision">MISIÓN ▶</span> <?= self::e($subtitulo) ?></p>
            </div>
        </div>
    </section>
        <?php
    }

    /** Muestra un mensaje de error con estilo "game over". */
    public static function error(?string $mensaje): void
    {
        if ($mensaje === null || $mensaje === '') {
            return;
        }
        echo '<div class="mensaje mensaje-error revelar" role="alert"><strong>¡AUCH! −1 ♥</strong><span>'
            . self::e($mensaje) . '</span></div>';
    }

    public function pie(): void
    {
        ?>
</main>
<footer class="pie">
    <span>TALLER DE PHP · HTML · CSS · PHP · POO</span>
    <span class="pie-parpadeo">INSERT COIN ▮</span>
</footer>
</body>
</html>
        <?php
    }
}
