<?php
require_once '../controllers/auth_protect.php';
require_once '../controllers/rbac.php';
requireRole('manager');
require_once '../models/Event.php';

$userId = $_SESSION['user']['id'] ?? '';
$userName = $_SESSION['user']['nombre'] ?? 'Manager';

$eventModel = new Event();
$misEventos = $eventModel->getByCreator($userId);

$flash = '';
$flashTipo = '';

// ELIMINAR 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['evento_id'] ?? '';
    $res = $eventModel->delete($id, $userId);
    $flash = $res['status'] === 'ok' ? 'Evento eliminado correctamente.' : ($res['message'] ?? 'Error al eliminar.');
    $flashTipo = $res['status'];
    $misEventos = $eventModel->getByCreator($userId); // refrescar
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Gestión - EduBook</title>
    <link rel="stylesheet" href="assets/style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="diseno-panel">
            <?php include 'partials/sidebar.php'; ?>
        <main class="contenido-principal">

            <header class="header-top">
                <div class="saludo">
                    <h2 class="texto-dorado">Panel de Gestión</h2>
                    <p style="color:var(--color-texto-gris)">Gestiona tus eventos publicados</p>
                </div>
                <div class="perfil-usuario">
                    <a href="crear_evento.php" class="btn-principal"
                        style="padding:9px 18px;font-size:0.9rem;text-decoration:none;white-space:nowrap;">
                        + Nuevo Evento
                    </a>
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

                <?php if ($flash): ?>
                <div class="flash-msg <?php echo $flashTipo; ?>">
                            <?php if ($flashTipo === 'ok'): ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                            <?php else: ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                        </svg>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($flash); ?>
                </div>
                <?php endif; ?>

            <div class="card-info-personal" style="padding:0; overflow:hidden;">
                    <?php if (empty($misEventos)): ?>
                    <div class="estado-vacio">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        <p style="margin-bottom:20px;">Todavía no has publicado ningún evento.</p>
                        <a href="crear_evento.php" class="btn-principal"
                            style="display:inline-block;text-decoration:none;padding:12px 24px;max-width:200px;">
                            Crear mi primer evento
                        </a>
                    </div>
                    <?php else: ?>
                    <table class="tabla-eventos">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Evento</th>
                                <th>Fecha</th>
                                <th>Categoría</th>
                                <th>Modalidad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                                        <?php foreach ($misEventos as $evt): ?>
                                <tr>
                                    <td>
                                                        <?php 
                                                            $imgSrc = !empty($evt['imagen']) ? htmlspecialchars($evt['imagen']) : 'assets/img/logo.png'; 
                                                        ?>
                                                        <img class="thumb-evento" 
                                                             src="<?php echo $imgSrc; ?>" 
                                                             alt="Imagen evento"
                                                             onerror="this.onerror=null; this.src='assets/img/logo.png';">
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($evt['titulo']); ?></strong><br>
                                        <small><?php echo htmlspecialchars($evt['lugar'] ?: '—'); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($evt['fecha']))); ?></td>
                                    <td><span
                                            class="badge-cat"><?php echo htmlspecialchars(ucfirst($evt['categoria'])); ?></span>
                                    </td>
                                    <td><span
                                            class="badge-modal"><?php echo htmlspecialchars(ucfirst($evt['modalidad'])); ?></span>
                                    </td>
                                    <td>
                                        <div class="acciones-tabla">
                                            <a href="editar_evento.php?id=<?php echo urlencode($evt['id']); ?>"
                                                class="btn-editar-ev">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                </svg>
                                                Editar
                                            </a>
                                            <button class="btn-eliminar-ev"
                                                onclick="confirmarEliminar('<?php echo htmlspecialchars($evt['id']); ?>', '<?php echo htmlspecialchars(addslashes($evt['titulo'])); ?>')">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6" />
                                                    <path d="M19 6l-1 14H6L5 6" />
                                                    <path d="M10 11v6" />
                                                    <path d="M14 11v6" />
                                                    <path d="M9 6V4h6v2" />
                                                </svg>
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
            </div>
            <?php include 'partials/footer.php'; ?>
        </main>
    </div>

    <!-- MODAL ELIMINAR -->
    <div class="modal-confirmar" id="modal-del">
        <div class="modal-body">
            <h4>¿Eliminar evento?</h4>
            <p id="modal-del-titulo">Esta acción no se puede deshacer.</p>
            <form method="POST" action="panel.php" id="form-del">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="evento_id" id="input-del-id">
                <div class="modal-btns">
                    <button type="button" class="btn-cancelar-modal"
                        style="flex:1; padding:11px; background:transparent; border:1px solid var(--color-borde); color:var(--color-texto); border-radius:6px; cursor:pointer;"
                        onclick="document.getElementById('modal-del').classList.remove('activo')">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-principal" style="flex:1;">Eliminar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmarEliminar(id, titulo) {
            document.getElementById('input-del-id').value = id;
            document.getElementById('modal-del-titulo').textContent = '¿Seguro que quieres eliminar "' + titulo + '"? Esta acción no se puede deshacer.';
            document.getElementById('modal-del').classList.add('activo');
        }
        document.getElementById('modal-del').addEventListener('click', function (e) {
            if (e.target === this) this.classList.remove('activo');
        });
    </script>
</body>

</html>