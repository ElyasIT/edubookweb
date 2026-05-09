<?php
require_once '../controllers/auth_protect.php';
require_once '../controllers/rbac.php';
require_once '../models/Subscription.php';
requireRole('explorador');

$userId = $_SESSION['user']['id'];
$subModel = new Subscription();
$userEvents = $subModel->getUserEvents($userId);

// Ordenar por fecha (próximos primero)
usort($userEvents, function($a, $b) {
    return strtotime($a['fecha']) - strtotime($b['fecha']);
});
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario - EduBook</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

    <div class="diseno-panel">

        <?php include 'partials/sidebar.php'; ?>

        <main class="contenido-principal">

            <header class="header-top">
                <div class="saludo">
                    <h2 class="texto-dorado">Mi Agenda</h2>
                    <p style="color:var(--color-texto-gris)">Próximos eventos y entregas</p>
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

            <section class="seccion-dashboard">

                <?php if (empty($userEvents)): ?>
                    <div style="text-align:center; padding:50px 20px; color:var(--color-texto-gris);">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            style="color:var(--color-borde);margin-bottom:16px;">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        <p>No estás inscrito a ningún evento.</p>
                        <a href="buscar.php" class="btn-principal"
                            style="display:inline-block;margin-top:16px;text-decoration:none;padding:11px 22px;">Buscar eventos</a>
                    </div>
                <?php else: ?>
                    <div class="lista-vertical">
                        <?php foreach ($userEvents as $evt): ?>
                            <div class="tarjeta-agenda-larga">
                                <div class="img-agenda">
                                    <?php if ($evt['imagen']): ?>
                                        <img src="<?php echo htmlspecialchars($evt['imagen']); ?>" alt="<?php echo htmlspecialchars($evt['titulo']); ?>">
                                    <?php else: ?>
                                        <div style="width:100%; height:100%; background:var(--color-fondo-panel); display:flex; align-items:center; justify-content:center;">
                                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:var(--color-borde)"><rect x="3" y="3" width="18" height="18" rx="2" /><circle cx="8.5" cy="8.5" r="1.5" /><polyline points="21 15 16 10 5 21" /></svg>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="info-agenda">
                                    <span class="hora-agenda"><?php echo date('d/m/Y', strtotime($evt['fecha'])); ?></span>
                                    <h4><?php echo htmlspecialchars($evt['titulo']); ?></h4>
                                    <p><?php echo htmlspecialchars($evt['universidad'] ?: $evt['creador_nombre']); ?></p>
                                </div>
                                <div class="acciones-agenda">
                                    <a href="evento.php?id=<?php echo urlencode($evt['id']); ?>" class="btn-detalles">Ver Detalles</a>
                                    <button class="btn-borrar" onclick="cancelarSuscripcion('<?php echo htmlspecialchars($evt['id']); ?>')">Borrar de calendario</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </section>

        </main>
    </div>

    <script>
        async function cancelarSuscripcion(eventId) {
            if (!confirm('¿Estás seguro de que quieres cancelar tu inscripción a este evento?')) return;

            try {
                const response = await fetch('../controllers/subscription_ajax.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'unsubscribe', event_id: eventId })
                });
                const data = await response.json();
                
                if (data.status === 'ok') {
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'No se pudo cancelar.'));
                }
            } catch (err) {
                console.error(err);
                alert('Error de conexión.');
            }
        }
    </script>

</body>

</html>