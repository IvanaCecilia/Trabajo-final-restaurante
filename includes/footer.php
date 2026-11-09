<?php
/*
 * FOOTER COMÚN
 * Se incluye al final de cada página PHP:  include 'includes/footer.php';
 * Cierra el <body> y el <html>, así que va DESPUÉS del </main>.
 */
?>
    <footer class="pie">
        <p><strong>Nombre del Restaurante</strong></p>
        <address>
            Av. Siempre Viva 742, Lomas de Zamora<br>
            Tel: <a href="tel:01112345678">011 1234-5678</a> ·
            <a href="mailto:reservas@restaurante.com">reservas@restaurante.com</a>
        </address>
        <p><small>TP Final Desarrollo Web · UNLZ · <?= date('Y') ?></small></p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>