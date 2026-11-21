<?php
/*
 * VISTA: LOGIN
 * Recibe del controlador:
 *   $error            → mensaje si el usuario o la contraseña estan mal ('' si no hay error)
 *   $usuarioIngresado → lo que escribio, para no borrarlo si se equivoco
 */
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