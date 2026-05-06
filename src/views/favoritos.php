<?php
require_once '../controllers/auth_protect.php';
require_once '../controllers/rbac.php';
requireRole('explorador');
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

                <div class="grid-tarjetas">

                    <a href="evento.php" class="tarjeta-evento">
                        <div class="imagen-evento">
                            <img src="assets/img/evento3.png" alt="Evento Elisava" onerror="this.style.display='none'">
                            <div class="etiqueta-favorito">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="contenido-tarjeta">
                            <div class="rating">4.9</div>
                            <h4>Taller de Diseño UX</h4>
                            <p class="uni-nombre">Elisava</p>
                        </div>
                    </a>

                    <a href="evento.php" class="tarjeta-evento">
                        <div class="imagen-evento">
                            <img src="assets/img/evento1.png" alt="Evento UB" onerror="this.style.display='none'">
                            <div class="etiqueta-favorito">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="contenido-tarjeta">
                            <div class="rating">4.8</div>
                            <h4>Jornada de Puertas Abiertas</h4>
                            <p class="uni-nombre">Universidad de Barcelona</p>
                        </div>
                    </a>

                    <a href="evento.php" class="tarjeta-evento">
                        <div class="imagen-evento">
                            <img src="assets/img/evento2.png" alt="Evento UE" onerror="this.style.display='none'">
                            <div class="etiqueta-favorito">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="contenido-tarjeta">
                            <div class="rating">4.5</div>
                            <h4>Feria de Ingeniería</h4>
                            <p class="uni-nombre">Universidad Europea</p>
                        </div>
                    </a>

                </div>
            </section>

        </main>
    </div>

</body>

</html>