<?php
/*
 * CONTROLADOR: CANCELAR RESERVA (Bruno)
 * Recibe el numero de reserva y le pide al modelo que la cancele.
 */
session_start();
require_once 'models/conexion.php';
require_once 'models/Reserva.php';

// Solo se llega por el formulario (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: reserva.php');
    exit;
}

$idReserva = filter_input(INPUT_POST, 'id_reserva', FILTER_VALIDATE_INT);

if (!$idReserva || $idReserva <= 0) {
    $encabezado = 'Número no válido';
    $texto      = 'El número de reserva ingresado no es válido.';
} else {
    try {
        if (cancelarReserva($conexion, $idReserva)) {
            $encabezado = 'Reserva cancelada';
            $texto      = "La reserva número $idReserva fue cancelada correctamente.";
        } else {
            $encabezado = 'No encontramos esa reserva';
            $texto      = 'Verificá que el número ingresado sea correcto.';
        }
    } catch (PDOException $e) {
        error_log('Error al cancelar reserva: ' . $e->getMessage());
        http_response_code(500);
        $encabezado = 'Ocurrió un error';
        $texto      = 'No pudimos cancelar la reserva. Intentá nuevamente.';
    }
}

$titulo = 'Cancelar reserva';

require 'views/layout/header.php';
require 'views/reserva_mensaje.php';
require 'views/layout/footer.php';