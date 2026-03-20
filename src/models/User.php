<?php
require_once __DIR__ . '/../../config/webhooks.php';

class User {
    
    /**
     * Envía los datos a n8n usando cURL de forma segura.
     */
    private function sendToN8n($url, $data) {
        $ch = curl_init($url);
        
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_SSL_VERIFYPEER => false, // Útil si tu local no tiene certificados actualizados
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                // CAMBIO IMPORTANTE: Usamos 'Authorization' para coincidir con el Header Auth de n8n
                'Authorization: ' . N8N_SECRET 
            ],
        ]);

        $response  = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Error de conexión cURL
        if ($curlError) {
            return [
                'httpCode' => 502, 
                'data' => ['status' => 'error', 'message' => 'Error de conexión cURL: ' . $curlError]
            ];
        }

        // Si n8n no responde o el workflow está desactivado
        if ($httpCode === 404 || !$response) {
            return [
                'httpCode' => 404, 
                'data' => ['status' => 'error', 'message' => 'El servidor de n8n no responde. ¿Está el workflow ACTIVO?']
            ];
        }

        $resData = json_decode($response, true);

        // Error en el formato de respuesta (si n8n devuelve texto en lugar de JSON)
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'httpCode' => 502, 
                'data' => ['status' => 'error', 'message' => 'Respuesta inválida del servidor: ' . strip_tags($response)]
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