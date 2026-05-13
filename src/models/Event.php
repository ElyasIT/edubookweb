<?php
require_once __DIR__ . '/../../config/webhooks.php';

// MODELO EVENTOS 
class Event
{

    private string $filePath;

    public function __construct()
    {
        $this->filePath = __DIR__ . '/../../data/events.json';
    }

    // LEER DESDE N8N (SUPABASE)
    private function readAll(): array
    {
        $ch = curl_init(N8N_WEBHOOK_GET_EVENTS);
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
            $eventos = $data['eventos'] ?? $data ?? [];
            
            // Si n8n devuelve un solo objeto en vez de un array (ej: {"id":"1"}), lo metemos en un array
            if (is_array($eventos) && isset($eventos['id'])) {
                return [$eventos];
            }
            // Si por algún motivo no es un array, devolvemos array vacío para evitar Fatal Error
            if (!is_array($eventos)) {
                return [];
            }
            return $eventos;
        }
        
        return [];
    }

    // ENVIAR A N8N (POST)
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

    // OBTENER TODOS 
    public function getAll(): array
    {
        $eventos = $this->readAll();
        usort($eventos, function($a, $b) {
            $dateA = $a['created_at'] ?? $a['creado_en'] ?? '';
            $dateB = $b['created_at'] ?? $b['creado_en'] ?? '';
            return strcmp($dateB, $dateA);
        });
        return $eventos;
    }

    // RECIENTES 
    public function getRecent(int $n = 3): array
    {
        return array_slice($this->getAll(), 0, $n);
    }

    // POR CREADOR 
    public function getByCreator(string $userId): array
    {
        return array_values(array_filter($this->readAll(), fn($e) => $e['creador_id'] === $userId));
    }

    // POR ID 
    public function getById(string $id): ?array
    {
        foreach ($this->readAll() as $e) {
            if ($e['id'] === $id)
                return $e;
        }
        return null;
    }

    // CREAR 
    public function create(array $data): array
    {
        $nuevo = [
            'id' => 'evt_' . uniqid(),
            'titulo' => trim($data['titulo'] ?? ''),
            'descripcion' => trim($data['descripcion'] ?? ''),
            'fecha' => trim($data['fecha'] ?? ''),
            'lugar' => trim($data['lugar'] ?? ''),
            'categoria' => trim($data['categoria'] ?? 'general'),
            'modalidad' => trim($data['modalidad'] ?? 'presencial'),
            'imagen' => $data['imagen'] ?? '',
            'creador_id' => $data['creador_id'] ?? '',
            'creador_nombre' => $data['creador_nombre'] ?? '',
            'universidad' => $data['universidad'] ?? '',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if (empty($nuevo['titulo']) || empty($nuevo['fecha']) || empty($nuevo['descripcion'])) {
            return ['status' => 'error', 'message' => 'Título, descripción y fecha son obligatorios.'];
        }

        $res = $this->sendToN8n(N8N_WEBHOOK_CREATE_EVENT, $nuevo);
        if (($res['status'] ?? '') === 'error') {
            return $res;
        }
        return ['status' => 'ok', 'evento' => $nuevo];
    }

    // ACTUALIZAR 
    public function update(string $id, array $data, string $userId): array
    {
        $evt = $this->getById($id);
        if (!$evt) {
            return ['status' => 'error', 'message' => 'Evento no encontrado.'];
        }
        if ($evt['creador_id'] !== $userId) {
            return ['status' => 'error', 'message' => 'No tienes permiso para editar este evento.'];
        }

        $evt['titulo'] = trim($data['titulo'] ?? $evt['titulo']);
        $evt['descripcion'] = trim($data['descripcion'] ?? $evt['descripcion']);
        $evt['fecha'] = trim($data['fecha'] ?? $evt['fecha']);
        $evt['lugar'] = trim($data['lugar'] ?? $evt['lugar']);
        $evt['categoria'] = trim($data['categoria'] ?? $evt['categoria']);
        $evt['modalidad'] = trim($data['modalidad'] ?? $evt['modalidad']);
        if (!empty($data['imagen'])) {
            $evt['imagen'] = $data['imagen'];
        }

        $res = $this->sendToN8n(N8N_WEBHOOK_UPDATE_EVENT, $evt);
        if (($res['status'] ?? '') === 'error') {
            return $res;
        }
        return ['status' => 'ok'];
    }

    // ELIMINAR 
    public function delete(string $id, string $userId): array
    {
        $evt = $this->getById($id);
        if (!$evt) {
            return ['status' => 'error', 'message' => 'Evento no encontrado.'];
        }
        if ($evt['creador_id'] === 'system') {
            return ['status' => 'error', 'message' => 'Los eventos del sistema no se pueden eliminar.'];
        }
        if ($evt['creador_id'] !== $userId) {
            return ['status' => 'error', 'message' => 'No tienes permiso para eliminar este evento.'];
        }

        $res = $this->sendToN8n(N8N_WEBHOOK_DELETE_EVENT, ['id' => $id, 'creador_id' => $userId]);
        if (($res['status'] ?? '') === 'error') {
            return $res;
        }
        return ['status' => 'ok'];
    }
}
