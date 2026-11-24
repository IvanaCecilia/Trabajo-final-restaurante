<?php
require "includes/catalogo.php";
/** @var array $platos */
$titulo = "Menú";
include "views/layout/header.php";
 ?>
<main class="contenedor" id="contenido">
    <h1> Nuestra carta </h1>

<!-- Buscador y filtros (solo diseño por ahora) -->
<div class="barra-filtros">
  <div class="buscador">
    <input type="search" id="buscador" placeholder="Buscar plato..." aria-label="Buscar plato">
    <button type="button" id="btnBuscar" class="boton">Buscar</button>
  </div>

  <div class="botones-filtro">
    <button type="button" class="filtro activo">Todos</button>
    <?php foreach ($platos as $categoria => $lista): ?>
      <button type="button" class="filtro"><?= htmlspecialchars($categoria) ?></button>
    <?php endforeach; ?>
  </div>
</div>
<p id="sinResultados" class="sin-resultados" hidden>No encontramos platos con ese criterio.</p>

    <!--Indice de categorias-->
    <nav class="indice-categorias">
        <?php foreach ($platos as $categoria => $lista): ?>
            <a href="#<?= urlencode($categoria) ?>"><?= htmlspecialchars($categoria) ?></a>
        <?php endforeach; ?>
    </nav>
    <?php foreach ($platos as $categoria => $lista): ?>
    <section class="seccion-categoria" id="<?= urlencode($categoria) ?>">
        <h2 class="titulo-categoria"><?= htmlspecialchars($categoria) ?></h2>

            <div class="grilla-platos">
                <?php foreach ($lista as $i => $plato): ?>
                    <article class="tarjeta-plato">
                        <img src="img/<?= htmlspecialchars($plato['foto']) ?>"
                            alt="<?= htmlspecialchars($plato['nombre']) ?>">
                        <h3><?= htmlspecialchars($plato['nombre']) ?></h3>
                        <p class="precio">$<?= number_format($plato['precio'], 0, ',', '.') ?></p>
                        <a class="boton" href="plato.php?cat=<?= urlencode($categoria) ?>&id=<?= $i ?>">
                            Ver detalle
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>
</main>
<script src="js/menu.js"></script>
<?php include 'views/layout/footer.php'; ?>




