<?php
session_start();
require_once __DIR__ . '/../models/Subscription.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';
$eventId = $data['event_id'] ?? '';
$userEmail = strtolower(trim($_SESSION['user']['email'] ?? ''));

if (!$action || !$eventId || !$userEmail) {
    echo json_encode(['status' => 'error', 'message' => 'Faltan parámetros o no hay email']);
    exit;
}

$subModel = new Subscription();

if ($action === 'subscribe') {
    $result = $subModel->subscribe($userEmail, $eventId);
    echo json_encode($result);
} elseif ($action === 'unsubscribe') {
    $result = $subModel->unsubscribe($userEmail, $eventId);
    echo json_encode($result);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
}
