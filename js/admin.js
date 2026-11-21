/*
 * JS DEL PANEL (Emma)
 * 1. Contador de mesas por estado.
 * 2. Confirmación antes de eliminar un empleado.
 */
document.addEventListener('DOMContentLoaded', function () {

  // ---------- 1. Contador de mesas ----------
  var contador = document.getElementById('contador-mesas');
  var mesas = document.querySelectorAll('.mesa');

  if (contador) {
    var cantidad = { libre: 0, reservada: 0, ocupada: 0 };

    mesas.forEach(function (mesa) {
      var estado = mesa.dataset.estado;          // lee el atributo data-estado
      if (estado in cantidad) {
        cantidad[estado]++;
      }
    });

    contador.textContent =
      'Libres: ' + cantidad.libre +
      ' · Reservadas: ' + cantidad.reservada +
      ' · Ocupadas: ' + cantidad.ocupada;
  }

  // ---------- 2. Confirmar antes de eliminar ----------
  var formulariosEliminar = document.querySelectorAll('.form-eliminar');

  formulariosEliminar.forEach(function (formulario) {
    formulario.addEventListener('submit', function (evento) {
      var nombre = formulario.dataset.nombre;
      var seguro = confirm('¿Eliminar a ' + nombre + '? Esta acción no se puede deshacer.');

      if (!seguro) {
        evento.preventDefault();               // cancela el envío del formulario
      }
    });
  });
});