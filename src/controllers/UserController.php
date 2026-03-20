<?php
require_once __DIR__ . '/../models/User.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login($data) {
        $result = $this->userModel->loginUser($data);
        $httpCode = $result['httpCode'];
        $resData = $result['data'];

        if ($httpCode === 200 && isset($resData['status']) && $resData['status'] === 'ok') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $resData['user']['id'];
            $_SESSION['nombre']  = $resData['user']['nombre'];
            $_SESSION['rol']     = $resData['user']['rol'];
        }

        http_response_code($httpCode);
        echo json_encode($resData);
        exit;
    }

    public function register($data) {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'El formato del correo es inválido.']);
            exit;
        }

        if (strlen($data['password']) < 8) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'La contraseña debe tener al menos 8 caracteres.']);
            exit;
        }

        $result = $this->userModel->registerUser($data);
        
        http_response_code($result['httpCode']);
        echo json_encode($result['data']);
        exit;
    }
    
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header("Location: /src/views/login.html");
        exit;
    }
}
?>