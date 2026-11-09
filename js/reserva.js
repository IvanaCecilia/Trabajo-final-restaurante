
const formulario = document.getElementById("formReserva");

const nombre = document.getElementById("nombre");
const telefono = document.getElementById("telefono");
const fecha = document.getElementById("fecha");
const hora = document.getElementById("hora");

// Obtener la fecha actual en formato YYYY-MM-DD
function obtenerFechaActual() {
    const hoy = new Date();

    const anio = hoy.getFullYear();
    const mes = String(hoy.getMonth() + 1).padStart(2, "0");
    const dia = String(hoy.getDate()).padStart(2, "0");

    return `${anio}-${mes}-${dia}`;
}

// Establecer la fecha mínima permitida
fecha.min = obtenerFechaActual();

// Validar el nombre
nombre.addEventListener("input", function () {
    const expresion = /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s'-]+$/;

    if (!expresion.test(nombre.value)) {
        nombre.setCustomValidity(
            "El nombre no puede contener números ni símbolos."
        );
    } else {
        nombre.setCustomValidity("");
    }
});

// Validar el teléfono
telefono.addEventListener("input", function () {
    const expresion = /^\+?[0-9\s()-]+$/;
    const cantidadNumeros = telefono.value.replace(/\D/g, "").length;

    if (!expresion.test(telefono.value) || cantidadNumeros < 7) {
        telefono.setCustomValidity(
            "Ingresá un teléfono válido, usando números."
        );
    } else {
        telefono.setCustomValidity("");
    }
});

// Actualizar la hora mínima según la fecha elegida
function actualizarHoraMinima() {
    if (fecha.value === obtenerFechaActual()) {
        const ahora = new Date();

        const horas = String(ahora.getHours()).padStart(2, "0");
        const minutos = String(ahora.getMinutes()).padStart(2, "0");

        hora.min = `${horas}:${minutos}`;
    } else {
        hora.removeAttribute("min");
    }
}

// Ejecutar cuando cambia la fecha
fecha.addEventListener("change", actualizarHoraMinima);

// Validación final al enviar el formulario
formulario.addEventListener("submit", function (event) {
    const hoy = obtenerFechaActual();

    // Validar que la fecha no haya pasado
    if (fecha.value < hoy) {
        event.preventDefault();
        alert("No podés reservar para una fecha pasada.");
        return;
    }

    // Validar que la hora no haya pasado si es hoy
    if (fecha.value === hoy) {
        const ahora = new Date();

        const horaActual =
            String(ahora.getHours()).padStart(2, "0") +
            ":" +
            String(ahora.getMinutes()).padStart(2, "0");

        if (hora.value < horaActual) {
            event.preventDefault();
            alert("No podés reservar para una hora que ya pasó.");
            return;
        }
    }

    // Si todo está correcto
    alert("¡Reserva validada correctamente!");
});
