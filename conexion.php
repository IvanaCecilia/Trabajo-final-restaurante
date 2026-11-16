<?php
/*
 * CONEXION A LA BASE DE DATOS (SQLite)
 * Archivo compartido: lo usan reservas (Bruno) y login/panel (Emma).
 *
 * La base es el archivo reservas.db. Si no existe, se crea sola,
 * con todas las tablas y los datos iniciales (mesas y usuario admin).
 *
 * Uso en cualquier pagina:  require_once 'conexion.php';  → queda disponible $conexion
 */

try {
    $ruta = __DIR__ . '/reservas.db';

    $conexion = new PDO('sqlite:' . $ruta);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // ---------- Reservas (Bruno) ----------
    $conexion->exec("CREATE TABLE IF NOT EXISTS reservas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre TEXT NOT NULL,
        email TEXT NOT NULL,
        telefono TEXT NOT NULL,
        fecha TEXT NOT NULL,
        hora TEXT NOT NULL,
        personas TEXT NOT NULL,
        comentarios TEXT,
        fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // ---------- Usuarios del panel (Emma) ----------
    // rol: 'superadmin' (gestiona empleados) o 'empleado'
    $conexion->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre TEXT NOT NULL,
        usuario TEXT NOT NULL UNIQUE,
        contrasenia_hash TEXT NOT NULL,
        rol TEXT NOT NULL DEFAULT 'empleado' CHECK (rol IN ('superadmin', 'empleado'))
    )");

    // ---------- Mesas del salon (Emma) ----------
    // estado: lo cambia a mano el personal desde el panel
    $conexion->exec("CREATE TABLE IF NOT EXISTS mesas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        numero INTEGER NOT NULL UNIQUE,
        capacidad INTEGER NOT NULL,
        estado TEXT NOT NULL DEFAULT 'libre' CHECK (estado IN ('libre', 'reservada', 'ocupada'))
    )");

    // ---------- Datos iniciales (solo la primera vez) ----------

    // Usuario superadministrador de prueba:  admin / admin123
    $cantidadUsuarios = $conexion->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();
    if ($cantidadUsuarios == 0) {
        $consulta = $conexion->prepare(
            'INSERT INTO usuarios (nombre, usuario, contrasenia_hash, rol) VALUES (?, ?, ?, ?)'
        );
        $consulta->execute(['Administrador', 'admin', password_hash('admin123', PASSWORD_DEFAULT), 'superadmin']);
    }

    // Mesas: 4 de 2 personas, 4 de 4, 2 de 6 y 2 de 8
    $cantidadMesas = $conexion->query('SELECT COUNT(*) FROM mesas')->fetchColumn();
    if ($cantidadMesas == 0) {
        $capacidades = [2, 2, 2, 2, 4, 4, 4, 4, 6, 6, 8, 8];
        $consulta = $conexion->prepare('INSERT INTO mesas (numero, capacidad) VALUES (?, ?)');
        foreach ($capacidades as $i => $capacidad) {
            $consulta->execute([$i + 1, $capacidad]);
        }
    }

} catch (PDOException $e) {
    die('Error al conectar con la base de datos: ' . $e->getMessage());
}