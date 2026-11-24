<?php
/*
 * VISTA: PANEL DEL ENCARGADO
 * Solo muestra datos; no consulta la base ni procesa formularios.
 * Recibe del controlador:
 *   $usuarioActual, $esSuperadmin, $mensaje, $mesas, $fecha, $reservas, $empleados
 */
/** @var array $usuarioActual */
/** @var bool $esSuperadmin */
/** @var array|null $mensaje */
/** @var array $mesas */
/** @var string $fecha */
/** @var array $reservas */
/** @var array $empleados */
?>
    <main id="contenido">
        <h1>Panel del encargado</h1>
        <p>Sesión iniciada como <strong><?= htmlspecialchars($usuarioActual['nombre']) ?></strong> · Rol:
            <span class="rol <?= $esSuperadmin ? 'rol--super' : '' ?>"><?= ROLES[$usuarioActual['rol']] ?></span>
        </p>

        <?php if ($mensaje): ?>
            <p class="mensaje-<?= $mensaje['tipo'] ?>" role="status"><?= htmlspecialchars($mensaje['texto']) ?></p>
        <?php endif; ?>

        <!-- ============ MESAS ============ -->
        <section aria-labelledby="titulo-mesas">
            <h2 id="titulo-mesas">Mesas del salón</h2>

            <ul class="leyenda-estados">
                <?php foreach (ESTADOS_MESA as $clave => $texto): ?>
                    <li><span class="estado estado--<?= $clave ?>"><?= $texto ?></span></li>
                <?php endforeach; ?>
            </ul>

            <!-- Lo completa js/admin.js contando las mesas de cada estado -->
            <p id="contador-mesas" class="contador-mesas" aria-live="polite"></p>

            <div class="grilla-mesas">
                <?php foreach ($mesas as $mesa): ?>
                    <article class="mesa estado--<?= htmlspecialchars($mesa['estado']) ?>" data-estado="<?= htmlspecialchars($mesa['estado']) ?>">
                        <h3>Mesa <?= htmlspecialchars($mesa['numero']) ?></h3>
                        <p><?= htmlspecialchars($mesa['capacidad']) ?> personas · <strong><?= ESTADOS_MESA[$mesa['estado']] ?></strong></p>

                        <form action="admin.php" method="post">
                            <input type="hidden" name="accion" value="cambiar_estado">
                            <input type="hidden" name="mesa_id" value="<?= htmlspecialchars($mesa['id']) ?>">
                            <label for="estado-<?= htmlspecialchars($mesa['id']) ?>">Cambiar a</label>
                            <select id="estado-<?= htmlspecialchars($mesa['id']) ?>" name="estado">
                                <?php foreach (ESTADOS_MESA as $clave => $texto): ?>
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
                            <th scope="col">N.º</th>
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
                                <td><?= htmlspecialchars($reserva['id']) ?></td>
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
                                    <?= ROLES[$empleado['rol']] ?>
                                </span>
                            </td>
                            <td>
                                <?php if ((int) $empleado['id'] === (int) $usuarioActual['id']): ?>
                                    —
                                <?php else: ?>
                                    <form action="admin.php" method="post" class="form-eliminar"
                                          data-nombre="<?= htmlspecialchars($empleado['nombre']) ?>">
                                        <input type="hidden" name="accion" value="eliminar_empleado">
                                        <input type="hidden" name="usuario_id" value="<?= htmlspecialchars($empleado['id']) ?>">
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
                        <?php foreach (ROLES as $clave => $texto): ?>
                            <option value="<?= $clave ?>"><?= $texto ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="boton">Agregar</button>
            </form>
        </section>
        <?php endif; ?>
    </main>