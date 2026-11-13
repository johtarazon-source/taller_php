<?php
require_once __DIR__ . '/config/autoload.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark">
    <title>Taller PHP Arcade</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&family=Chakra+Petch:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
<div class="crt" aria-hidden="true"></div>

<input type="checkbox" id="menu-abierto" class="menu-check">

<header class="hud">
    <a class="marca" href="index.php">
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
    <a href="index.php">
        <span class="nav-num">⌂</span>Inicio
    </a>
    <a href="vistas/acronimo.php">
        <span class="nav-num">1</span>Acrónimos
    </a>
    <a href="vistas/series.php">
        <span class="nav-num">2</span>Fibonacci y Factorial
    </a>
    <a href="vistas/estadistica.php">
        <span class="nav-num">3</span>Estadística
    </a>
    <a href="vistas/conjuntos.php">
        <span class="nav-num">4</span>Conjuntos
    </a>
    <a href="vistas/binario.php">
        <span class="nav-num">5</span>Decimal a Binario
    </a>
    <a href="vistas/arbol.php">
        <span class="nav-num">6</span>Árbol Binario
    </a>
    <a href="vistas/calculadora.php">
        <span class="nav-num">7</span>Calculadora
    </a>
</nav>

<main class="contenido">

<section class="hero revelar">
    <span class="hero-insignia">★ HTML · CSS · PHP · POO ★</span>
    <h1 class="hero-titulo">
        <span class="titulo-palabra"><b class="letra-arcade">T</b><b class="letra-arcade">A</b><b class="letra-arcade">L</b><b class="letra-arcade">L</b><b class="letra-arcade">E</b><b class="letra-arcade">R</b></span>
        <span class="titulo-palabra"><b class="letra-arcade">P</b><b class="letra-arcade">H</b><b class="letra-arcade">P</b></span>
        <small class="hero-edicion">ARCADE EDITION</small>
    </h1>
    <p class="press-start">▶ SELECCIONA TU NIVEL ◀</p>
</section>

<h2 class="seccion-titulo">SELECCIÓN DE NIVEL</h2>

<div class="apps-grid">
    <a href="vistas/acronimo.php" class="app-card">
        <div class="app-card-top">
            <div class="app-nivel">NIVEL 01 <span class="app-estrellas" title="Dificultad">★☆☆</span></div>
        </div>
        <h3 class="app-titulo">Acrónimos</h3>
        <p class="app-descripcion">Convierte frases largas como "Portable Network Graphics" en su acrónimo.</p>
        <span class="app-jugar">▶ JUGAR</span>
    </a>
    <a href="vistas/series.php" class="app-card">
        <div class="app-card-top">
            <div class="app-nivel">NIVEL 02 <span class="app-estrellas" title="Dificultad">★★☆</span></div>
        </div>
        <h3 class="app-titulo">Fibonacci y Factorial</h3>
        <p class="app-descripcion">Genera la sucesión de Fibonacci o la serie factorial de un número.</p>
        <span class="app-jugar">▶ JUGAR</span>
    </a>
    <a href="vistas/estadistica.php" class="app-card">
        <div class="app-card-top">
            <div class="app-nivel">NIVEL 03 <span class="app-estrellas" title="Dificultad">★★☆</span></div>
        </div>
        <h3 class="app-titulo">Estadística</h3>
        <p class="app-descripcion">Promedio, mediana y moda de una serie de números reales.</p>
        <span class="app-jugar">▶ JUGAR</span>
    </a>
    <a href="vistas/conjuntos.php" class="app-card">
        <div class="app-card-top">
            <div class="app-nivel">NIVEL 04 <span class="app-estrellas" title="Dificultad">★★☆</span></div>
        </div>
        <h3 class="app-titulo">Conjuntos</h3>
        <p class="app-descripcion">Unión, intersección y diferencias A−B y B−A de dos conjuntos.</p>
        <span class="app-jugar">▶ JUGAR</span>
    </a>
    <a href="vistas/binario.php" class="app-card">
        <div class="app-card-top">
            <div class="app-nivel">NIVEL 05 <span class="app-estrellas" title="Dificultad">★☆☆</span></div>
        </div>
        <h3 class="app-titulo">Decimal a Binario</h3>
        <p class="app-descripcion">Convierte un número entero a binario mostrando cada división.</p>
        <span class="app-jugar">▶ JUGAR</span>
    </a>
    <a href="vistas/arbol.php" class="app-card">
        <div class="app-card-top">
            <div class="app-nivel">NIVEL 06 <span class="app-estrellas" title="Dificultad">★★★</span></div>
        </div>
        <h3 class="app-titulo">Árbol Binario</h3>
        <p class="app-descripcion">Reconstruye y dibuja un árbol a partir de sus recorridos.</p>
        <span class="app-jugar">▶ JUGAR</span>
    </a>
    <a href="vistas/calculadora.php" class="app-card">
        <div class="app-card-top">
            <div class="app-nivel">NIVEL 07 <span class="app-estrellas" title="Dificultad">★★☆</span></div>
        </div>
        <h3 class="app-titulo">Calculadora</h3>
        <p class="app-descripcion">Operaciones básicas y porcentaje con historial persistente.</p>
        <span class="app-jugar">▶ JUGAR</span>
    </a>
</div>

</main>
<footer class="pie">
    <span class="pie-parpadeo">INSERT COIN ▮</span>
</footer>
</body>
</html>
