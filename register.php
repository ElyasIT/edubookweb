<?php
// Configuración
define('N8N_WEBHOOK_URL', 'https://n8n.kairasystems.com/webhook/edubook/registro');
define('N8N_SECRET',      'EDUBOOK'); 
define('ALLOWED_ORIGIN',  '*'); 

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ' . ALLOWED_ORIGIN);

// Leer datos
$rawInput = file_get_contents('php://input');
$data     = json_decode($rawInput, true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'No llegaron datos al servidor.']);
    exit;
}

// Llamada a n8n
$ch = curl_init(N8N_WEBHOOK_URL);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($data),
    CURLOPT_SSL_VERIFYPEER => false, 
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'X-Internal-Secret: ' . N8N_SECRET 
    ],
]);

$response  = curl_exec($ch);
$httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch); // Cazamos el error de cURL
curl_close($ch);

// Si cURL falla (ej. localhost sin internet o sin módulo curl activado)
if ($curlError) {
    http_response_code(502);
    echo json_encode(['status' => 'error', 'message' => 'Fallo cURL en localhost: ' . $curlError]);
    exit;
}

// Si n8n no devuelve nada
if (!$response) {
    http_response_code(502);
    echo json_encode(['status' => 'error', 'message' => 'n8n no devolvió ninguna respuesta (Código: ' . $httpCode . ')']);
    exit;
}

http_response_code($httpCode);
echo $response;