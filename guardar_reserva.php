
<?php

// Incluir la conexión a SQLite
require_once "conexion.php";

// Comprobar que el formulario se haya enviado por POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Acceso no permitido.");
}

// Recibir los datos
$nombre = trim($_POST["nombre"] ?? "");
$email = trim($_POST["email"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$fecha = $_POST["fecha"] ?? "";
$hora = $_POST["hora"] ?? "";
$personas = $_POST["personas"] ?? "";
$comentarios = trim($_POST["comentarios"] ?? "");

// Validar nombre
if (!preg_match("/^[\p{L}\s'-]+$/u", $nombre)) {
    exit("El nombre contiene caracteres no permitidos.");
}

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("El correo electrónico no es válido.");
}

// Validar teléfono
if (!preg_match("/^\+?[0-9\s()-]{7,20}$/", $telefono)) {
    exit("El teléfono no es válido.");
}

// Validar fecha
$fechaActual = date("d-m-y");

if ($fecha < $fechaActual) {
    exit("No podés reservar para una fecha pasada.");
}

// Validar hora
if ($fecha === $fechaActual && $hora < date("H:i")) {
    exit("No podés reservar para una hora que ya pasó.");
}

// Validar cantidad de personas
if (!in_array($personas, ["1", "2", "3", "4", "5", "6+"], true)) {
    exit("La cantidad de personas no es válida.");
}

// Guardar la reserva
try {

    $sql = "INSERT INTO reservas
            (nombre, email, telefono, fecha, hora, personas, comentarios)
            VALUES
            (:nombre, :email, :telefono, :fecha, :hora, :personas, :comentarios)";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ":nombre" => $nombre,
        ":email" => $email,
        ":telefono" => $telefono,
        ":fecha" => $fecha,
        ":hora" => $hora,
        ":personas" => $personas,
        ":comentarios" => $comentarios
    ]);

    echo "¡Reserva guardada correctamente!";

} catch (PDOException $e) {
    echo "Error al guardar la reserva.";
}

?>
