<?php
/*
 * CONTROLADOR: LOGOUT (Emma)
 * Cierra la sesión y vuelve al inicio. No tiene vista.
 */
session_start();

$_SESSION = [];        // vacía los datos de la sesión
session_destroy();     // y la elimina

header('Location: index.php');
exit;