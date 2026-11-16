<?php
$titulo = 'Ingresar';
$estilos = ['css/admin.css'];
include 'includes/header.php';
?>

    <main id="contenido">
        <h1>Ingreso del encargado</h1>
        <p>Acceso exclusivo para el personal del restaurante.</p>

        <form id="form-login" class="formulario" action="admin.php" method="post">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" required autocomplete="username">

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">

            <button type="submit" class="boton">Ingresar</button>
        </form>
    </main>

<?php include 'includes/footer.php'; ?>