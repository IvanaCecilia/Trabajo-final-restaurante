
<?php
require_once "conexion.php";

// Verificar que se haya enviado el formulario.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: reserva.php");
    exit;
}

// Obtener el número de reserva.
$idReserva = filter_input(INPUT_POST, "id_reserva", FILTER_VALIDATE_INT);

// Validar el número.
if (!$idReserva || $idReserva <= 0) {
    exit("El número de reserva ingresado no es válido.");
}

try {

    // Eliminar la reserva que coincida con el ID.
    $consulta = $conexion->prepare(
        "DELETE FROM reservas WHERE id = :id"
    );

    $consulta->execute([
        ":id" => $idReserva
    ]);

    $titulo = "Cancelar reserva";
    include 'includes/header.php';
    ?>

    <main id="contenido" class="contenedor">

        <?php if ($consulta->rowCount() > 0): ?>

            <h1>Reserva cancelada</h1>
            <p>La reserva número
                <strong><?= htmlspecialchars((string)$idReserva) ?></strong>
                fue cancelada correctamente.
            </p>

        <?php else: ?>

            <h1>No encontramos esa reserva</h1>
            <p>Verificá que el número ingresado sea correcto.</p>

        <?php endif; ?>

        <a href="reserva.php">Volver a reservas</a>

    </main>

    <?php
    include 'includes/footer.php';

} catch (PDOException $e) {
    error_log("Error al cancelar reserva: " . $e->getMessage());
    http_response_code(500);
    echo "Ocurrió un error al cancelar la reserva.";
}
?>
