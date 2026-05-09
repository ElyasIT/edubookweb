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

$userId = $_SESSION['user']['id'] ?? '';
$subModel = new Subscription();
$userEvents = $subModel->getUserEvents($userId);
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
                    <?php if ($evento['imagen']): ?>
                        <img src="<?php echo htmlspecialchars($evento['imagen']); ?>" alt="<?php echo htmlspecialchars($evento['titulo']); ?>"
                            style="max-width: 100%; height: 250px; object-fit: cover; border-radius: 8px; margin-bottom: 20px;">
                    <?php endif; ?>

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

                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <button id="btn-subscribe" class="btn-principal" style="max-width: 250px; <?php echo $isSubscribed ? 'background: #e74c3c; color: white;' : ''; ?>" data-subscribed="<?php echo $isSubscribed ? 'true' : 'false'; ?>">
                        <?php echo $isSubscribed ? 'Cancelar Inscripción' : 'Inscribirme ahora'; ?>
                    </button>

                    <button
                        style="background: transparent; border: 1px solid var(--color-acento); color: var(--color-acento); padding: 14px 20px; border-radius: 5px; font-weight: bold; cursor: pointer;">
                        Guardar en favoritos
                    </button>
                </div>

            </section>

        </main>
    </div>

    <script>
        document.getElementById('btn-subscribe').addEventListener('click', async function() {
            const btn = this;
            const isSubscribed = btn.getAttribute('data-subscribed') === 'true';
            const action = isSubscribed ? 'unsubscribe' : 'subscribe';
            const eventId = '<?php echo $eventId; ?>';

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
                        alert('¡Inscrito correctamente! Podrás verlo en tu calendario.');
                    } else {
                        btn.setAttribute('data-subscribed', 'false');
                        btn.innerText = 'Inscribirme ahora';
                        btn.style.background = 'var(--color-primario)';
                        btn.style.color = '#111';
                        alert('Inscripción cancelada.');
                    }
                } else {
                    alert('Error: ' + (data.message || 'No se pudo completar la acción.'));
                    btn.innerText = isSubscribed ? 'Cancelar Inscripción' : 'Inscribirme ahora';
                }
            } catch (error) {
                console.error(error);
                alert('Error de conexión.');
                btn.innerText = isSubscribed ? 'Cancelar Inscripción' : 'Inscribirme ahora';
            } finally {
                btn.disabled = false;
                btn.style.opacity = '1';
            }
        });
    </script>
</body>

</html>