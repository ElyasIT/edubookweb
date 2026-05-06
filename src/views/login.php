<?php
require_once '../../config/webhooks.php';
require_once '../controllers/UserController.php';

// PROCESAR POST 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CABECERAS 
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: ' . ALLOWED_ORIGIN);
    header('Access-Control-Allow-Methods: POST');

    // CAPTURA JSON 
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

    // ROL FORMULARIO 
    $rolFormulario = strtolower(trim($data['rol'] ?? 'explorador'));
    $result = $controller->login($data['email'], $data['password'], $rolFormulario);

    // VERIFICAR RESULTADO 
    if (isset($result['status']) && $result['status'] === 'ok') {
        http_response_code(200);
    } else {
        // ERROR 
        http_response_code(401);
    }

    // RESPUESTA 
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
    <link rel="stylesheet" href="assets/style.css?v=<?php echo time(); ?>">
</head>

<body class="cuerpo-auth">
    <main class="contenedor-auth">
        <section class="panel lado-info">
            <div class="contenido-info">
                <div class="icono-grande">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3" />
                    </svg>
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

                <div style="display:flex; flex-direction:column; gap:10px;">
                    <button type="submit" id="btn-login" class="btn-principal" style="display:none;">Iniciar
                        Sesión</button>
                    <button type="button" id="btn-show-cookies" class="btn-secundario"
                        style="display:none; width:100%;">Mostrar aviso de cookies</button>
                </div>
            </form>

            <div class="divisor"><span>o</span></div>
            <div class="pie-form">
                <p>¿No tienes una cuenta?</p>
                <a href="register.php" class="btn-secundario">Crear cuenta</a>
            </div>
        </section>
    </main>

    <div id="cookie-notice" style="display:none;">
        <p class="cookie-notice-text">
            Este sitio web utiliza cookies para mejorar su experiencia.
        </p>
        <div class="cookie-notice-btns">
            <button type="button" id="accept-cookies" class="btn-principal">Aceptar</button>
            <button type="button" id="decline-cookies" class="btn-secundario">Rechazar</button>
        </div>
    </div>

    <div id="logo-tooltip" style="display:none;">
        EduBook - Gestión Universitaria
    </div>

    <div id="forgot-password-modal" style="display:none;">
        <div class="modal-content" id="modal-inner">
            <h3>Recuperar contraseña</h3>
            <p>Contacta con el administrador del sistema para restablecer tu contraseña.</p>
            <button type="button" class="btn-principal" id="close-modal-btn">De acuerdo</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/script.js"></script>
    <script>
        $(document).ready(function () {
            // 1. Tooltip que sigue al ratón sobre la imagen del logo
            $('.img-logo').on('mouseenter', function () {
                $('#logo-tooltip').show();
            }).on('mouseleave', function () {
                $('#logo-tooltip').hide();
            }).on('mousemove', function (e) {
                $('#logo-tooltip').css({ top: e.pageY + 10, left: e.pageX + 10 });
            });

            // 2. Modal con fondo transparente
            $('.enlace-olvido').on('click', function (e) {
                e.preventDefault();
                $('#forgot-password-modal').css('display', 'flex');
            });

            // Ocultar modal al hacer clic en el fondo transparente o en el botón cerrar
            $('#forgot-password-modal').on('click', function (e) {
                if (e.target.id === 'forgot-password-modal') {
                    $(this).hide();
                }
            });
            $('#close-modal-btn').on('click', function () {
                $('#forgot-password-modal').hide();
            });

            // 3. Lógica del Aviso de Cookies
            let cookiesAccepted = localStorage.getItem('cookiesAccepted');

            if (cookiesAccepted === 'true') {
                $('#btn-login').show();
                $('#btn-show-cookies').hide();
            } else if (cookiesAccepted === 'false') {
                $('#btn-login').hide();
                $('#btn-show-cookies').show();
            } else {
                $('#cookie-notice').show();
                $('#btn-login').hide();
                $('#btn-show-cookies').hide();
            }

            $('#accept-cookies').on('click', function () {
                localStorage.setItem('cookiesAccepted', 'true');
                $('#cookie-notice').hide();
                $('#btn-show-cookies').hide();
                $('#btn-login').show();
            });

            $('#decline-cookies').on('click', function () {
                localStorage.setItem('cookiesAccepted', 'false');
                $('#cookie-notice').hide();
                $('#btn-login').hide();
                $('#btn-show-cookies').show();
            });

            $('#btn-show-cookies').on('click', function () {
                $('#cookie-notice').show();
            });
        });
    </script>
</body>

</html>