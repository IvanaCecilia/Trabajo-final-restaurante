<?php
/*
 * MODELO: RESERVAS
 * Todas las consultas a la tabla "reservas" van aca.
 * Por ahora tiene la funcion que usa el panel (Emma).
 * Bruno: aca van tambien guardarReserva() y cancelarReserva().
 */

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