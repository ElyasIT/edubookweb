<?php
require_once '../controllers/auth_protect.php';
require_once '../controllers/rbac.php';
requireRole('manager');
require_once '../models/Event.php';

$rolUsuario = strtolower(trim($_SESSION['user']['rol'] ?? 'manager'));
$userId = $_SESSION['user']['id'] ?? '';
$userName = $_SESSION['user']['nombre'] ?? 'Manager';

$eventModel = new Event();
$mensaje = '';
$tipoMsg = '';

// ---- Procesar creación de evento ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imagenPath = '';

    // Subida de imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $archivo = $_FILES['imagen'];
        $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $permitidos = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $maxBytes = 5 * 1024 * 1024;

        if (!in_array($ext, $permitidos)) {
            $mensaje = 'Formato de imagen no permitido. Usa JPG, PNG, WEBP o GIF.';
            $tipoMsg = 'error';
        } elseif ($archivo['size'] > $maxBytes) {
            $mensaje = 'La imagen supera el límite de 5MB.';
            $tipoMsg = 'error';
        } else {
            $dir = __DIR__ . '/assets/img/eventos/';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            $nombre = 'evt_' . uniqid() . '.' . $ext;
            move_uploaded_file($archivo['tmp_name'], $dir . $nombre);
            $imagenPath = 'assets/img/eventos/' . $nombre;
        }
    }

    if ($tipoMsg !== 'error') {
        $result = $eventModel->create([
            'titulo' => $_POST['titulo'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'fecha' => $_POST['fecha'] ?? '',
            'lugar' => $_POST['lugar'] ?? '',
            'categoria' => $_POST['categoria'] ?? 'general',
            'modalidad' => $_POST['modalidad'] ?? 'presencial',
            'imagen' => $imagenPath,
            'creador_id' => $userId,
            'creador_nombre' => $userName,
            'universidad' => $_SESSION['user']['universidad'] ?? '',
        ]);

        $mensaje = $result['status'] === 'ok'
            ? 'Evento publicado correctamente. Ya es visible para todos los usuarios.'
            : $result['message'];
        $tipoMsg = $result['status'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Evento - EduBook</title>
    <link rel="stylesheet" href="assets/style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="diseno-panel">
            <?php include 'partials/sidebar.php'; ?>
        <main class="contenido-principal">

            <header class="header-top">
                <div class="saludo">
                    <h2 class="texto-dorado">Crear Evento</h2>
                    <p style="color:var(--color-texto-gris)">Solo visible para Managers</p>
                </div>
                <div class="perfil-usuario">
                    <span class="nombre-corto"><?php echo htmlspecialchars($userName); ?></span>
                    <?php 
                    $navUserName = $_SESSION['user']['nombre'] ?? 'Usuario';
                    $navAvatar = $_SESSION['user']['avatar'] ?? '';
                    ?>
                    <div class="avatar-circulo" <?php echo !$navAvatar ? 'style="background:var(--color-acento);color:#111;font-weight:bold;"' : 'style="background:transparent;border:1px solid var(--color-borde);padding:0;overflow:hidden;"'; ?>>
                        <?php if ($navAvatar): ?>
                            <img src="<?php echo htmlspecialchars($navAvatar); ?>" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
                        <?php else: ?>
                            <?php echo strtoupper(substr($navUserName, 0, 1)); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

                <?php if ($mensaje): ?>
                <div class="alerta-form <?php echo $tipoMsg; ?>">
                            <?php if ($tipoMsg === 'ok'): ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                            <?php else: ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($mensaje); ?>
                </div>
                <?php endif; ?>

            <div class="card-info-personal" style="max-width:860px;">
                <h4 class="titulo-form-perfil"
                    style="margin-bottom:26px;font-size:1rem;text-transform:none;color:var(--color-texto);">
                    Información del Evento
                </h4>
                <form action="crear_evento.php" method="POST" enctype="multipart/form-data" class="form-evento">

                    <div class="grupo-ev">
                        <label for="titulo">Título del evento *</label>
                        <input type="text" id="titulo" name="titulo" placeholder="Ej: Jornada de Puertas Abiertas 2025"
                            required>
                    </div>

                    <div class="grupo-ev">
                        <label for="descripcion">Descripción *</label>
                        <textarea id="descripcion" name="descripcion"
                            placeholder="Describe el evento, ponentes, temario..." required></textarea>
                    </div>

                    <div class="grid-form-2">
                        <div class="grupo-ev">
                            <label for="fecha">Fecha *</label>
                            <input type="date" id="fecha" name="fecha" required>
                        </div>
                        <div class="grupo-ev">
                            <label for="lugar">Lugar / Plataforma</label>
                            <input type="text" id="lugar" name="lugar" placeholder="Ej: Aula Magna, Zoom...">
                        </div>
                        <div class="grupo-ev">
                            <label for="categoria">Categoría</label>
                            <select id="categoria" name="categoria">
                                <option value="general">General</option>
                                <option value="ingenieria">Ingeniería</option>
                                <option value="diseno">Diseño</option>
                                <option value="marketing">Marketing</option>
                                <option value="ciencias">Ciencias</option>
                                <option value="tecnologia">Tecnología</option>
                                <option value="arte">Arte y Cultura</option>
                            </select>
                        </div>
                        <div class="grupo-ev">
                            <label for="modalidad">Modalidad</label>
                            <select id="modalidad" name="modalidad">
                                <option value="presencial">Presencial</option>
                                <option value="online">Online</option>
                                <option value="hibrida">Híbrida</option>
                            </select>
                        </div>
                    </div>

                    <div class="grupo-ev">
                        <label>Imagen del evento (opcional)</label>
                        <div class="zona-imagen" onclick="document.getElementById('imagen').click()">
                            <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5">
                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" />
                            </svg>
                            <p>Haz click para seleccionar imagen</p>
                            <span>JPG, PNG, WEBP o GIF · Máx 5MB</span>
                            <input type="file" id="imagen" name="imagen" accept="image/*" style="display:none">
                            <img id="preview-img" alt="Preview">
                        </div>
                    </div>

                    <div style="display:flex;gap:14px;flex-wrap:wrap;">
                        <button type="submit" class="btn-principal" style="max-width:220px;">Publicar Evento</button>
                        <a href="panel.php" class="btn-secundario"
                            style="max-width:180px;padding:13px;text-align:center;">Ver mis eventos</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <script>
        document.getElementById('imagen').addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.getElementById('preview-img');
                img.src = e.target.result;
                img.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
    </script>
</body>

</html>
