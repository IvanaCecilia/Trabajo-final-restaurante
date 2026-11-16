document.addEventListener("DOMContentLoaded", function () {
  var inputBuscador = document.getElementById("buscador");
  var btnBuscar     = document.getElementById("btnBuscar");
  var botonesFiltro = document.querySelectorAll(".filtro");
  var secciones     = document.querySelectorAll(".seccion-categoria");
  var mensaje       = document.getElementById("sinResultados");

  var categoriaActiva = "Todos";

  // Minúsculas y sin tildes
  function normalizar(texto) {
    return texto.toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .trim();
  }

  function aplicarFiltros() {
    var texto = normalizar(inputBuscador.value);
    var totalVisibles = 0;

    secciones.forEach(function (seccion) {
      var titulo = seccion.querySelector(".titulo-categoria").textContent.trim();
      var coincideCategoria = categoriaActiva === "Todos" || titulo === categoriaActiva;

      var visiblesEnSeccion = 0;
      var tarjetas = seccion.querySelectorAll(".tarjeta-plato");

      tarjetas.forEach(function (tarjeta) {
        var nombre = normalizar(tarjeta.querySelector("h3").textContent);
        var mostrar = coincideCategoria && nombre.includes(texto);

        tarjeta.hidden = !mostrar;
        if (mostrar) visiblesEnSeccion++;
      });

      seccion.hidden = visiblesEnSeccion === 0;
      totalVisibles += visiblesEnSeccion;
    });

    mensaje.hidden = totalVisibles > 0;
  }

  botonesFiltro.forEach(function (boton) {
    boton.addEventListener("click", function () {
      botonesFiltro.forEach(function (b) { b.classList.remove("activo"); });
      boton.classList.add("activo");
      categoriaActiva = boton.textContent.trim();
      aplicarFiltros();
    });
  });

  inputBuscador.addEventListener("input", aplicarFiltros);
  btnBuscar.addEventListener("click", aplicarFiltros);
});