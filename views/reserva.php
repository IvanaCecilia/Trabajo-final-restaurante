<?php
/*
 * VISTA: FORMULARIO DE RESERVA Y CANCELACION
 * Solo HTML. No recibe datos.
 */
?>

<main id="contenido" class="contenedor pagina-reserva">

    <form action="guardar_reserva.php" method="POST" id="formReserva" class="form-reserva">

        <h1>Reservá tu mesa</h1>
        <p class="subtitulo">Completá el formulario para realizar tu reserva.</p>

        <div class="campo">
            <label for="nombre">Nombre completo:</label>
            <input type="text" id="nombre" name="nombre"
                   placeholder="Tu nombre" maxlength="60" required>
        </div>

        <div class="campo">
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email"
                   placeholder="ejemplo@correo.com" required>
        </div>

        <div class="campo">
            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono"
                   placeholder="Tu teléfono" maxlength="20" required>
        </div>

        <div class="fila">
            <div class="campo">
                <label for="fecha">Fecha de reserva:</label>
                <input type="date" id="fecha" name="fecha" required>
            </div>

            <div class="campo">
                <label for="hora">Hora de reserva:</label>
                <input type="time" id="hora" name="hora" required>
            </div>
        </div>

        <div class="campo">
            <label for="personas">Cantidad de personas:</label>
            <select id="personas" name="personas" required>
                <option value="">Seleccioná una cantidad</option>
                <option value="1">1 persona</option>
                <option value="2">2 personas</option>
                <option value="3">3 personas</option>
                <option value="4">4 personas</option>
                <option value="5">5 personas</option>
                <option value="6+">6 o más personas</option>
            </select>
            <small>Para grupos de 6 o más personas, consultanos.</small>
        </div>

        <div class="campo">
            <label for="comentarios">Comentarios adicionales:</label>
            <textarea id="comentarios" name="comentarios"
                      rows="4"
                      placeholder="Alergias, preferencias o solicitudes..."></textarea>
        </div>

        <div class="botones">
            <button type="submit" class="btn-reservar">Reservar mesa</button>
            <button type="reset" class="btn-limpiar">Limpiar formulario</button>
        </div>

    </form>

    <!-- Formulario independiente para cancelar una reserva -->
    <section class="form-cancelar">
        <h2>Cancelar una reserva</h2>
        <p>Ingresá el número de reserva que recibiste al confirmar.</p>

        <form action="cancelar_reserva.php" method="POST">
            <div class="campo">
                <label for="id_reserva">Número de reserva:</label>
                <input type="number" id="id_reserva" name="id_reserva"
                       min="1" required>
            </div>

            <button type="submit" class="btn-cancelar">
                Cancelar reserva
            </button>
        </form>
    </section>

</main>