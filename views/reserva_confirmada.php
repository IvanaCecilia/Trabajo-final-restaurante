<?php
/*
 * VISTA: RESERVA CONFIRMADA
 * Recibe del controlador:
 *   $numeroReserva → el numero que genero la base
 *   $datos         → nombre, fecha, hora y personas de la reserva
 */
?>
    <main id="contenido" class="contenedor">
        <h1>¡Tu reserva fue confirmada!</h1>

        <p>Gracias, <?= htmlspecialchars($datos['nombre']) ?>. Tu reserva se guardó correctamente.</p>

        <h2>Tu número de reserva es:</h2>
        <p class="numero-reserva">
            <strong><?= htmlspecialchars($numeroReserva) ?></strong>
        </p>

        <p>Guardá este número. Lo vas a necesitar si querés cancelar tu reserva.</p>

        <p><strong>Fecha:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($datos['fecha']))) ?></p>
        <p><strong>Hora:</strong> <?= htmlspecialchars($datos['hora']) ?></p>
        <p><strong>Personas:</strong> <?= htmlspecialchars($datos['personas']) ?></p>
        <p>Tiene 20 minutos de tolerancia.</p>

        <a href="index.php">Volver al inicio</a>
    </main>