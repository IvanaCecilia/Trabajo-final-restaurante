<?php
require 'models/Plato.php';

$cat = $_GET['cat'] ?? '';
$id  = $_GET['id'] ?? '';

// Le pedimos el plato al modelo (devuelve null si no existe)
$plato = obtenerPlato($cat, $id);

if ($plato === null) {
    http_response_code(404);
    echo "Plato no encontrado. <a href='menu.php'>Volver a la carta</a>";
    exit;
}
$titulo = $plato['nombre'];
include 'views/layout/header.php';
?>

<main class="contenedor" id="contenido">
  <article class="detalle-plato">
    <img src="img/<?= htmlspecialchars($plato['foto']) ?>"
         alt="<?= htmlspecialchars($plato['nombre']) ?>">

    <div class="info-plato">
      <p class="categoria"><?= htmlspecialchars($cat) ?></p>
      <h1><?= htmlspecialchars($plato['nombre']) ?></h1>
      <p><?= htmlspecialchars($plato['descripcion']) ?></p>
      <p class="precio">$<?= number_format($plato['precio'], 0, ',', '.') ?></p>

      <a class="boton" href="reserva.php">Reservar mesa</a>
      <a href="menu.php#<?= urlencode($cat) ?>">← Volver a la carta</a>
    </div>
  </article>
</main>

<?php include 'views/layout/footer.php'; ?>