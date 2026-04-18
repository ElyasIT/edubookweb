<?php
require_once '../controllers/auth_protect.php';
require_once '../controllers/UserController.php';

// ROL 
$rolUsuario = strtolower(trim($_SESSION['user']['rol'] ?? 'explorador'));
$esManager = ($rolUsuario === 'manager');

$mensajeFoto = '';
$tipoMensaje = '';
$userId = $_SESSION['user']['id'] ?? md5($_SESSION['user']['email'] ?? 'user');

// SUBIDA FOTO 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $esManager && isset($_FILES['foto_perfil'])) {
    $archivo = $_FILES['foto_perfil'];
    if ($archivo['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $permitidos = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $maxBytes = 5 * 1024 * 1024;

        if (!in_array($ext, $permitidos)) {
            $mensajeFoto = 'Formato no permitido. Usa JPG, PNG, WEBP o GIF.';
            $tipoMensaje = 'error';
        } elseif ($archivo['size'] > $maxBytes) {
            $mensajeFoto = 'La imagen es demasiado grande. Máximo 5MB.';
            $tipoMensaje = 'error';
        } else {
            $dirAvatars = __DIR__ . '/assets/img/avatars/';
            if (!is_dir($dirAvatars))
                mkdir($dirAvatars, 0755, true);

            $nombreArchivo = 'avatar_' . preg_replace('/[^a-z0-9]/', '', strtolower($userId)) . '.' . $ext;
            $rutaDestino = $dirAvatars . $nombreArchivo;

            if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                $rutaWeb = 'assets/img/avatars/' . $nombreArchivo;

                // Guardar en sesión
                $_SESSION['user']['avatar'] = $rutaWeb;

                // Persistir en disco para que sobreviva cierres de sesión
                $ctrl = new UserController();
                $ctrl->saveAvatar($userId, $rutaWeb);

                $mensajeFoto = 'Foto de perfil actualizada correctamente.';
                $tipoMensaje = 'ok';
            } else {
                $mensajeFoto = 'Error al guardar la imagen. Inténtalo de nuevo.';
                $tipoMensaje = 'error';
            }
        }
    } elseif ($archivo['error'] !== UPLOAD_ERR_NO_FILE) {
        $mensajeFoto = 'Error al subir el archivo.';
        $tipoMensaje = 'error';
    }
}

