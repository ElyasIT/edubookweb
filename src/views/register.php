<?php
require_once '../../config/webhooks.php';
require_once '../controllers/UserController.php';

// Si es una petición POST, procesamos el registro como API
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: ' . ALLOWED_ORIGIN);

    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'No se recibieron datos.']);
        exit;
    }

    $controller = new UserController();
    $result = $controller->register($data); // Enviamos todo el array con nombre, email, uni, etc.

    if (isset($result['status']) && $result['status'] === 'ok') {
        http_response_code(201); // 201 Created es mas profesional para registros
    } else {
        http_response_code(400);
    }

    echo json_encode($result);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - EduBook</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="cuerpo-auth">
    <main class="contenedor-auth">
        <section class="panel lado-formulario">
            <div class="caja-logo">
                <img src="assets/img/logo.png" alt="Logotipo EduBook" class="img-logo">
            </div>

            <form action="register.php" method="POST" class="formulario-login">
                <div class="grupo-rol">
                    <label>Tipo de usuario</label>
                    <div class="opciones-rol">
                        <input type="radio" id="rol-explorador" name="rol" value="explorador" checked>
                        <label for="rol-explorador" class="caja-rol">Explorador</label>

                        <input type="radio" id="rol-manager" name="rol" value="manager">
                        <label for="rol-manager" class="caja-rol">Manager</label>
                    </div>
                </div>

                <div class="grupo-input">
                    <label for="name">Nombre completo</label>
                    <input type="text" id="name" name="nombre" required>
                </div>

                <div class="grupo-input">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="grupo-input">
                    <label for="uni">Tu universidad / Centro</label>
                    <input type="text" id="uni" name="universidad">
                </div>

                <div class="grupo-input">
                    <label for="pass">Contraseña (Mínimo 8 caracteres)</label>
                    <input type="password" id="pass" name="password" minlength="8" required>
                </div>

                <div class="grupo-input">
                    <label for="pass-confirm">Confirma tu contraseña</label>
                    <input type="password" id="pass-confirm" name="pass-confirm" required>
                </div>

                <label class="checkbox-terminos">
                    <input type="checkbox" required> 
                    <span>Acepto los términos y condiciones</span>
                </label>

                <button type="submit" class="btn-principal">Crear cuenta</button>
            </form>

            <div class="divisor"><span>o</span></div>
            <div class="pie-form">
                <p>¿Ya tienes una cuenta?</p>
                <a href="login.php" class="btn-secundario">Iniciar sesión</a>
            </div>
        </section>

        <section class="panel lado-info">
            <div class="contenido-info">
                <div class="icono-grande">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                </div>
                <h2 class="titulo-bienvenida">Únete a la comunidad</h2>
                <div class="bloque-texto">
                    <p><strong>Estudiantes:</strong> Explora eventos y talleres.</p>
                </div>
                <div class="bloque-texto">
                    <p><strong>Universidades:</strong> Gestiona y promociona tus charlas.</p>
                </div>
            </div>
        </section>
    </main>

    <script src="assets/script.js"></script>
</body>
</html>
