<?php
require_once '../controllers/auth_protect.php';
require_once '../models/Event.php';

$rolUsuario = strtolower(trim($_SESSION['user']['rol'] ?? 'explorador'));
$esManager = ($rolUsuario === 'manager');
$eventModel = new Event();
$eventosRecientes = $eventModel->getRecent(3);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - EduBook</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="diseno-panel">
        <?php include 'partials/sidebar.php'; ?>
        <main class="contenido-principal">

            <header class="header-top">
                <div class="saludo">
                    <h2 class="texto-dorado">Bienvenido,
                        <?php echo htmlspecialchars($_SESSION['user']['nombre'] ?? ''); ?> 👋</h2>
                    <p style="color:var(--color-texto-gris)">Descubre eventos universitarios cerca de ti</p>
                </div>
                <div class="perfil-usuario">
                    <span
                        class="nombre-corto"><?php echo htmlspecialchars($_SESSION['user']['nombre'] ?? 'Usuario'); ?></span>
                    <div class="avatar-circulo"
                        style="background: transparent; border: 1px solid var(--color-borde); padding: 0; overflow: hidden;">
                        <img src="assets/img/logo.png" alt="Avatar"
                            style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                </div>
            </header>

            <!-- ACCESOS RAPIDOS -->
            <section class="seccion-dashboard">
                <h3 class="titulo-seccion">Accesos Rápidos</h3>
                <div class="grid-accesos">
                    <a href="buscar.php" class="tarjeta-acceso" style="text-decoration:none;color:inherit;">
                        <div class="icono-acceso">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </div>
                        <h4>Buscar Eventos</h4>
                    </a>
                    <?php if (!$esManager): ?>
                        <a href="calendario.php" class="tarjeta-acceso" style="text-decoration:none;color:inherit;">
                            <div class="icono-acceso">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>
                            <h4>Mi Calendario</h4>
                        </a>
                        <a href="favoritos.php" class="tarjeta-acceso" style="text-decoration:none;color:inherit;">
                            <div class="icono-acceso">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                    </path>
                                </svg>
                            </div>
                            <h4>Mis Favoritos</h4>
                        </a>
                    <?php else: ?>
                        <a href="crear_evento.php" class="tarjeta-acceso" style="text-decoration:none;color:inherit;">
                            <div class="icono-acceso">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                            </div>
                            <h4>Crear Evento</h4>
                        </a>
                        <a href="panel.php" class="tarjeta-acceso" style="text-decoration:none;color:inherit;">
                            <div class="icono-acceso">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="3" y1="9" x2="21" y2="9"></line>
                                    <line x1="9" y1="21" x2="9" y2="9"></line>
                                </svg>
                            </div>
                            <h4>Panel de Gestión</h4>
                        </a>
                    <?php endif; ?>
                    <a href="profile.php" class="tarjeta-acceso" style="text-decoration:none;color:inherit;">
                        <div class="icono-acceso">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <h4>Mi Perfil</h4>
                    </a>
                </div>
            </section>

            <!-- EVENTOS RECIENTES -->
            <section class="seccion-dashboard">
                <div class="cabecera-seccion">
                    <h3 class="titulo-seccion">Eventos Publicados</h3>
                    <a href="buscar.php" class="ver-todos">Ver todos →</a>
                </div>

                <?php if (empty($eventosRecientes)): ?>
                    <div style="text-align:center; padding:50px 20px; color:var(--color-texto-gris);">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            style="color:var(--color-borde);margin-bottom:16px;">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        <p>Aún no hay eventos publicados.</p>
                        <?php if ($esManager): ?>
                            <a href="crear_evento.php" class="btn-principal"
                                style="display:inline-block;margin-top:16px;text-decoration:none;padding:11px 22px;">Sé el
                                primero en publicar</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="grid-tarjetas">
                        <?php foreach ($eventosRecientes as $evt): ?>
                            <a href="evento.php?id=<?php echo urlencode($evt['id']); ?>" class="tarjeta-evento">
                                <div class="imagen-evento">
                                    <?php if ($evt['imagen']): ?>
                                        <img src="<?php echo htmlspecialchars($evt['imagen']); ?>"
                                            alt="<?php echo htmlspecialchars($evt['titulo']); ?>">
                                    <?php else: ?>
                                        <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.5" style="color:var(--color-borde)">
                                            <rect x="3" y="3" width="18" height="18" rx="2" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" />
                                        </svg>
                                    <?php endif; ?>
                                </div>
                                <div class="contenido-tarjeta">
                                    <div class="rating">📅 <?php echo date('d/m/Y', strtotime($evt['fecha'])); ?></div>
                                    <h4><?php echo htmlspecialchars($evt['titulo']); ?></h4>
                                    <p class="uni-nombre">
                                        <?php echo htmlspecialchars($evt['universidad'] ?: $evt['creador_nombre']); ?></p>
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