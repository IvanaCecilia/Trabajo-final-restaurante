<?php
/*
 * CONTROLADOR: LOGIN (Emma)
 * GET  → muestra el formulario
 * POST → le pide al modelo el usuario y verifica la contraseña
 */
session_start();
require_once 'models/conexion.php';
require_once 'models/Usuario.php';

// Si ya hay alguien logueado, no tiene sentido mostrar el login
if (isset($_SESSION['usuario'])) {
    header('Location: admin.php');
    exit;
}

$error = '';
$usuarioIngresado = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioIngresado = trim($_POST['usuario'] ?? '');
    $contrasenia = $_POST['password'] ?? '';

    $fila = buscarUsuarioPorNombre($conexion, $usuarioIngresado);

    // password_verify compara la contraseña escrita con el hash guardado
    if ($fila && password_verify($contrasenia, $fila['contrasenia_hash'])) {
        session_regenerate_id(true);   // nueva sesión al entrar (buena práctica de seguridad)

        // Guardamos en la sesión solo lo necesario (nunca la contraseña)
        $_SESSION['usuario'] = [
            'id'      => $fila['id'],
            'nombre'  => $fila['nombre'],
            'usuario' => $fila['usuario'],
            'rol'     => $fila['rol'],
        ];

        header('Location: admin.php');
        exit;
    }

    // Mismo mensaje si falla el usuario o la contraseña: así no se revela cuál existe
    $error = 'Usuario o contraseña incorrectos.';
}

$titulo = 'Ingresar';
$estilos = ['css/admin.css'];

require 'views/layout/header.php';
require 'views/login.php';
require 'views/layout/footer.php';