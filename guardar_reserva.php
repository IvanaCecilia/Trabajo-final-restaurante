
<?php
require_once "conexion.php";

// Comprobar que el formulario se haya enviado por POST.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: reserva.php");
    exit;
}

// Recibir los datos.
$nombre = trim($_POST["nombre"] ?? "");
$email = trim($_POST["email"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$fecha = $_POST["fecha"] ?? "";
$hora = $_POST["hora"] ?? "";
$personas = $_POST["personas"] ?? "";
$comentarios = trim($_POST["comentarios"] ?? "");

// Validar nombre.
if (!preg_match("/^[\p{L}\s'-]+$/u", $nombre)) {
    exit("El nombre contiene caracteres no permitidos.");
}

// Validar email.
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("El correo electrónico no es válido.");
}

// Validar teléfono.
if (!preg_match("/^\+?[0-9\s()-]{7,20}$/", $telefono)) {
    exit("El teléfono no es válido.");
}

// Validar que la fecha tenga el formato correcto.
$fechaObjeto = DateTime::createFromFormat('!Y-m-d', $fecha);

if (!$fechaObjeto || $fechaObjeto->format('Y-m-d') !== $fecha) {
    exit("La fecha ingresada no es válida.");
}

// Comparar fechas usando el mismo formato: Y-m-d.
$fechaActual = date("Y-m-d");

if ($fecha < $fechaActual) {
    exit("No podés reservar para una fecha pasada.");
}

// Validar hora.
if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $hora)) {
    exit("La hora ingresada no es válida.");
}

if ($fecha === $fechaActual && $hora < date("H:i")) {
    exit("No podés reservar para una hora que ya pasó.");
}

// Validar cantidad de personas.
if (!in_array($personas, ["1", "2", "3", "4", "5", "6+"], true)) {
    exit("La cantidad de personas no es válida.");
}

// Los grupos grandes deben comunicarse con el restaurante.
if ($personas === "6+") {
    $mensaje = "Para reservas de 6 o más personas, comunicate con nosotros.";
} else {
    $mensaje = "";
}

// Guardar la reserva.
try {

    if ($personas === "6+") {
        // No guardamos automáticamente reservas de grupos grandes.
        $titulo = "Consulta para grupos grandes";
        include 'includes/header.php';
        ?>

        <main id="contenido" class="contenedor">
            <h1>¡Contactanos!</h1>
            <p><?= htmlspecialchars($mensaje) ?></p>
            <p>Teléfono: <a href="tel:01112345678">011 1234-5678</a></p>
            <p>Email: <a href="mailto:reservas@restaurante.com">reservas@restaurante.com</a></p>
            <a href="reserva.php">Volver a reservas</a>
        </main>

        <?php
        include 'includes/footer.php';
        exit;
    }

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

    // Obtener el ID que SQLite acaba de generar.
    $numeroReserva = $conexion->lastInsertId();

    // Mostrar la confirmación.
    $titulo = "Reserva confirmada";
    include 'includes/header.php';
    ?>

    <main id="contenido" class="contenedor">
        <h1>¡Tu reserva fue confirmada!</h1>

        <p>Gracias, <?= htmlspecialchars($nombre) ?>. Tu reserva se guardó correctamente.</p>

        <h2>Tu número de reserva es:</h2>
        <p class="numero-reserva">
            <strong><?= htmlspecialchars($numeroReserva) ?></strong>
        </p>

        <p>Guardá este número. Lo vas a necesitar si querés cancelar tu reserva.</p>

        <p><strong>Fecha:</strong> <?= htmlspecialchars($fecha) ?></p>
        <p><strong>Hora:</strong> <?= htmlspecialchars($hora) ?></p>
        <p><strong>Personas:</strong> <?= htmlspecialchars($personas) ?></p>

        <a href="index.php">Volver al inicio</a>
    </main>

    <?php
    include 'includes/footer.php';

} catch (PDOException $e) {
    error_log("Error al guardar reserva: " . $e->getMessage());
    http_response_code(500);
    echo "Ocurrió un error al guardar la reserva. Intentá nuevamente.";
}
?>
