<?php
/*
 * VISTA: INICIO
 * Solo HTML. No recibe datos: es contenido fijo.
 */
?>
    <main id="contenido">
        <section class="portada" aria-labelledby="titulo-portada">
            <img src="img/restaurante.jpg" alt="Salón del restaurante con las mesas preparadas para la cena">
            <h1 id="titulo-portada">Nombre del Restaurante</h1>
            <p>Cocina casera, parrilla y pastas en un ambiente familiar.</p>
            <a class="boton" href="reserva.php">Reservar una mesa</a>
            <a class="boton boton--secundario" href="menu.php">Ver el menú</a>
            
        </section>

        <section aria-labelledby="titulo-nosotros">
            <h2 id="titulo-nosotros">Sobre nosotros</h2>
            <p>Somos un restaurante familiar con más de 20 años en el barrio. Trabajamos con productos frescos y recetas de siempre.</p>
        </section>

        <section aria-labelledby="titulo-horarios">
            <h2 id="titulo-horarios">Horarios</h2>
            <ul>
                <li>Martes a domingo · Mediodía: 12:00 a 15:30 hs</li>
                <li>Martes a domingo · Noche: 20:00 a 23:30 hs</li>
                <li>Lunes: cerrado</li>
            </ul>
        </section>

        <section aria-labelledby="titulo-ubicacion">
            <h2 id="titulo-ubicacion">Dónde estamos</h2>
            <address>Av. Siempre Viva 742, Lomas de Zamora</address>
        </section>

        <section aria-labelledby="titulo-reservas">
            <h2 id="titulo-reservas">Reservas</h2>
            <p>Reservá tu mesa online y te confirmamos al instante si hay lugar.</p>
            <a class="boton" href="reserva.php">Hacer una reserva</a>
        </section>
    </main>