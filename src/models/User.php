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
                'data' => ['status' => 'error', 'message' => 'Servicio no disponible.']
            ];
        }

        $resData = json_decode($response, true);

        // Respuesta no es JSON válido
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'httpCode' => 502,
                'data' => ['status' => 'error', 'message' => 'Respuesta inválida del servidor.']
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
        // Guardar también en users_data.json por si necesitamos leer localmente
        $email = strtolower(trim($data['user_email'] ?? ''));
        if ($email) {
            $usersDataFile = __DIR__ . '/../../data/users_data.json';
            $usersData = file_exists($usersDataFile) ? json_decode(file_get_contents($usersDataFile), true) : [];
            if (!isset($usersData[$email])) $usersData[$email] = [];
            foreach ($data as $k => $v) {
                if ($k !== 'user_email' && $k !== 'user_id') {
                    $usersData[$email][$k] = $v;
                }
            }
            file_put_contents($usersDataFile, json_encode($usersData, JSON_PRETTY_PRINT));
        }

        return $this->sendToN8n(N8N_WEBHOOK_UPDATE_PROFILE, $data);
    }

    public function updatePassword($data)
    {
        return $this->sendToN8n(N8N_WEBHOOK_UPDATE_PASSWORD, $data);
    }

    public function deleteAccount($data)
    {
        $email = strtolower(trim($data['email']));
        $userId = $data['user_id'] ?? md5($email);
        
        // 1. Eliminar de users_data.json
        $usersDataFile = __DIR__ . '/../../data/users_data.json';
        if (file_exists($usersDataFile)) {
            $usersData = json_decode(file_get_contents($usersDataFile), true);
            if (isset($usersData[$email])) {
                unset($usersData[$email]);
                file_put_contents($usersDataFile, json_encode($usersData, JSON_PRETTY_PRINT));
            }
        }
        
        // 2. Eliminar de roles.json
        $rolesFile = __DIR__ . '/../../data/roles.json';
        if (file_exists($rolesFile)) {
            $roles = json_decode(file_get_contents($rolesFile), true);
            if (isset($roles[$email])) {
                unset($roles[$email]);
                file_put_contents($rolesFile, json_encode($roles, JSON_PRETTY_PRINT));
            }
        }

        // 3. Borrado en cascada de Eventos creados por este usuario
        $eventsFile = __DIR__ . '/../../data/events.json';
        if (file_exists($eventsFile)) {
            $events = json_decode(file_get_contents($eventsFile), true);
            if (is_array($events)) {
                $eventsRestantes = array_filter($events, function($e) use ($userId) {
                    return ($e['creador_id'] ?? '') !== $userId;
                });
                file_put_contents($eventsFile, json_encode(array_values($eventsRestantes), JSON_PRETTY_PRINT));
            }
        }

        // 4. Borrar de suscripciones
        $subsFile = __DIR__ . '/../../data/subscriptions.json';
        if (file_exists($subsFile)) {
            $subs = json_decode(file_get_contents($subsFile), true);
            if (isset($subs[$email])) {
                unset($subs[$email]);
                file_put_contents($subsFile, json_encode($subs, JSON_PRETTY_PRINT));
            }
        }

        // 5. Borrar de Supabase usando n8n
        return $this->sendToN8n(N8N_WEBHOOK_DELETE_USER, $data);
    }
}