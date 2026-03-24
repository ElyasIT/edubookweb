<?php
require_once '../../config/webhooks.php';
require_once 'UserController.php';

$controller = new UserController();
$controller->logout();

// Redirigir al login para que no se quede la pantalla en blanco
header('Location: ../views/login.php');
exit;
