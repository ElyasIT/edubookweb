<?php
require_once 'config/webhooks.php';
require_once 'src/controllers/UserController.php';

// Cabeceras profesionales
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ' . ALLOWED_ORIGIN);
header('Access-Control-Allow-Methods: POST');

// Captura de datos JSON
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

if (!$data || !isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error', 
        'message' => 'Solicitud invalida: faltan credenciales.'
    ]);
    exit;
}

$controller = new UserController();

// Llamamos al metodo login pasando el email y la password
$result = $controller->login($data['email'], $data['password']);

// Verificamos el resultado para establecer el codigo de respuesta HTTP
if (isset($result['status']) && $result['status'] === 'ok') {
    http_response_code(200);
} else {
    // Si n8n devolvio error o las credenciales fallaron
    http_response_code(401);
}

// Enviamos la respuesta final al script.js
echo json_encode($result);