<?php
require_once 'config/webhooks.php';
require_once 'src/controllers/UserController.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ' . ALLOWED_ORIGIN);

$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'No se recibieron datos.']);
    exit;
}

$controller = new UserController();
$result = $controller->register($data); // Enviamos todo el array con nombre, email, uni, etc.

if (isset($result['status']) && $result['status'] === 'ok') {
    http_response_code(201); // 201 Created es mas profesional para registros
} else {
    http_response_code(400);
}

echo json_encode($result);