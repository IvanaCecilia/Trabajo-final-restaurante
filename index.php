<?php
/*
 * CONTROLADOR: INICIO (Emma)
 * No necesita datos del modelo: solo arma la pagina con el layout y la vista.
 */
session_start();

$titulo = 'Inicio';

require 'views/layout/header.php';
require 'views/inicio.php';
require 'views/layout/footer.php';