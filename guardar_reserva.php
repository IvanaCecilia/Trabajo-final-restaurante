<?php
/*
 * CONTROLADOR: GUARDAR RESERVA (Bruno)
 * 1. Recibe los datos del formulario.
 * 2. Le pide al modelo que los valide y los guarde.
 * 3. Muestra la confirmacion o el mensaje que corresponda.
 */
session_start();
require_once 'models/conexion.php';
require_once 'models/Reserva.php';

// Solo se llega por el formulario (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: reserva.php');
    exit;
}

$datos = [
    'nombre'      => trim($_POST['nombre'] ?? ''),
    'email'       => trim($_POST['email'] ?? ''),
    'telefono'    => trim($_POST['telefono'] ?? ''),
    'fecha'       => $_POST['fecha'] ?? '',
    'hora'        => $_POST['hora'] ?? '',
    'personas'    => $_POST['personas'] ?? '',
    'comentarios' => trim($_POST['comentarios'] ?? ''),
];

$error = validarReserva($datos);

if ($error !== '') {
    // Datos incorrectos: mostramos el error
    $titulo     = 'Revisá tu reserva';
    $encabezado = 'No pudimos tomar tu reserva';
    $texto      = $error;
    $vista      = 'views/reserva_mensaje.php';

} elseif ($datos['personas'] === '6+') {
    // Los grupos grandes no se reservan online: tienen que comunicarse
    $titulo          = 'Consulta para grupos grandes';
    $encabezado      = '¡Contactanos!';
    $texto           = 'Para reservas de 6 o más personas, comunicate con nosotros.';
    $mostrarContacto = true;
    $vista           = 'views/reserva_mensaje.php';

} else {
    try {
        $numeroReserva = guardarReserva($conexion, $datos);
        $titulo = 'Reserva confirmada';
        $vista  = 'views/reserva_confirmada.php';
    } catch (PDOException $e) {
        error_log('Error al guardar reserva: ' . $e->getMessage());
        http_response_code(500);
        $titulo     = 'Error';
        $encabezado = 'Ocurrió un error';
        $texto      = 'No pudimos guardar la reserva. Intentá nuevamente.';
        $vista      = 'views/reserva_mensaje.php';
    }
}

require 'views/layout/header.php';
require $vista;
require 'views/layout/footer.php';