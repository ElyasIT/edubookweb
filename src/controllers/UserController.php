<?php
// src/controllers/UserController.php
require_once __DIR__ . '/../models/User.php';

class UserController
{
    private $userModel;
    private string $avatarsFile;
    private string $rolesFile;
    private string $usersDataFile;

    public function __construct()
    {
        $this->userModel = new User();
        $this->avatarsFile = __DIR__ . '/../../data/avatars.json';
        $this->rolesFile = __DIR__ . '/../../data/roles.json';
        $this->usersDataFile = __DIR__ . '/../../data/users_data.json';
    }

    // CARGAR AVATAR
    private function loadAvatar(string $userId): string
    {
        if (!file_exists($this->avatarsFile))
            return '';
        $data = json_decode(file_get_contents($this->avatarsFile), true);
        return $data['avatars'][$userId] ?? '';
    }

    // GUARDAR AVATAR
    public function saveAvatar(string $userId, string $path): bool
    {
        $data = ['avatars' => []];
        if (file_exists($this->avatarsFile)) {
            $data = json_decode(file_get_contents($this->avatarsFile), true) ?: $data;
        }
        $data['avatars'][$userId] = $path;
        return (bool) file_put_contents(
            $this->avatarsFile,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        );
    }

    // CARGAR ROL REGISTRADO
    private function loadRol(string $email): string
    {
        if (!file_exists($this->rolesFile))
            return '';
        $data = json_decode(file_get_contents($this->rolesFile), true);
        return $data['roles'][strtolower(trim($email))] ?? '';
    }

    // GUARDAR DATOS DE PERFIL (nombre, universidad, etc.)
    public function saveUserData(string $email, array $fields): bool
    {
        $email = strtolower(trim($email));
        $data = ['users' => []];
        if (file_exists($this->usersDataFile)) {
            $data = json_decode(file_get_contents($this->usersDataFile), true) ?: $data;
        }
        // Fusionar — no sobreescribir campos que ya existan a menos que vengan en $fields
        $existing = $data['users'][$email] ?? [];
        $data['users'][$email] = array_merge($existing, $fields);
        return (bool) file_put_contents(
            $this->usersDataFile,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        );
    }

    // CARGAR DATOS DE PERFIL
    private function loadUserData(string $email): array
    {
        if (!file_exists($this->usersDataFile))
            return [];
        $data = json_decode(file_get_contents($this->usersDataFile), true);
        return $data['users'][strtolower(trim($email))] ?? [];
    }

    // LOGIN
    public function login(string $email, string $password, string $rolFormulario = 'explorador'): array
    {
        // VALIDACION
        if (empty($email) || empty($password)) {
            return ['status' => 'error', 'message' => 'Faltan credenciales.'];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'El formato del email no es válido.'];
        }

        $result = $this->userModel->loginUser(['email' => $email, 'password' => $password]);

        if ($result['httpCode'] === 200 && ($result['data']['status'] ?? '') === 'ok') {
            if (session_status() === PHP_SESSION_NONE)
                session_start();

            $user_data = $result['data']['user'] ?? [];

            // EMAIL SESION
            if (empty($user_data['email'])) {
                $user_data['email'] = $email;
            }

            // ROL: prioridad → n8n/Supabase → roles.json → formulario
            $emailNorm = strtolower(trim($email));
            $rolN8n = strtolower(trim($user_data['rol'] ?? ''));
            $rolGuardado = $this->loadRol($emailNorm);

            if (in_array($rolN8n, ['manager', 'explorador'])) {
                $user_data['rol'] = $rolN8n;
            } elseif (in_array($rolGuardado, ['manager', 'explorador'])) {
                $user_data['rol'] = $rolGuardado;
            } else {
                $user_data['rol'] = in_array($rolFormulario, ['manager', 'explorador']) ? $rolFormulario : 'explorador';
            }

            // DATOS DE PERFIL PERSISTIDOS (nombre, universidad...)
            $datosGuardados = $this->loadUserData($emailNorm);
            if (!empty($datosGuardados['nombre']) && empty($user_data['nombre'])) {
                $user_data['nombre'] = $datosGuardados['nombre'];
            }
            if (!empty($datosGuardados['universidad']) && empty($user_data['universidad'])) {
                $user_data['universidad'] = $datosGuardados['universidad'];
            }

            // AVATAR PERSISTIDO
            $userId = $user_data['id'] ?? md5($emailNorm);
            if (empty($user_data['id']))
                $user_data['id'] = $userId;

            $avatarGuardado = $this->loadAvatar($userId) ?: $this->loadAvatar(md5($emailNorm));
            if ($avatarGuardado) {
                $user_data['avatar'] = $avatarGuardado;
            }

            $_SESSION['user'] = $user_data;
            $_SESSION['access_token'] = $result['data']['access_token'] ?? '';

            return $result['data'];
        }

        return $result['data'] ?? ['status' => 'error', 'message' => 'Datos incorrectos o usuario no registrado.'];
    }

    // REGISTER
    public function register(array $userData): array
    {
        if (empty($userData['email']) || empty($userData['password']) || empty($userData['nombre']) || empty($userData['rol'])) {
            return ['status' => 'error', 'message' => 'Faltan campos obligatorios.'];
        }
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'El formato del correo no es válido.'];
        }
        if (strlen($userData['password']) < 8) {
            return ['status' => 'error', 'message' => 'La contraseña debe tener al menos 8 caracteres.'];
        }

        $result = $this->userModel->registerUser($userData);

        if (isset($result['data']['status']) && $result['data']['status'] === 'ok') {
            return $result['data'];
        }

        return $result['data'] ?? ['status' => 'error', 'message' => 'No se pudo procesar el registro.'];
    }

    // LOGOUT
    public function logout(): array
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        session_unset();
        session_destroy();
        return ['status' => 'ok'];
    }
}