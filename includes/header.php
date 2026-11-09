<?php
/*
 * HEADER COMÚN
 * Se incluye al principio de cada página PHP:  include 'includes/header.php';
 *
 * Opcional, ANTES del include:
 *   $titulo = 'Menú';                 → título de la pestaña: "Menú | Nombre del Restaurante"
 *   $estilos = ['css/menu.css'];      → hojas de estilo extra de esa página
 */

$nombreSitio = 'Nombre del Restaurante';

// Título de la pestaña
$tituloPestania = isset($titulo) ? $titulo . ' | ' . $nombreSitio : $nombreSitio;

// Página actual (ej: "menu.php"), para marcar el link activo en el menú
$paginaActual = basename($_SERVER['SCRIPT_NAME']);

// Links del menú: archivo => texto
$links = [
    'index.html'   => 'Inicio',
    'menu.php'     => 'Menú',
    'reserva.html' => 'Reservar',
    'login.html'   => 'Ingresar',
];

// Páginas que no están en el menú pero pertenecen a una sección
$secciones = [
    'plato.php' => 'menu.php',
];
$linkActivo = $secciones[$paginaActual] ?? $paginaActual;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPestania) ?></title>
    <link rel="stylesheet" href="css/estilos.css">
    <?php foreach ($estilos ?? [] as $hoja): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($hoja) ?>">
    <?php endforeach; ?>
</head>
<body>
    <a class="saltar-contenido" href="#contenido">Saltar al contenido</a>

    <header class="encabezado">
        <a class="marca" href="index.html"><?= htmlspecialchars($nombreSitio) ?></a>

        <nav class="nav-principal" aria-label="Navegación principal">
            <ul>
                <?php foreach ($links as $archivo => $texto): ?>
                    <li>
                        <a href="<?= $archivo ?>"<?= $archivo === $linkActivo ? ' aria-current="page"' : '' ?>><?= $texto ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </header>