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

    public function subscribe(string $userEmail, string $eventId): array
    {
        return $this->sendToN8n(N8N_WEBHOOK_SUBSCRIBE_EVENT, [
            'user_email' => $userEmail,
            'event_id' => $eventId,
            'action' => 'subscribe'
        ]);
    }

    public function unsubscribe(string $userEmail, string $eventId): array
    {
        return $this->sendToN8n(N8N_WEBHOOK_UNSUBSCRIBE_EVENT, [
            'user_email' => $userEmail,
            'event_id' => $eventId,
            'action' => 'unsubscribe'
        ]);
    }

    public function getUserEvents(string $userEmail): array
    {
        $ch = curl_init(N8N_WEBHOOK_GET_USER_EVENTS . '?user_email=' . urlencode($userEmail));
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

        $eventosSuscritos = [];
        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            $inscripciones = $data['eventos'] ?? $data ?? [];
            
            // Si devuelve un solo objeto, forzar array
            if (is_array($inscripciones) && isset($inscripciones['event_id'])) {
                $inscripciones = [$inscripciones];
            }

            if (is_array($inscripciones) && count($inscripciones) > 0) {
                require_once __DIR__ . '/Event.php';
                $eventModel = new Event();
                $todosEventos = $eventModel->getAll();

                foreach ($inscripciones as $inscripcion) {
                    $evId = $inscripcion['event_id'] ?? null;
                    if ($evId) {
                        foreach ($todosEventos as $evt) {
                            if ($evt['id'] === $evId) {
                                $eventosSuscritos[] = $evt;
                                break;
                            }
                        }
                    }
                }
            }
        }
        
        return $eventosSuscritos;
    }
}
