<?php
/*
 * CONTROLADOR: FORMULARIO DE RESERVA (Bruno)
 * Solo muestra el formulario. Lo procesan guardar_reserva.php y cancelar_reserva.php.
 */
session_start();

$titulo  = 'Reservar una mesa';
$estilos = ['css/reserva.css'];
$scripts = ['js/reserva.js'];

require 'views/layout/header.php';
require 'views/reserva.php';
require 'views/layout/footer.php';