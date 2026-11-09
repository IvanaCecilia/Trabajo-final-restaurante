
<?php

try {
    // Ruta de la base de datos
    $ruta = __DIR__ . "/reservas.db";

    // Conectar con SQLite
    $conexion = new PDO("sqlite:" . $ruta);

    // Activar errores de PDO
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear la tabla de reservas
    $sql = "CREATE TABLE IF NOT EXISTS reservas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre TEXT NOT NULL,
        email TEXT NOT NULL,
        telefono TEXT NOT NULL,
        fecha TEXT NOT NULL,
        hora TEXT NOT NULL,
        personas TEXT NOT NULL,
        comentarios TEXT,
        fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
    )";

    $conexion->exec($sql);

} catch (PDOException $e) {
    die("Error al conectar con SQLite: " . $e->getMessage());
}

?>
