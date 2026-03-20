<?php
require_once 'config/webhooks.php';
require_once 'src/controllers/UserController.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ' . ALLOWED_ORIGIN);

$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'No llegaron datos al servidor.']);
    exit;
}

$controller = new UserController();
$controller->login($data);
?>