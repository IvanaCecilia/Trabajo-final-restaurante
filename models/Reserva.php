<?php
/*
 * MODELO: RESERVAS (Bruno y Emma)
 * Todas las consultas y reglas de la tabla "reservas" estan aca.
 */

// Cantidades de personas que se pueden elegir en el formulario
const OPCIONES_PERSONAS = ['1', '2', '3', '4', '5', '6+'];

// Revisa los datos del formulario.
// Devuelve el mensaje del primer error encontrado, o '' si esta todo bien.
function validarReserva(array $datos): string
{
    if (!preg_match("/^[\p{L}\s'-]+$/u", $datos['nombre'])) {
        return 'El nombre contiene caracteres no permitidos.';
    }

    if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        return 'El correo electrónico no es válido.';
    }

    if (!preg_match('/^\+?[0-9\s()-]{7,20}$/', $datos['telefono'])) {
        return 'El teléfono no es válido.';
    }

    // La fecha tiene que existir y tener formato AAAA-MM-DD
    $fechaObjeto = DateTime::createFromFormat('!Y-m-d', $datos['fecha']);
    if (!$fechaObjeto || $fechaObjeto->format('Y-m-d') !== $datos['fecha']) {
        return 'La fecha ingresada no es válida.';
    }

    $hoy = date('Y-m-d');
    if ($datos['fecha'] < $hoy) {
        return 'No podés reservar para una fecha pasada.';
    }

    if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $datos['hora'])) {
        return 'La hora ingresada no es válida.';
    }

    if ($datos['fecha'] === $hoy && $datos['hora'] < date('H:i')) {
        return 'No podés reservar para una hora que ya pasó.';
    }

    if (!in_array($datos['personas'], OPCIONES_PERSONAS, true)) {
        return 'La cantidad de personas no es válida.';
    }

    return '';
}

// Guarda una reserva y devuelve su numero (el id que genera la base).
function guardarReserva(PDO $conexion, array $datos): int
{
    $consulta = $conexion->prepare(
        'INSERT INTO reservas (nombre, email, telefono, fecha, hora, personas, comentarios)
         VALUES (:nombre, :email, :telefono, :fecha, :hora, :personas, :comentarios)'
    );
    $consulta->execute([
        ':nombre'      => $datos['nombre'],
        ':email'       => $datos['email'],
        ':telefono'    => $datos['telefono'],
        ':fecha'       => $datos['fecha'],
        ':hora'        => $datos['hora'],
        ':personas'    => $datos['personas'],
        ':comentarios' => $datos['comentarios'],
    ]);

    return (int) $conexion->lastInsertId();
}

// Cancela (borra) una reserva. Devuelve true si existia y se borro.
function cancelarReserva(PDO $conexion, int $id): bool
{
    $consulta = $conexion->prepare('DELETE FROM reservas WHERE id = ?');
    $consulta->execute([$id]);
    return $consulta->rowCount() > 0;
}

// Reservas de un dia, ordenadas por hora (las muestra el panel).
function listarReservasPorFecha(PDO $conexion, string $fecha): array
{
    $consulta = $conexion->prepare(
        'SELECT id, nombre, email, telefono, fecha, hora, personas, comentarios
         FROM reservas WHERE fecha = ? ORDER BY hora'
    );
    $consulta->execute([$fecha]);
    return $consulta->fetchAll();
}