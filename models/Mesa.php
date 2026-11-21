<?php
/*
 * MODELO: MESAS (Emma)
 * Todas las consultas a la tabla "mesas" estan aca.
 */

// Estados posibles de una mesa: valor guardado => texto que se muestra
const ESTADOS_MESA = [
    'libre'     => 'Libre',
    'reservada' => 'Reservada',
    'ocupada'   => 'Ocupada',
];

// Lista todas las mesas ordenadas por numero.
function listarMesas(PDO $conexion): array
{
    return $conexion->query('SELECT id, numero, capacidad, estado FROM mesas ORDER BY numero')->fetchAll();
}

// Cambia el estado de una mesa. Devuelve true si la mesa existe y se actualizo.
function cambiarEstadoMesa(PDO $conexion, int $mesaId, string $estado): bool
{
    $consulta = $conexion->prepare('UPDATE mesas SET estado = ? WHERE id = ?');
    $consulta->execute([$estado, $mesaId]);
    return $consulta->rowCount() > 0;
}