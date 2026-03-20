<?php
require_once 'config/webhooks.php';
require_once 'src/controllers/UserController.php';

$controller = new UserController();
$controller->logout();

// Redirigir al login para que no se quede la pantalla en blanco
header('Location: src/views/login.html');
exit;