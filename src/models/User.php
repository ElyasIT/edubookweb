<?php
require_once __DIR__ . '/../../config/webhooks.php';

class User
{

    // ENVIAR N8N 
    private function sendToN8n($url, $data)
    {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_SSL_VERIFYPEER => false, // SSL 
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                // AUTH HEADER 
                'Authorization: ' . N8N_SECRET
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Error de conexión cURL
        if ($curlError) {
            return [
                'httpCode' => 502,
                'data' => ['status' => 'error', 'message' => 'Datos incorrectos o usuario no registrado.']
            ];
        }

        // Si el servicio no responde
        if ($httpCode === 404 || !$response) {
            return [
                'httpCode' => 404,
                'data' => ['status' => 'error', 'message' => 'Datos incorrectos o usuario no registrado.']
            ];
        }

        $resData = json_decode($response, true);

        // Respuesta no es JSON válido
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'httpCode' => 502,
                'data' => ['status' => 'error', 'message' => 'Datos incorrectos o usuario no registrado.']
            ];
        }

        return [
            'httpCode' => $httpCode,
            'data' => $resData
        ];
    }

    public function registerUser($data)
    {
        return $this->sendToN8n(N8N_WEBHOOK_REGISTER, $data);
    }

    public function loginUser($data)
    {
        return $this->sendToN8n(N8N_WEBHOOK_LOGIN, $data);
    }

    public function updateProfile($data)
    {
        return $this->sendToN8n(N8N_WEBHOOK_UPDATE_PROFILE, $data);
    }

    public function updatePassword($data)
    {
        return $this->sendToN8n(N8N_WEBHOOK_UPDATE_PASSWORD, $data);
    }

    public function deleteAccount($data)
    {
        return $this->sendToN8n(N8N_WEBHOOK_DELETE_USER, $data);
    }
}