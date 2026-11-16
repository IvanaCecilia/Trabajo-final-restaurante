<?php
/*
 * LOGIN (Emma)
 * GET  → muestra el formulario
 * POST → verifica usuario y contraseña contra la tabla usuarios
 */
session_start();
require_once 'conexion.php';

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

    // Buscar al usuario (consulta preparada: nunca se pega lo que escribió el usuario en el SQL)
    $consulta = $conexion->prepare('SELECT * FROM usuarios WHERE usuario = ?');
    $consulta->execute([$usuarioIngresado]);
    $fila = $consulta->fetch();

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
include 'includes/header.php';
?>

    <main id="contenido">
        <h1>Ingreso del encargado</h1>
        <p>Acceso exclusivo para el personal del restaurante.</p>

        <?php if ($error): ?>
            <p class="mensaje-error" role="alert"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form id="form-login" class="formulario" action="login.php" method="post">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" value="<?= htmlspecialchars($usuarioIngresado) ?>" required autocomplete="username">

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">

            <button type="submit" class="boton">Ingresar</button>
        </form>
    </main>

<?php include 'includes/footer.php'; ?>