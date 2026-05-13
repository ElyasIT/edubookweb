<?php
require_once '../controllers/auth_protect.php';
require_once '../models/Event.php';
require_once '../models/Subscription.php';

$eventId = $_GET['id'] ?? '';
if (!$eventId) {
    header('Location: buscar.php');
    exit;
}

$eventModel = new Event();
$evento = $eventModel->getById($eventId);

if (!$evento) {
    header('Location: buscar.php');
    exit;
}

$userEmail = strtolower(trim($_SESSION['user']['email'] ?? ''));
$subModel = new Subscription();
$userEvents = $subModel->getUserEvents($userEmail);
$isSubscribed = false;
foreach ($userEvents as $ue) {
    if ($ue['id'] === $eventId) {
        $isSubscribed = true;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Evento - EduBook</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

    <div class="diseno-panel">

        <?php include 'partials/sidebar.php'; ?>

        <main class="contenido-principal">

            <header class="header-top">
                <div class="saludo">
                    <a href="buscar.php" class="ver-todos" style="font-size: 1rem;">← Volver al buscador</a>
                </div>
                <div class="perfil-usuario">
                    <span
                        class="nombre-corto"><?php echo htmlspecialchars($_SESSION['user']['nombre'] ?? 'Usuario'); ?></span>
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

            <section class="card-info-personal" style="max-width: 900px; margin: 0 auto;">

                <div style="text-align: center; margin-bottom: 30px;">
                    <img src="<?php echo !empty($evento['imagen']) ? htmlspecialchars($evento['imagen']) : 'assets/img/logo.png'; ?>" 
                         alt="<?php echo htmlspecialchars($evento['titulo']); ?>"
                         onerror="this.onerror=null; this.src='assets/img/logo.png';"
                         style="max-width: 100%; height: 250px; object-fit: cover; border-radius: 8px; margin-bottom: 20px;">

                    <h1 style="color: var(--color-texto); font-size: 2rem; margin-bottom: 10px;"><?php echo htmlspecialchars($evento['titulo']); ?></h1>
                    <p style="color: var(--color-acento); font-size: 1.2rem; font-weight: bold;"><?php echo htmlspecialchars($evento['universidad'] ?: $evento['creador_nombre']); ?></p>
                </div>

                <div class="grid-inputs-perfil" style="margin-bottom: 30px;">
                    <div class="grupo-input-perfil">
                        <label style="color: var(--color-texto-gris);">Fecha</label>
                        <p style="color: #fff; font-size: 1.1rem;"><?php echo date('d/m/Y', strtotime($evento['fecha'])); ?></p>
                    </div>
                    <div class="grupo-input-perfil">
                        <label style="color: var(--color-texto-gris);">Categoría</label>
                        <p style="color: #fff; font-size: 1.1rem; text-transform: capitalize;"><?php echo htmlspecialchars($evento['categoria']); ?></p>
                    </div>
                    <div class="grupo-input-perfil">
                        <label style="color: var(--color-texto-gris);">Ubicación</label>
                        <p style="color: #fff; font-size: 1.1rem;"><?php echo htmlspecialchars($evento['lugar'] ?: 'Por definir'); ?></p>
                    </div>
                    <div class="grupo-input-perfil">
                        <label style="color: var(--color-texto-gris);">Modalidad</label>
                        <p style="color: #fff; font-size: 1.1rem; text-transform: capitalize;"><?php echo htmlspecialchars($evento['modalidad']); ?></p>
                    </div>
                </div>

                <div style="margin-bottom: 40px;">
                    <h3
                        style="color: var(--color-texto); margin-bottom: 15px; border-bottom: 1px solid var(--color-borde); padding-bottom: 10px;">
                        Sobre este evento</h3>
                    <p class="texto-descriptivo" style="text-align: justify; max-width: 100%; white-space: pre-wrap;">
                        <?php echo htmlspecialchars($evento['descripcion']); ?>
                    </p>
                </div>

                <?php 
                $rolUsuario = strtolower(trim($_SESSION['user']['rol'] ?? 'explorador'));
                if ($rolUsuario !== 'manager'): 
                ?>
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <button id="btn-subscribe" class="btn-principal" style="max-width: 250px; <?php echo $isSubscribed ? 'background: #e74c3c; color: white;' : ''; ?>" data-subscribed="<?php echo $isSubscribed ? 'true' : 'false'; ?>">
                        <?php echo $isSubscribed ? 'Cancelar Inscripción' : 'Inscribirme ahora'; ?>
                    </button>

                    <button id="btn-favorite"
                        style="background: transparent; border: 1px solid var(--color-acento); color: var(--color-acento); padding: 14px 20px; border-radius: 5px; font-weight: bold; cursor: pointer;">
                        Guardar en favoritos
                    </button>
                </div>
                <?php endif; ?>

            </section>

        </main>
    </div>

    <!-- SweetAlert2 para notificaciones chulas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        <?php if ($rolUsuario !== 'manager'): ?>
        const eventId = '<?php echo $eventId; ?>';
        
        // --- LÓGICA DE SUSCRIPCIONES (n8n) ---
        document.getElementById('btn-subscribe').addEventListener('click', async function() {
            const btn = this;
            const isSubscribed = btn.getAttribute('data-subscribed') === 'true';
            const action = isSubscribed ? 'unsubscribe' : 'subscribe';

            btn.disabled = true;
            btn.style.opacity = '0.7';
            btn.innerText = 'Procesando...';

            try {
                const response = await fetch('../controllers/subscription_ajax.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: action, event_id: eventId })
                });

                const data = await response.json();

                if (data.status === 'ok') {
                    if (action === 'subscribe') {
                        btn.setAttribute('data-subscribed', 'true');
                        btn.innerText = 'Cancelar Inscripción';
                        btn.style.background = '#e74c3c';
                        btn.style.color = 'white';
                        Swal.fire({
                            title: '¡Inscrito!',
                            text: 'Te has apuntado al evento correctamente. Ya puedes verlo en tu calendario.',
                            icon: 'success',
                            background: 'var(--color-panel)',
                            color: 'var(--color-texto)',
                            confirmButtonColor: 'var(--color-primario)'
                        });
                    } else {
                        btn.setAttribute('data-subscribed', 'false');
                        btn.innerText = 'Inscribirme ahora';
                        btn.style.background = 'var(--color-primario)';
                        btn.style.color = '#111';
                        Swal.fire({
                            title: 'Cancelado',
                            text: 'Has cancelado tu inscripción al evento.',
                            icon: 'info',
                            background: 'var(--color-panel)',
                            color: 'var(--color-texto)',
                            confirmButtonColor: 'var(--color-primario)'
                        });
                    }
                } else {
                    Swal.fire({ title: 'Error', text: data.message || 'No se pudo completar la acción.', icon: 'error', background: 'var(--color-panel)', color: 'var(--color-texto)' });
                    btn.innerText = isSubscribed ? 'Cancelar Inscripción' : 'Inscribirme ahora';
                }
            } catch (error) {
                console.error(error);
                Swal.fire({ title: 'Error', text: 'Error de conexión. Asegúrate de tener configurado el flujo EduBook - Suscripciones en n8n.', icon: 'error', background: 'var(--color-panel)', color: 'var(--color-texto)' });
                btn.innerText = isSubscribed ? 'Cancelar Inscripción' : 'Inscribirme ahora';
            } finally {
                btn.disabled = false;
                btn.style.opacity = '1';
            }
        });

        // --- LÓGICA DE FAVORITOS (localStorage) ---
        const btnFav = document.getElementById('btn-favorite');
        let favoritos = JSON.parse(localStorage.getItem('edubook_favoritos')) || [];
        
        if (favoritos.includes(eventId)) {
            btnFav.innerText = 'Quitar de favoritos';
            btnFav.style.background = 'var(--color-acento)';
            btnFav.style.color = '#111';
        }

        btnFav.addEventListener('click', function() {
            favoritos = JSON.parse(localStorage.getItem('edubook_favoritos')) || [];
            if (favoritos.includes(eventId)) {
                favoritos = favoritos.filter(id => id !== eventId);
                btnFav.innerText = 'Guardar en favoritos';
                btnFav.style.background = 'transparent';
                btnFav.style.color = 'var(--color-acento)';
                Swal.fire({ title: 'Eliminado', text: 'Evento quitado de tus favoritos', icon: 'info', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false, background: 'var(--color-panel)', color: 'var(--color-texto)' });
            } else {
                favoritos.push(eventId);
                btnFav.innerText = 'Quitar de favoritos';
                btnFav.style.background = 'var(--color-acento)';
                btnFav.style.color = '#111';
                Swal.fire({ title: '¡Guardado!', text: 'Evento añadido a tus favoritos', icon: 'success', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false, background: 'var(--color-panel)', color: 'var(--color-texto)' });
            }
            localStorage.setItem('edubook_favoritos', JSON.stringify(favoritos));
        });
        <?php endif; ?>
    </script>
</body>

</html>