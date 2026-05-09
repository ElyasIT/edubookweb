<?php
require_once __DIR__ . '/../../config/webhooks.php';

class Subscription
{
    private function sendToN8n(string $url, array $data): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . N8N_SECRET,
                'Content-Type: application/json'
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            $resData = json_decode($response, true);
            return $resData ?? ['status' => 'ok'];
        }

        return ['status' => 'error', 'message' => 'Error en la comunicación con n8n. HTTP Code: ' . $httpCode];
    }

    public function subscribe(string $userId, string $eventId): array
    {
        return $this->sendToN8n(N8N_WEBHOOK_SUBSCRIBE_EVENT, [
            'user_id' => $userId,
            'event_id' => $eventId,
            'action' => 'subscribe'
        ]);
    }

    public function unsubscribe(string $userId, string $eventId): array
    {
        return $this->sendToN8n(N8N_WEBHOOK_UNSUBSCRIBE_EVENT, [
            'user_id' => $userId,
            'event_id' => $eventId,
            'action' => 'unsubscribe'
        ]);
    }

    public function getUserEvents(string $userId): array
    {
        $ch = curl_init(N8N_WEBHOOK_GET_USER_EVENTS . '?user_id=' . urlencode($userId));
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . N8N_SECRET,
                'Content-Type: application/json'
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            return $data['eventos'] ?? $data ?? [];
        }
        
        return [];
    }
}
