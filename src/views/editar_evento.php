<?php
require_once '../controllers/auth_protect.php';
require_once '../controllers/rbac.php';
requireRole('manager');
require_once '../models/Event.php';

$userId = $_SESSION['user']['id'] ?? '';
$userName = $_SESSION['user']['nombre'] ?? 'Manager';

$eventModel = new Event();
$id = $_GET['id'] ?? '';

if (!$id) {
    header('Location: panel.php');
    exit;
}

$evento = $eventModel->getById($id);

// Solo el creador puede editar
if (!$evento || $evento['creador_id'] !== $userId) {
    header('Location: panel.php?error=nopermiso');
    exit;
}

$mensaje = '';
$tipoMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imagenPath = $evento['imagen']; // mantener logo por defecto

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $archivo = $_FILES['imagen'];
        $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $permitidos = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (in_array($ext, $permitidos) && $archivo['size'] <= 5 * 1024 * 1024) {
            $dir = __DIR__ . '/assets/img/eventos/';
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
            $nombre = 'evt_' . uniqid() . '.' . $ext;
            move_uploaded_file($archivo['tmp_name'], $dir . $nombre);
            $imagenPath = 'assets/img/eventos/' . $nombre;
        }
    }

    $res = $eventModel->update($id, [
        'titulo' => $_POST['titulo'] ?? '',
        'descripcion' => $_POST['descripcion'] ?? '',
        'fecha' => $_POST['fecha'] ?? '',
        'lugar' => $_POST['lugar'] ?? '',
        'categoria' => $_POST['categoria'] ?? $evento['categoria'],
        'modalidad' => $_POST['modalidad'] ?? $evento['modalidad'],
        'imagen' => $imagenPath,
    ], $userId);

    if ($res['status'] === 'ok') {
        // Recargar evento actualizado
        $evento = $eventModel->getById($id);
        $mensaje = 'Evento actualizado correctamente.';
        $tipoMsg = 'ok';
    } else {
        $mensaje = $res['message'];
        $tipoMsg = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento - EduBook</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="diseno-panel">
        <?php include 'partials/sidebar.php'; ?>
        <main class="contenido-principal">

            <header class="header-top">
                <div class="saludo">
                    <a href="panel.php" class="ver-todos" style="font-size:0.95rem;">← Volver al panel</a>
                    <h2 class="texto-dorado" style="margin-top:6px;">Editar Evento</h2>
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
                        </svg>
                    <?php endif; ?>
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>

            <div class="card-info-personal" style="max-width:860px;">
                <form action="editar_evento.php?id=<?php echo urlencode($id); ?>" method="POST"
                    enctype="multipart/form-data" class="form-evento">

                    <div class="grupo-ev">
                        <label for="titulo">Título *</label>
                        <input type="text" id="titulo" name="titulo"
                            value="<?php echo htmlspecialchars($evento['titulo']); ?>" required>
                    </div>

                    <div class="grupo-ev">
                        <label for="descripcion">Descripción *</label>
                        <textarea id="descripcion" name="descripcion"
                            required><?php echo htmlspecialchars($evento['descripcion']); ?></textarea>
                    </div>

                    <div class="grid-form-2">
                        <div class="grupo-ev">
                            <label for="fecha">Fecha *</label>
                            <input type="date" id="fecha" name="fecha"
                                value="<?php echo htmlspecialchars($evento['fecha']); ?>" required>
                        </div>
                        <div class="grupo-ev">
                            <label for="lugar">Lugar / Plataforma</label>
                            <input type="text" id="lugar" name="lugar"
                                value="<?php echo htmlspecialchars($evento['lugar']); ?>">
                        </div>
                        <div class="grupo-ev">
                            <label for="categoria">Categoría</label>
                            <select id="categoria" name="categoria">
                                <?php
                                $cats = ['general' => 'General', 'ingenieria' => 'Ingeniería', 'diseno' => 'Diseño', 'marketing' => 'Marketing', 'ciencias' => 'Ciencias', 'tecnologia' => 'Tecnología', 'arte' => 'Arte y Cultura'];
                                foreach ($cats as $val => $label):
                                    $sel = ($evento['categoria'] === $val) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $val; ?>" <?php echo $sel; ?>><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="grupo-ev">
                            <label for="modalidad">Modalidad</label>
                            <select id="modalidad" name="modalidad">
                                <?php
                                $mods = ['presencial' => 'Presencial', 'online' => 'Online', 'hibrida' => 'Híbrida'];
                                foreach ($mods as $val => $label):
                                    $sel = ($evento['modalidad'] === $val) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $val; ?>" <?php echo $sel; ?>><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="grupo-ev">
                        <label>Imagen del evento</label>
                        <?php if ($evento['imagen']): ?>
                            <img src="<?php echo htmlspecialchars($evento['imagen']); ?>" class="img-actual"
                                alt="Imagen actual">
                            <p style="color:var(--color-texto-gris);font-size:0.83rem;margin-bottom:12px;">Imagen actual.
                                Sube una nueva para reemplazarla.</p>
                        <?php endif; ?>
                        <div class="zona-imagen" onclick="document.getElementById('imagen').click()">
                            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="var(--color-acento)"
                                stroke-width="1.5">
                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" />
                            </svg>
                            <p>Haz click para cambiar la imagen</p>
                            <span>JPG, PNG, WEBP · Máx 5MB</span>
                            <input type="file" id="imagen" name="imagen" accept="image/*" style="display:none">
                        </div>
                    </div>

                    <div style="display:flex;gap:14px;flex-wrap:wrap;">
                        <button type="submit" class="btn-principal" style="max-width:200px;">Guardar cambios</button>
                        <a href="panel.php" class="btn-secundario"
                            style="max-width:160px;padding:13px;text-align:center;">Cancelar</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>