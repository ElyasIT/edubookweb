<?php
require_once __DIR__ . '/../../config/webhooks.php';

class User {
    
    private function sendToN8n($url, $data) {
        $ch = curl_init($url);
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
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return [
                'httpCode' => 502, 
                'data' => ['status' => 'error', 'message' => 'Fallo cURL: ' . $curlError]
            ];
        }

        if ($httpCode === 404 || !$response) {
            return [
                'httpCode' => 404, 
                'data' => ['status' => 'error', 'message' => 'n8n devolvió 404 o sin respuesta. ¿Está ACTIVADO el workflow en n8n?']
            ];
        }

        $resData = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'httpCode' => 502, 
                'data' => ['status' => 'error', 'message' => 'n8n devolvió un formato inválido: ' . strip_tags($response)]
            ];
        }

        return [
            'httpCode' => $httpCode, 
            'data' => $resData
        ];
    }

    public function registerUser($data) {
        return $this->sendToN8n(N8N_WEBHOOK_REGISTER, $data);
    }

    public function loginUser($data) {
        return $this->sendToN8n(N8N_WEBHOOK_LOGIN, $data);
    }
}
?>