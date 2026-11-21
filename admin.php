<?php
/*
 * CONTROLADOR: PANEL DEL ENCARGADO (Emma) - ZONA PROTEGIDA
 *
 * 1. Verifica que haya sesión y que el usuario siga existiendo.
 * 2. Si llega un formulario (POST), le pide al modelo que haga el cambio.
 * 3. Le pide al modelo los datos y se los pasa a la vista.
 *
 * Permisos:
 * - Empleado y superadmin: ven las reservas y cambian el estado de las mesas.
 * - Solo superadmin: ve y gestiona los empleados.
 */
session_start();

// Si no hay nadie logueado, lo mandamos al login
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

require_once 'models/conexion.php';
require_once 'models/Usuario.php';
require_once 'models/Mesa.php';
require_once 'models/Reserva.php';

// Verificamos en la base que el usuario siga existiendo.
// Si el superadmin lo eliminó mientras tenía la sesión abierta, lo sacamos del panel.
$usuarioEnBase = buscarUsuarioPorId($conexion, (int) $_SESSION['usuario']['id']);
if ($usuarioEnBase === false) {
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit;
}
// Por si le cambiaron el rol, usamos siempre el de la base
$_SESSION['usuario']['rol'] = $usuarioEnBase['rol'];

$usuarioActual = $_SESSION['usuario'];
$esSuperadmin = $usuarioActual['rol'] === 'superadmin';

/* =====================================================================
   ACCIONES (formularios enviados por POST)
   Después de cada acción se redirige a admin.php: así, si se recarga
   la página, el formulario no se vuelve a enviar.
   ===================================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $mensaje = '';
    $tipo = 'exito';

    // ---------- Cambiar el estado de una mesa (todos) ----------
    if ($accion === 'cambiar_estado') {
        $mesaId = (int) ($_POST['mesa_id'] ?? 0);
        $estado = $_POST['estado'] ?? '';

        if (!isset(ESTADOS_MESA[$estado])) {
            $mensaje = 'Estado no válido.';
            $tipo = 'error';
        } elseif (cambiarEstadoMesa($conexion, $mesaId, $estado)) {
            $mensaje = 'Estado de la mesa actualizado.';
        } else {
            $mensaje = 'La mesa no existe.';
            $tipo = 'error';
        }
    }

    // ---------- Agregar empleado (solo superadmin) ----------
    elseif ($accion === 'agregar_empleado' && $esSuperadmin) {
        $nombre      = trim($_POST['nombre'] ?? '');
        $usuario     = trim($_POST['usuario'] ?? '');
        $contrasenia = $_POST['password'] ?? '';
        $rol         = $_POST['rol'] ?? 'empleado';

        if ($nombre === '' || $usuario === '' || $contrasenia === '') {
            $mensaje = 'Completá todos los campos.';
            $tipo = 'error';
        } elseif (strlen($contrasenia) < 6) {
            $mensaje = 'La contraseña tiene que tener al menos 6 caracteres.';
            $tipo = 'error';
        } elseif (!isset(ROLES[$rol])) {
            $mensaje = 'Rol no válido.';
            $tipo = 'error';
        } elseif (existeUsuario($conexion, $usuario)) {
            $mensaje = 'Ya existe un usuario con ese nombre de usuario.';
            $tipo = 'error';
        } else {
            crearUsuario($conexion, $nombre, $usuario, $contrasenia, $rol);
            $mensaje = 'Empleado agregado.';
        }
    }

    // ---------- Eliminar empleado (solo superadmin, no a sí mismo) ----------
    elseif ($accion === 'eliminar_empleado' && $esSuperadmin) {
        $usuarioId = (int) ($_POST['usuario_id'] ?? 0);

        if ($usuarioId === (int) $usuarioActual['id']) {
            $mensaje = 'No podés eliminar tu propio usuario.';
            $tipo = 'error';
        } else {
            eliminarUsuario($conexion, $usuarioId);
            $mensaje = 'Empleado eliminado.';
        }
    }

    // Guardamos el mensaje para mostrarlo una vez después de redirigir
    if ($mensaje !== '') {
        $_SESSION['mensaje'] = ['texto' => $mensaje, 'tipo' => $tipo];
    }

    header('Location: admin.php');
    exit;
}

/* =====================================================================
   DATOS PARA LA VISTA
   ===================================================================== */

// Mensaje de la última acción (se muestra una sola vez)
$mensaje = $_SESSION['mensaje'] ?? null;
unset($_SESSION['mensaje']);

// Reservas del día elegido (por defecto, hoy)
$fecha = $_GET['fecha'] ?? date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
    $fecha = date('Y-m-d');
}

$mesas     = listarMesas($conexion);
$reservas  = listarReservasPorFecha($conexion, $fecha);
$empleados = $esSuperadmin ? listarUsuarios($conexion) : [];

$titulo  = 'Panel del encargado';
$estilos = ['css/admin.css'];
$scripts = ['js/admin.js'];

require 'views/layout/header.php';
require 'views/admin.php';
require 'views/layout/footer.php';