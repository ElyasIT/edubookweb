<?php
require_once '../../config/webhooks.php';
require_once '../controllers/UserController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: ' . ALLOWED_ORIGIN);

    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    $isMultipart = strpos($contentType, 'multipart/form-data') !== false;

    if ($isMultipart) {
        // FormData con posible archivo adjunto
        $data = $_POST;
    } else {
        // JSON plano sin archivo
        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true);
    }

    if (!$data) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'No se recibieron datos.']);
        exit;
    }

    $controller = new UserController();
    $result = $controller->register($data);

    if ($result['status'] === 'ok') {
        // Guardar rol por email para que el login lo use siempre
        $email = strtolower(trim($data['email'] ?? ''));
        $rol   = strtolower(trim($data['rol'] ?? 'explorador'));
        if ($email) {
            $rolesFile = __DIR__ . '/../../data/roles.json';
            $rolesData = ['roles' => []];
            if (file_exists($rolesFile)) {
                $rolesData = json_decode(file_get_contents($rolesFile), true) ?: $rolesData;
            }
            $rolesData['roles'][$email] = $rol;
            file_put_contents($rolesFile, json_encode($rolesData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

            // Guardar nombre y universidad para que siempre aparezcan en el perfil
            $controller->saveUserData($email, [
                'nombre'      => trim($data['nombre'] ?? ''),
                'universidad' => trim($data['universidad'] ?? ''),
                'rol'         => $rol,
            ]);
        }

        // Si es manager con foto en el registro, guardarla ya
        if ($rol === 'manager' && $isMultipart && isset($_FILES['foto_perfil_registro'])) {
            $archivo = $_FILES['foto_perfil_registro'];
            if ($archivo['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
                $permitidos = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                if (in_array($ext, $permitidos) && $archivo['size'] <= 5 * 1024 * 1024) {
                    $dirAvatars = __DIR__ . '/assets/img/avatars/';
                    if (!is_dir($dirAvatars)) mkdir($dirAvatars, 0755, true);
                    // Clave basada en md5(email) como ID temporal hasta primer login
                    $userId   = md5($email);
                    $fileName = 'avatar_' . preg_replace('/[^a-z0-9]/', '', $email) . '.' . $ext;
                    if (move_uploaded_file($archivo['tmp_name'], $dirAvatars . $fileName)) {
                        $controller->saveAvatar($userId, 'assets/img/avatars/' . $fileName);
                    }
                }
            }
        }

        http_response_code(201);
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
    <link rel="stylesheet" href="assets/style.css?v=<?php echo time(); ?>">
</head>

<body class="cuerpo-auth">
    <main class="contenedor-auth">

        <!-- PANEL INFO -->
        <section class="panel lado-info">
            <div class="contenido-info">
                <div class="icono-grande">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <h2 class="titulo-bienvenida">Únete a EduBook</h2>
                <div class="bloque-texto">
                    <p><strong>Explorador:</strong> Descubre eventos, guarda favoritos y gestiona tu agenda
                        universitaria.</p>
                </div>
                <div class="bloque-texto">
                    <p><strong>Manager:</strong> Publica eventos, sube material gráfico y gestiona tu catálogo de
                        actividades.</p>
                </div>
                <div class="linea-decorativa"></div>
            </div>
        </section>

        <!-- FORMULARIOS -->
        <section class="panel lado-formulario">
            <div class="caja-logo">
                <img src="assets/img/logo.png" alt="Logotipo EduBook" class="img-logo">
            </div>

            <!-- Tabs -->
            <div class="tabs-registro">
                <button class="tab-btn activo" id="tab-explorador" onclick="cambiarTab('explorador')">
                    Explorador
                </button>
                <button class="tab-btn" id="tab-manager" onclick="cambiarTab('manager')">
                    Manager
                </button>
            </div>

            <!-- EXPLORADOR -->
            <div class="tab-panel activo" id="panel-explorador">
                <span class="badge-rol badge-explorador">Estudiante / Explorador</span>

                <form action="register.php" method="POST" class="formulario-login" id="form-explorador">
                    <input type="hidden" name="rol" value="explorador">

                    <div class="grupo-input">
                        <label for="nombre-exp">Nombre completo *</label>
                        <input type="text" id="nombre-exp" name="nombre" placeholder="Tu nombre y apellidos" required>
                    </div>

                    <div class="grupo-input">
                        <label for="email-exp">Correo electrónico *</label>
                        <input type="email" id="email-exp" name="email" placeholder="ejemplo@universidad.edu" required>
                    </div>

                    <div class="grupo-input">
                        <label for="uni-exp">Universidad / Centro</label>
                        <input type="text" id="uni-exp" name="universidad" placeholder="Ej: Universidad de Barcelona">
                    </div>

                    <div class="grupo-input">
                        <label for="pass-exp">Contraseña (mínimo 8 caracteres) *</label>
                        <input type="password" id="pass-exp" name="password" minlength="8" required>
                    </div>

                    <div class="grupo-input">
                        <label for="pass-confirm-exp">Confirmar contraseña *</label>
                        <input type="password" id="pass-confirm-exp" name="password_confirm" required>
                    </div>

                    <label class="checkbox-terminos">
                        <input type="checkbox" required>
                        <span>Acepto los términos y condiciones</span>
                    </label>

                    <button type="submit" class="btn-principal">Crear cuenta de Explorador</button>
                </form>
            </div>

            <!-- MANAGER -->
            <div class="tab-panel" id="panel-manager">
                <span class="badge-rol badge-manager">Universidad / Manager</span>

                <form action="register.php" method="POST" enctype="multipart/form-data"
                    class="formulario-login" id="form-manager">
                    <input type="hidden" name="rol" value="manager">

                    <div class="grupo-input">
                        <label for="nombre-mgr">Nombre completo *</label>
                        <input type="text" id="nombre-mgr" name="nombre" placeholder="Tu nombre y apellidos" required>
                    </div>

                    <div class="grupo-input">
                        <label for="email-mgr">Correo electrónico *</label>
                        <input type="email" id="email-mgr" name="email" placeholder="contacto@universidad.edu" required>
                    </div>

                    <div class="grupo-input">
                        <label for="uni-mgr">Universidad / Institución *</label>
                        <input type="text" id="uni-mgr" name="universidad" placeholder="Ej: Universidad Europea"
                            required>
                    </div>

                    <div class="grupo-input">
                        <label for="pass-mgr">Contraseña (mínimo 8 caracteres) *</label>
                        <input type="password" id="pass-mgr" name="password" minlength="8" required>
                    </div>

                    <div class="grupo-input">
                        <label for="pass-confirm-mgr">Confirmar contraseña *</label>
                        <input type="password" id="pass-confirm-mgr" name="password_confirm" required>
                    </div>

                    <!-- FOTO PERFIL -->
                    <div class="grupo-input">
                        <label>Foto de perfil (opcional)</label>
                        <div class="zona-foto-reg" onclick="document.getElementById('foto-mgr').click()">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--color-acento)"
                                stroke-width="1.5">
                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" />
                            </svg>
                            <p>Haz click para subir tu foto</p>
                            <span>JPG, PNG o WEBP · Máx 5MB</span>
                            <input type="file" id="foto-mgr" name="foto_perfil_registro" accept="image/*"
                                style="display:none">
                            <img id="preview-manager" src="" alt="Preview">
                        </div>
                    </div>

                    <label class="checkbox-terminos">
                        <input type="checkbox" required>
                        <span>Acepto los términos y condiciones</span>
                    </label>

                    <button type="submit" class="btn-principal">Crear cuenta de Manager</button>
                </form>
            </div>

            <div class="divisor"><span>o</span></div>
            <div class="pie-form">
                <p>¿Ya tienes una cuenta?</p>
                <a href="login.php" class="btn-secundario">Iniciar sesión</a>
            </div>
        </section>
    </main>

    <script src="assets/script.js"></script>
    <script>
        // TAB
        function cambiarTab(tipo) {
            ['explorador', 'manager'].forEach(t => {
                document.getElementById('tab-' + t).classList.toggle('activo', t === tipo);
                document.getElementById('panel-' + t).classList.toggle('activo', t === tipo);
            });
        }

        // PREVIEW FOTO
        document.getElementById('foto-mgr').addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.getElementById('preview-manager');
                img.src = e.target.result;
                img.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
    </script>
</body>

</html>
