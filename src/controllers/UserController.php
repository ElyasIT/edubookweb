<?php
// src/controllers/UserController.php

class UserController {
    
    public function __construct() {
        // Cargamos las constantes definidas en el config
        require_once __DIR__ . '/../../config/webhooks.php';
    }

    private function callWebhook($url, $data) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            // Enviamos el secreto "EDUBOOK" que espera n8n
            'Authorization: ' . N8N_SECRET 
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'code' => $httpCode,
            'data' => json_decode($response, true)
        ];
    }

    public function login($email, $password) {
        $payload = ['email' => $email, 'password' => $password];
        $result = $this->callWebhook(N8N_WEBHOOK_LOGIN, $payload);

        // Si n8n responde 200 y el status es ok
        if ($result['code'] === 200 && isset($result['data']['status']) && $result['data']['status'] === 'ok') {
            if (session_status() === PHP_SESSION_NONE) session_start();
            
            // Guardamos la sesion con los datos que devuelve tu n8n (nodo Obtener Perfil)
            $_SESSION['user'] = $result['data']['user'];
            $_SESSION['access_token'] = $result['data']['access_token'];
            
            return $result['data'];
        }
        
        return $result['data'] ?? ['status' => 'error', 'message' => 'Credenciales incorrectas o error de servidor'];
    }

    public function register($userData) {
        $result = $this->callWebhook(N8N_WEBHOOK_REGISTER, $userData);
        return $result['data'] ?? ['status' => 'error', 'message' => 'No se pudo procesar el registro'];
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        return ['status' => 'ok'];
    }
}