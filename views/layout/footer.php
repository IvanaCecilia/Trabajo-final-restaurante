<?php
/*
 * VISTA: FOOTER COMUN (layout)
 * Lo incluye el controlador después de la vista de cada pagina:
 *     require 'views/layout/footer.php';
 *
 * Opcional, antes del include:
 *   $scripts = ['js/admin.js'];   → archivos JS que necesita esa página
 */
?>
    <footer class="pie">
        <p><strong><?= htmlspecialchars($nombreSitio) ?></strong></p>
        <address>
            Av. Siempre Viva 742, Lomas de Zamora<br>
            Tel: <a href="tel:01112345678">011 1234-5678</a> ·
            <a href="mailto:reservas@restaurante.com">reservas@restaurante.com</a>
        </address>
        <p><small>TP Final Desarrollo Web · UNLZ · <?= date('Y') ?></small></p>
    </footer>

    <?php foreach ($scripts ?? [] as $script): ?>
        <script src="<?= htmlspecialchars($script) ?>"></script>
    <?php endforeach; ?>
</body>
</html>