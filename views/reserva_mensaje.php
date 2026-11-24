<?php
/*
 * VISTA: MENSAJE DE RESERVAS
 * Una sola vista para los avisos: errores del formulario, grupos grandes
 * y resultado de la cancelacion.
 * Recibe del controlador:
 *   $encabezado      → titulo del mensaje
 *   $texto           → explicacion
 *   $mostrarContacto → true para mostrar telefono y mail del restaurante
 */
?>
    <main id="contenido" class="contenedor">
        <h1><?= htmlspecialchars($encabezado) ?></h1>
        <p><?= htmlspecialchars($texto) ?></p>

        <?php if (!empty($mostrarContacto)): ?>
            <p>Teléfono: <a href="tel:01112345678">011 1234-5678</a></p>
            <p>Email: <a href="mailto:reservas@restaurante.com">reservas@restaurante.com</a></p>
        <?php endif; ?>

        <a href="reserva.php">Volver a reservas</a>
    </main>