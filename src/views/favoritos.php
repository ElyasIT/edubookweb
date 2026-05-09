<?php
require_once '../controllers/auth_protect.php';
require_once '../controllers/rbac.php';
require_once '../models/Subscription.php';
requireRole('explorador');

$userId = $_SESSION['user']['id'];
$subModel = new Subscription();
$userEvents = $subModel->getUserEvents($userId);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Favoritos - EduBook</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

    <div class="diseno-panel">

        <?php include 'partials/sidebar.php'; ?>

        <main class="contenido-principal">

            <header class="header-top">
                <div class="saludo">
                    <h2 class="texto-dorado">Mis Favoritos</h2>
                    <p style="color:var(--color-texto-gris)">Tus eventos guardados</p>
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
                <div class="barra-filtros-fav">
                    <div class="grupo-izq">
                        <button class="btn-filtro-fav activo">Todos</button>
                        <button class="btn-filtro-fav">Próximamente</button>
                        <button class="btn-filtro-fav">Disponibles</button>
                    </div>

                    <div class="grupo-der">
                        <button class="btn-ordenar">
                            Ordenar por fecha
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                    </div>
                </div>

                <?php if (empty($userEvents)): ?>
                    <div style="text-align:center; padding:50px 20px; color:var(--color-texto-gris);">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            style="color:var(--color-borde);margin-bottom:16px;">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        <p>No tienes eventos guardados en favoritos.</p>
                    </div>
                <?php else: ?>
                    <div class="grid-tarjetas">
                        <?php foreach ($userEvents as $evt): ?>
                            <a href="evento.php?id=<?php echo urlencode($evt['id']); ?>" class="tarjeta-evento">
                                <div class="imagen-evento">
                                    <?php if ($evt['imagen']): ?>
                                        <img src="<?php echo htmlspecialchars($evt['imagen']); ?>" alt="<?php echo htmlspecialchars($evt['titulo']); ?>">
                                    <?php else: ?>
                                        <div style="width:100%; height:100%; background:var(--color-fondo-panel); display:flex; align-items:center; justify-content:center;">
                                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:var(--color-borde)"><rect x="3" y="3" width="18" height="18" rx="2" /><circle cx="8.5" cy="8.5" r="1.5" /><polyline points="21 15 16 10 5 21" /></svg>
                                        </div>
                                    <?php endif; ?>
                                    <div class="etiqueta-favorito">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                            <path
                                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="contenido-tarjeta">
                                    <div class="rating"><?php echo date('d/m/Y', strtotime($evt['fecha'])); ?></div>
                                    <h4><?php echo htmlspecialchars($evt['titulo']); ?></h4>
                                    <p class="uni-nombre"><?php echo htmlspecialchars($evt['universidad'] ?: $evt['creador_nombre']); ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

        </main>
    </div>

</body>

</html>