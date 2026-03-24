<?php
require_once '../../config/webhooks.php';
require_once '../controllers/UserController.php';

// Si es una petición POST, procesamos el login como API
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cabeceras profesionales
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: ' . ALLOWED_ORIGIN);
    header('Access-Control-Allow-Methods: POST');

    // Captura de datos JSON
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);

    if (!$data || !isset($data['email']) || !isset($data['password'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error', 
            'message' => 'Solicitud invalida: faltan credenciales.'
        ]);
        exit;
    }

    $controller = new UserController();

    // Llamamos al metodo login pasando el email y la password
    $result = $controller->login($data['email'], $data['password']);

    // Verificamos el resultado para establecer el codigo de respuesta HTTP
    if (isset($result['status']) && $result['status'] === 'ok') {
        http_response_code(200);
    } else {
        // Si n8n devolvio error o las credenciales fallaron
        http_response_code(401);
    }

    // Enviamos la respuesta final al script.js
    echo json_encode($result);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - EduBook</title>
    <!-- Actualizado el path de assets -->
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="cuerpo-auth">
    <main class="contenedor-auth">
        <section class="panel lado-info">
            <div class="contenido-info">
                <div class="icono-grande">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                </div>
                <h2 class="titulo-bienvenida">Bienvenido</h2>
                <p class="texto-descriptivo">Centraliza y simplifica el acceso a eventos universitarios.</p>
                <div class="linea-decorativa"></div>
            </div>
        </section>

        <section class="panel lado-formulario">
            <div class="caja-logo">
                <img src="assets/img/logo.png" alt="Logotipo EduBook" class="img-logo">
            </div>

            <form action="login.php" method="POST" class="formulario-login">
                <div class="grupo-rol">
                    <label>Tipo de usuario</label>
                    <div class="opciones-rol">
                        <input type="radio" id="rol-explorador-l" name="rol" value="explorador" checked>
                        <label for="rol-explorador-l" class="caja-rol">Explorador</label>

                        <input type="radio" id="rol-manager-l" name="rol" value="manager">
                        <label for="rol-manager-l" class="caja-rol">Manager</label>
                    </div>
                </div>

                <div class="grupo-input">
                    <label for="email-l">Correo electrónico</label>
                    <input type="email" id="email-l" name="email" placeholder="ejemplo@universidad.edu" required>
                </div>

                <div class="grupo-input">
                    <label for="password-l">Contraseña</label>
                    <input type="password" id="password-l" name="password" placeholder="••••••••" required>
                </div>

                <div class="opciones-extra">
                    <label style="display:flex; align-items:center; gap:5px; cursor:pointer;">
                        <input type="checkbox"> Recordarme
                    </label>
                    <a href="#" class="enlace-olvido">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn-principal">Iniciar Sesión</button>
            </form>

            <div class="divisor"><span>o</span></div>
            <div class="pie-form">
                <p>¿No tienes una cuenta?</p>
                <a href="register.php" class="btn-secundario">Crear cuenta</a>
            </div>
        </section>
    </main>

    <script src="assets/script.js"></script>
</body>
</html>
