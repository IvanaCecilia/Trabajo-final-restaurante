<?php
/*
 * LOGOUT: cierra la sesión y vuelve al inicio
 */
session_start();

$_SESSION = [];        // vacía los datos de la sesión
session_destroy();     // y la elimina

header('Location: index.php');
exit;