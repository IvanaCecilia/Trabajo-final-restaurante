<?php
/*
 * VISTA: HEADER COMUN (layout)
 * Lo incluye el controlador antes de la vista de cada pagina:
 *     require 'views/layout/header.php';
 *
 * Opcional, ANTES del include:
 *   $titulo  = 'Menú';               → título de la pestaña: "Menú | Nombre del Restaurante"
 *   $estilos = ['css/menu.css'];     → hojas de estilo extra de esa página
 */

// La sesión se inicia acá, antes de mandar cualquier HTML,
// así todas las páginas saben si hay alguien logueado.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$logueado = isset($_SESSION['usuario']);

$nombreSitio = 'Nombre del Restaurante';

// Título de la pestaña
$tituloPestania = isset($titulo) ? $titulo . ' | ' . $nombreSitio : $nombreSitio;

// Página actual (ej: "menu.php"), para marcar el link activo en el menú
$paginaActual = basename($_SERVER['SCRIPT_NAME']);

// Links del menú: archivo => texto
$links = [
    'index.php'    => 'Inicio',
    'menu.php'     => 'Menú',
    'reserva.php' => 'Reservar',
];

// El último link cambia según haya alguien logueado o no
if ($logueado) {
    $links['admin.php']  = 'Panel';
    $links['logout.php'] = 'Salir';
} else {
    $links['login.php'] = 'Ingresar';
}

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
    <meta name="description" content="<?= htmlspecialchars($nombreSitio) ?>: menú y reservas online.">
    <title><?= htmlspecialchars($tituloPestania) ?></title>
    <link rel="stylesheet" href="css/estilos.css">
    <?php foreach ($estilos ?? [] as $hoja): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($hoja) ?>">
    <?php endforeach; ?>
</head>
<body>
    <a class="saltar-contenido" href="#contenido">Saltar al contenido</a>

    <header class="encabezado">
        <a class="marca" href="index.php"><?= htmlspecialchars($nombreSitio) ?></a>

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