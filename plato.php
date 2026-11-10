<?php
require 'includes/catalogo.php';

$cat = $_GET['cat'] ?? '';
$id  = $_GET['id'] ?? '';

// Validar que la categoría y el plato existan
if (!isset($platos[$cat]) || !ctype_digit((string)$id) || !isset($platos[$cat][(int)$id])) {
    http_response_code(404);
    echo "Plato no encontrado. <a href='menu.php'>Volver a la carta</a>";
    exit;
}

$plato = $platos[$cat][(int)$id];
include 'includes/header.php';
?>

<main class="contenedor">
  <article class="detalle-plato">
    <img src="imagenes/<?= htmlspecialchars($plato['foto']) ?>"
         alt="<?= htmlspecialchars($plato['nombre']) ?>">

    <div class="info-plato">
      <p class="categoria"><?= htmlspecialchars($cat) ?></p>
      <h1><?= htmlspecialchars($plato['nombre']) ?></h1>
      <p><?= htmlspecialchars($plato['descripcion']) ?></p>
      <p class="precio">$<?= number_format($plato['precio'], 0, ',', '.') ?></p>

      <a class="boton" href="reservas.html">Reservar mesa</a>
      <a href="menu.php#<?= urlencode($cat) ?>">← Volver a la carta</a>
    </div>
  </article>
</main>

<?php include 'includes/footer.php'; ?>