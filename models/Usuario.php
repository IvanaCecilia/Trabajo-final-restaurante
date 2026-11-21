<?php
/*
 * MODELO: USUARIOS (Emma)
 * Todas las consultas a la tabla "usuarios" estan aca.
 * Los controladores llaman a estas funciones; nunca escriben SQL.
 */

// Roles que existen en el sistema: valor guardado => texto que se muestra
const ROLES = [
    'empleado'   => 'Empleado',
    'superadmin' => 'Superadministrador',
];

// Busca un usuario por su nombre de usuario (para el login). Devuelve la fila o false.
function buscarUsuarioPorNombre(PDO $conexion, string $usuario)
{
    $consulta = $conexion->prepare(
        'SELECT id, nombre, usuario, contrasenia_hash, rol FROM usuarios WHERE usuario = ?'
    );
    $consulta->execute([$usuario]);
    return $consulta->fetch();
}

// Busca un usuario por su id (para verificar que siga existiendo). Devuelve la fila o false.
function buscarUsuarioPorId(PDO $conexion, int $id)
{
    $consulta = $conexion->prepare('SELECT id, nombre, usuario, rol FROM usuarios WHERE id = ?');
    $consulta->execute([$id]);
    return $consulta->fetch();
}

// Lista todos los usuarios, sin la contraseña.
function listarUsuarios(PDO $conexion): array
{
    return $conexion->query('SELECT id, nombre, usuario, rol FROM usuarios ORDER BY nombre')->fetchAll();
}

// ¿Ya existe alguien con ese nombre de usuario?
function existeUsuario(PDO $conexion, string $usuario): bool
{
    $consulta = $conexion->prepare('SELECT COUNT(*) FROM usuarios WHERE usuario = ?');
    $consulta->execute([$usuario]);
    return $consulta->fetchColumn() > 0;
}

// Crea un usuario. La contraseña se guarda encriptada, nunca en texto plano.
function crearUsuario(PDO $conexion, string $nombre, string $usuario, string $contrasenia, string $rol): void
{
    $consulta = $conexion->prepare(
        'INSERT INTO usuarios (nombre, usuario, contrasenia_hash, rol) VALUES (?, ?, ?, ?)'
    );
    $consulta->execute([$nombre, $usuario, password_hash($contrasenia, PASSWORD_DEFAULT), $rol]);
}

// Elimina un usuario por su id.
function eliminarUsuario(PDO $conexion, int $id): void
{
    $consulta = $conexion->prepare('DELETE FROM usuarios WHERE id = ?');
    $consulta->execute([$id]);
}