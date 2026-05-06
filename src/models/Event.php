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
            // Asumimos que n8n devuelve el array directamente o dentro de 'eventos'
            return $data['eventos'] ?? $data ?? [];
        }
        
        return [];
    }

    // ESCRIBIR JSON 
    private function writeAll(array $eventos): bool
    {
        $json = json_encode(['eventos' => array_values($eventos)], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return (bool) file_put_contents($this->filePath, $json, LOCK_EX);
    }

    // OBTENER TODOS 
    public function getAll(): array
    {
        $eventos = $this->readAll();
        usort($eventos, fn($a, $b) => strcmp($b['created_at'], $a['created_at']));
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
        $eventos = $this->readAll();
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

        $eventos[] = $nuevo;
        if ($this->writeAll($eventos)) {
            return ['status' => 'ok', 'evento' => $nuevo];
        }
        return ['status' => 'error', 'message' => 'No se pudo guardar el evento.'];
    }

    // ACTUALIZAR 
    public function update(string $id, array $data, string $userId): array
    {
        $eventos = $this->readAll();
        $encontrado = false;

        foreach ($eventos as &$evt) {
            if ($evt['id'] === $id) {
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
                $encontrado = true;
                break;
            }
        }
        unset($evt);

        if (!$encontrado)
            return ['status' => 'error', 'message' => 'Evento no encontrado.'];
        if ($this->writeAll($eventos))
            return ['status' => 'ok'];
        return ['status' => 'error', 'message' => 'Error al guardar cambios.'];
    }

    // ELIMINAR 
    public function delete(string $id, string $userId): array
    {
        $eventos = $this->readAll();
        $original = count($eventos);

        $filtrados = array_filter($eventos, function ($e) use ($id, $userId) {
            if ($e['id'] !== $id)
                return true; // distinto ID → mantener

            // Eventos del sistema: nunca se pueden borrar
            if ($e['creador_id'] === 'system')
                return true;

            // Solo el creador puede eliminar el suyo
            return $e['creador_id'] !== $userId;
        });

        if (count($filtrados) === $original) {
            $evt = $this->getById($id);
            if ($evt && $evt['creador_id'] === 'system') {
                return ['status' => 'error', 'message' => 'Los eventos del sistema no se pueden eliminar.'];
            }
            if ($evt && $evt['creador_id'] !== $userId) {
                return ['status' => 'error', 'message' => 'No tienes permiso para eliminar este evento.'];
            }
            return ['status' => 'error', 'message' => 'Evento no encontrado.'];
        }

        if ($this->writeAll($filtrados))
            return ['status' => 'ok'];
        return ['status' => 'error', 'message' => 'Error al eliminar el evento.'];
    }
}