$avatarSrc = $_SESSION['user']['avatar'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - EduBook</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="diseno-panel">
        <?php include 'partials/sidebar.php'; ?>

        <main class="contenido-principal">

            <header class="header-top">
                <div class="saludo">
                    <h3 style="color:var(--color-texto-gris)">Mi Perfil</h3>
                </div>
                <div class="perfil-usuario">
                    <button class="btn-top-explorador">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <?php echo ucfirst($rolUsuario); ?>
                    </button>
                </div>
            </header>

            <?php if ($mensajeFoto): ?>
                <div class="alerta-perfil <?php echo $tipoMensaje; ?>">
                    <?php if ($tipoMensaje === 'ok'): ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    <?php else: ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                    <?php endif; ?>
                    <?php echo htmlspecialchars($mensajeFoto); ?>
                </div>
            <?php endif; ?>

            <div class="layout-perfil">

                <div class="col-izq-perfil">
                    <div class="card-perfil-header">
                        <div class="top-info-user">

                            <div class="avatar-gigante" id="avatar-wrap"
                                style="overflow:hidden; padding:0; background-color:transparent;">
                                <?php if ($avatarSrc): ?>
                                    <img id="avatar-img" src="<?php echo htmlspecialchars($avatarSrc); ?>" alt="Avatar"
                                        style="width:100%;height:100%;object-fit:cover;">
                                <?php else: ?>
                                    <img id="avatar-img" src="assets/img/logo.png" alt="Avatar"
                                        style="width:100%;height:100%;object-fit:contain;">
                                <?php endif; ?>
                            </div>

                            <div class="info-texto-user">
                                <h2><?php echo htmlspecialchars($_SESSION['user']['nombre'] ?? 'Usuario'); ?></h2>
                                <p><?php echo ucfirst($rolUsuario); ?> -
                                    <?php echo htmlspecialchars($_SESSION['user']['universidad'] ?? '-'); ?>
                                </p>
                                <div class="btns-user-actions">
                                    <button class="btn-editar-perfil">Editar perfil</button>
                                    <?php if ($esManager): ?>
                                        <button class="btn-cambiar-foto" id="btn-abrir-modal" type="button">Cambiar
                                            foto</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="grid-stats-perfil">
                            <div class="stat-box">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <span class="num-stat">12</span>
                                <span class="label-stat">Eventos asistidos</span>
                            </div>
                            <div class="stat-box">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <span class="num-stat">5</span>
                                <span class="label-stat">Eventos creados</span>
                            </div>
                            <div class="stat-box">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="12" cy="8" r="7"></circle>
                                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                                </svg>
                                <span class="num-stat">3</span>
                                <span class="label-stat">Certificados</span>
                            </div>
                            <div class="stat-box">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                    </polygon>
                                </svg>
                                <span class="num-stat">8</span>
                                <span class="label-stat">Favoritos</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-info-personal">
                        <h4 class="titulo-form-perfil">información personal</h4>
                        <div class="grid-inputs-perfil">
                            <div class="grupo-input-perfil">
                                <label>Nombre completo</label>
                                <input type="text"
                                    value="<?php echo htmlspecialchars($_SESSION['user']['nombre'] ?? ''); ?>" readonly>
                            </div>
                            <div class="grupo-input-perfil">
                                <label>Correo electrónico</label>
                                <input type="email"
                                    value="<?php echo htmlspecialchars($_SESSION['user']['email'] ?? ''); ?>" readonly>
                            </div>
                            <div class="grupo-input-perfil">
                                <label>Teléfono</label>
                                <input type="text" value="<?php echo htmlspecialchars($_SESSION['user']['telefono'] ?? ''); ?>" placeholder="Sin teléfono registrado" readonly>
                            </div>
                            <div class="grupo-input-perfil">
                                <label>Universidad</label>
                                <input type="text"
                                    value="<?php echo htmlspecialchars($_SESSION['user']['universidad'] ?? ''); ?>"
                                    readonly>
                            </div>
                            <div class="grupo-input-perfil">
                                <label>ID de estudiante</label>
                                <input type="text" value="<?php echo htmlspecialchars($_SESSION['user']['id_estudiante'] ?? $_SESSION['user']['id'] ?? ''); ?>" placeholder="Sin ID" readonly>
                            </div>
                            <div class="grupo-input-perfil">
                                <label>Fecha de nacimiento</label>
                                <input type="text" value="<?php echo htmlspecialchars($_SESSION['user']['fecha_nacimiento'] ?? ''); ?>" placeholder="Sin fecha registrada" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-der-perfil">
                    <div class="card-accesos-rapidos">
                        <h4>Acceso rápido</h4>
                        <div class="lista-botones-acceso">
                            <?php if (!$esManager): ?>
                                <button class="btn-acceso-lat" onclick="location.href='calendario.php'">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    Mi calendario
                                </button>
                                <button class="btn-acceso-lat" onclick="location.href='favoritos.php'">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg>
                                    Mis favoritos
                                </button>
                            <?php else: ?>
                                <button class="btn-acceso-lat" onclick="location.href='crear_evento.php'">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="16"></line>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg>
                                    Crear Evento
                                </button>
                            <?php endif; ?>
                            <button class="btn-acceso-lat" onclick="location.href='buscar.php'">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                Buscar eventos
                            </button>
                        </div>
                    </div>

                    <div class="card-config-cuenta">
                        <h4>Configuración de cuenta</h4>
                        <div class="lista-enlaces-config">
                            <a href="#">Cambiar contraseña</a>
                            <a href="#">Privacidad y seguridad</a>
                            <a href="#">Conectar calendario</a>
                            <a href="../controllers/logout.php" class="enlace-logout">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                Cerrar sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- MODAL FOTO -->
    <?php if ($esManager): ?>
        <div class="modal-overlay" id="modal-foto">
            <div class="modal-foto">
                <h3>📸 Cambiar foto de perfil</h3>
                <p>Sube una imagen JPG, PNG o WEBP. Máx. 5MB.</p>

                <img id="preview-avatar-modal" src="" alt="Preview">

                <form action="profile.php" method="POST" enctype="multipart/form-data" id="form-foto">
                    <div class="zona-drop-foto" onclick="document.getElementById('foto_perfil').click()">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                        <p style="margin:8px 0 4px; color:var(--color-texto); font-size:0.9rem;">Haz click para elegir
                            imagen</p>
                        <span>o arrastra y suelta aquí</span>
                        <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*" style="display:none">
                    </div>

                    <div class="modal-acciones">
                        <button type="button" class="btn-cancelar-modal" id="btn-cancelar-modal">Cancelar</button>
                        <button type="submit" class="btn-principal" style="flex:1;">Guardar foto</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            const modal = document.getElementById('modal-foto');
            const btnAbrir = document.getElementById('btn-abrir-modal');
            const btnCancelar = document.getElementById('btn-cancelar-modal');
            const inputFoto = document.getElementById('foto_perfil');
            const preview = document.getElementById('preview-avatar-modal');

            btnAbrir.addEventListener('click', () => modal.classList.add('activo'));
            btnCancelar.addEventListener('click', () => modal.classList.remove('activo'));
            modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('activo'); });

            inputFoto.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            });
        </script>
    <?php endif; ?>

</body>

</html>