<?php
session_start();
define('N8N_WEBHOOK_URL', 'https://n8n.kairasystems.com/webhook/edubook/login');
define('N8N_SECRET',      'EDUBOOK');
define('ALLOWED_ORIGIN',  '*');

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ' . ALLOWED_ORIGIN);

$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'No llegaron datos al servidor.']);
    exit;
}

$ch = curl_init(N8N_WEBHOOK_URL);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($data),
    CURLOPT_SSL_VERIFYPEER => false, // IMPORTANTE para localhost
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'X-Internal-Secret: ' . N8N_SECRET
    ],
]);

$response  = curl_exec($ch);
$httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// Error de red local
if ($curlError) {
    http_response_code(502);
    echo json_encode(['status' => 'error', 'message' => 'Fallo cURL: ' . $curlError]);
    exit;
}

// Si n8n devuelve 404 (Workflow apagado) o no devuelve nada
if ($httpCode === 404 || !$response) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'n8n devolvió 404. ¿Está ACTIVADO el workflow de Login en n8n?']);
    exit;
}

$resData = json_decode($response, true);

// Si falla al decodificar el JSON de n8n
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(502);
    echo json_encode(['status' => 'error', 'message' => 'n8n devolvió un formato inválido: ' . strip_tags($response)]);
    exit;
}

// Si todo va bien, iniciamos la sesión
if ($httpCode === 200 && isset($resData['status']) && $resData['status'] === 'ok') {
    $_SESSION['user_id'] = $resData['user']['id'];
    $_SESSION['nombre']  = $resData['user']['nombre'];
    $_SESSION['rol']     = $resData['user']['rol'];
}

http_response_code($httpCode);
echo $response;
exit;