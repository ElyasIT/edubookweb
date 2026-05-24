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
        $userEmail = strtolower(trim($userEmail));
        
        // Guardar suscripción localmente
        $file = __DIR__ . '/../../data/subscriptions.json';
        $subs = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        if (!isset($subs[$userEmail])) $subs[$userEmail] = [];
        if (!in_array($eventId, $subs[$userEmail])) {
            $subs[$userEmail][] = $eventId;
            file_put_contents($file, json_encode($subs, JSON_PRETTY_PRINT));
        }

        $this->sendToN8n(N8N_WEBHOOK_SUBSCRIBE_EVENT, [
            'user_email' => $userEmail,
            'event_id' => $eventId,
            'action' => 'subscribe'
        ]);

        return ['status' => 'ok'];
    }

    public function unsubscribe(string $userEmail, string $eventId): array
    {
        $userEmail = strtolower(trim($userEmail));

        // Borrar localmente
        $file = __DIR__ . '/../../data/subscriptions.json';
        if (file_exists($file)) {
            $subs = json_decode(file_get_contents($file), true);
            if (isset($subs[$userEmail])) {
                $subs[$userEmail] = array_values(array_filter($subs[$userEmail], fn($id) => $id !== $eventId));
                file_put_contents($file, json_encode($subs, JSON_PRETTY_PRINT));
            }
        }
        
        $this->sendToN8n(N8N_WEBHOOK_UNSUBSCRIBE_EVENT, [
            'user_email' => $userEmail,
            'event_id' => $eventId,
            'action' => 'unsubscribe'
        ]);
        
        return ['status' => 'ok'];
    }

    public function getUserEvents(string $userEmail): array
    {
        $userEmail = strtolower(trim($userEmail));

        // Intentar n8n primero
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
        $n8nSuccess = false;

        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            $inscripciones = $data['eventos'] ?? $data ?? [];
            
            // Si devuelve un solo objeto, forzar array
            if (is_array($inscripciones) && isset($inscripciones['event_id'])) {
                $inscripciones = [$inscripciones];
            }

            if (is_array($inscripciones) && count($inscripciones) > 0) {
                $n8nSuccess = true;
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
        
        // Si falló n8n o devolvió vacío, leemos de local
        if (empty($eventosSuscritos)) {
            $file = __DIR__ . '/../../data/subscriptions.json';
            if (file_exists($file)) {
                $subsLocal = json_decode(file_get_contents($file), true);
                if (isset($subsLocal[$userEmail]) && count($subsLocal[$userEmail]) > 0) {
                    require_once __DIR__ . '/Event.php';
                    $eventModel = new Event();
                    $todosEventos = $eventModel->getAll();

                    foreach ($subsLocal[$userEmail] as $evId) {
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
