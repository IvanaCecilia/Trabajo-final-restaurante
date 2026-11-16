<?php
/*
 * PANEL DEL ENCARGADO (Emma) - ZONA PROTEGIDA
 *
 * - Solo se puede ver con la sesión iniciada.
 * - Empleados y superadmin: ven las reservas y cambian el estado de las mesas.
 * - Solo el superadmin: ve y gestiona los empleados.
 */
session_start();

// Si no hay nadie logueado, lo mandamos al login
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

require_once 'conexion.php';

$usuarioActual = $_SESSION['usuario'];
$esSuperadmin = $usuarioActual['rol'] === 'superadmin';

$estadosValidos = ['libre' => 'Libre', 'reservada' => 'Reservada', 'ocupada' => 'Ocupada'];
$rolesValidos   = ['empleado' => 'Empleado', 'superadmin' => 'Superadministrador'];

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

        if (isset($estadosValidos[$estado])) {
            $consulta = $conexion->prepare('UPDATE mesas SET estado = ? WHERE id = ?');
            $consulta->execute([$estado, $mesaId]);
            $mensaje = 'Estado de la mesa actualizado.';
        } else {
            $mensaje = 'Estado no válido.';
            $tipo = 'error';
        }
    }

    // ---------- Agregar empleado (solo superadmin) ----------
    elseif ($accion === 'agregar_empleado' && $esSuperadmin) {
        $nombre      = trim($_POST['nombre'] ?? '');
        $usuario     = trim($_POST['usuario'] ?? '');
        $contrasenia = $_POST['password'] ?? '';
        $rol         = $_POST['rol'] ?? 'empleado';

        $existe = $conexion->prepare('SELECT COUNT(*) FROM usuarios WHERE usuario = ?');
        $existe->execute([$usuario]);

        if ($nombre === '' || $usuario === '' || $contrasenia === '') {
            $mensaje = 'Completá todos los campos.';
            $tipo = 'error';
        } elseif (strlen($contrasenia) < 6) {
            $mensaje = 'La contraseña tiene que tener al menos 6 caracteres.';
            $tipo = 'error';
        } elseif (!isset($rolesValidos[$rol])) {
            $mensaje = 'Rol no válido.';
            $tipo = 'error';
        } elseif ($existe->fetchColumn() > 0) {
            $mensaje = 'Ya existe un usuario con ese nombre de usuario.';
            $tipo = 'error';
        } else {
            $consulta = $conexion->prepare(
                'INSERT INTO usuarios (nombre, usuario, contrasenia_hash, rol) VALUES (?, ?, ?, ?)'
            );
            $consulta->execute([$nombre, $usuario, password_hash($contrasenia, PASSWORD_DEFAULT), $rol]);
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
            $consulta = $conexion->prepare('DELETE FROM usuarios WHERE id = ?');
            $consulta->execute([$usuarioId]);
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
   DATOS PARA MOSTRAR
   ===================================================================== */

// Mensaje de la última acción (se muestra una sola vez)
$mensaje = $_SESSION['mensaje'] ?? null;
unset($_SESSION['mensaje']);

// Mesas
$mesas = $conexion->query('SELECT * FROM mesas ORDER BY numero')->fetchAll();

// Reservas del día elegido (por defecto, hoy)
$fecha = $_GET['fecha'] ?? date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
    $fecha = date('Y-m-d');
}
$consulta = $conexion->prepare('SELECT * FROM reservas WHERE fecha = ? ORDER BY hora');
$consulta->execute([$fecha]);
$reservas = $consulta->fetchAll();

// Empleados (solo los ve el superadmin)
$empleados = [];
if ($esSuperadmin) {
    $empleados = $conexion->query('SELECT id, nombre, usuario, rol FROM usuarios ORDER BY nombre')->fetchAll();
}

$titulo = 'Panel del encargado';
$estilos = ['css/admin.css'];
include 'includes/header.php';
?>

    <main id="contenido">
        <h1>Panel del encargado</h1>
        <p>Sesión iniciada como <strong><?= htmlspecialchars($usuarioActual['nombre']) ?></strong> · Rol:
            <?php if ($esSuperadmin): ?>
                <span class="rol rol--super">Superadministrador</span>
            <?php else: ?>
                <span class="rol">Empleado</span>
            <?php endif; ?>
        </p>

        <?php if ($mensaje): ?>
            <p class="mensaje-<?= $mensaje['tipo'] ?>" role="status"><?= htmlspecialchars($mensaje['texto']) ?></p>
        <?php endif; ?>

        <!-- ============ MESAS ============ -->
        <section aria-labelledby="titulo-mesas">
            <h2 id="titulo-mesas">Mesas del salón</h2>

            <ul class="leyenda-estados">
                <?php foreach ($estadosValidos as $clave => $texto): ?>
                    <li><span class="estado estado--<?= $clave ?>"><?= $texto ?></span></li>
                <?php endforeach; ?>
            </ul>

            <div class="grilla-mesas">
                <?php foreach ($mesas as $mesa): ?>
                    <article class="mesa estado--<?= htmlspecialchars($mesa['estado']) ?>">
                        <h3>Mesa <?= $mesa['numero'] ?></h3>
                        <p><?= $mesa['capacidad'] ?> personas · <strong><?= $estadosValidos[$mesa['estado']] ?></strong></p>

                        <form action="admin.php" method="post">
                            <input type="hidden" name="accion" value="cambiar_estado">
                            <input type="hidden" name="mesa_id" value="<?= $mesa['id'] ?>">
                            <label for="estado-<?= $mesa['id'] ?>">Cambiar a</label>
                            <select id="estado-<?= $mesa['id'] ?>" name="estado">
                                <?php foreach ($estadosValidos as $clave => $texto): ?>
                                    <option value="<?= $clave ?>" <?= $mesa['estado'] === $clave ? 'selected' : '' ?>><?= $texto ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit">Guardar</button>
                        </form>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- ============ RESERVAS ============ -->
        <section aria-labelledby="titulo-reservas">
            <h2 id="titulo-reservas">Reservas del <?= date('d/m/Y', strtotime($fecha)) ?></h2>

            <form action="admin.php" method="get">
                <label for="filtro-fecha">Ver otro día</label>
                <input type="date" id="filtro-fecha" name="fecha" value="<?= htmlspecialchars($fecha) ?>">
                <button type="submit">Ver</button>
            </form>

            <?php if (empty($reservas)): ?>
                <p>No hay reservas para este día.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th scope="col">Hora</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Personas</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Email</th>
                            <th scope="col">Comentarios</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservas as $reserva): ?>
                            <tr>
                                <td><?= htmlspecialchars($reserva['hora']) ?></td>
                                <td><?= htmlspecialchars($reserva['nombre']) ?></td>
                                <td><?= htmlspecialchars($reserva['personas']) ?></td>
                                <td><?= htmlspecialchars($reserva['telefono']) ?></td>
                                <td><?= htmlspecialchars($reserva['email']) ?></td>
                                <td><?= htmlspecialchars($reserva['comentarios'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>

        <!-- ============ EMPLEADOS (solo superadministrador) ============ -->
        <?php if ($esSuperadmin): ?>
        <section aria-labelledby="titulo-empleados">
            <h2 id="titulo-empleados">Empleados</h2>
            <p>Solo el superadministrador puede agregar o quitar usuarios del sistema.</p>

            <table>
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empleados as $empleado): ?>
                        <tr>
                            <td><?= htmlspecialchars($empleado['nombre']) ?></td>
                            <td><?= htmlspecialchars($empleado['usuario']) ?></td>
                            <td>
                                <span class="rol <?= $empleado['rol'] === 'superadmin' ? 'rol--super' : '' ?>">
                                    <?= $rolesValidos[$empleado['rol']] ?>
                                </span>
                            </td>
                            <td>
                                <?php if ((int) $empleado['id'] === (int) $usuarioActual['id']): ?>
                                    —
                                <?php else: ?>
                                    <form action="admin.php" method="post" class="form-eliminar">
                                        <input type="hidden" name="accion" value="eliminar_empleado">
                                        <input type="hidden" name="usuario_id" value="<?= $empleado['id'] ?>">
                                        <button type="submit" class="boton-eliminar">Eliminar</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Agregar empleado</h3>
            <form id="form-empleado" class="form-empleado" action="admin.php" method="post">
                <input type="hidden" name="accion" value="agregar_empleado">
                <div class="campo">
                    <label for="emp-nombre">Nombre y apellido</label>
                    <input type="text" id="emp-nombre" name="nombre" required>
                </div>
                <div class="campo">
                    <label for="emp-usuario">Usuario</label>
                    <input type="text" id="emp-usuario" name="usuario" required autocomplete="off">
                </div>
                <div class="campo">
                    <label for="emp-password">Contraseña</label>
                    <input type="password" id="emp-password" name="password" required minlength="6" autocomplete="new-password">
                </div>
                <div class="campo">
                    <label for="emp-rol">Rol</label>
                    <select id="emp-rol" name="rol">
                        <?php foreach ($rolesValidos as $clave => $texto): ?>
                            <option value="<?= $clave ?>"><?= $texto ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="boton">Agregar</button>
            </form>
        </section>
        <?php endif; ?>
    </main>

<?php include 'includes/footer.php'; ?>