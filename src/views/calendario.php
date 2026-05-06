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

                <h4 class="fecha-separador">Hoy, 20 Noviembre</h4>

                <div class="lista-vertical">

                    <div class="tarjeta-agenda-larga">
                        <div class="img-agenda">
                            <img src="assets/img/evento1.png" alt="Evento UB" onerror="this.style.display='none'">
                        </div>
                        <div class="info-agenda">
                            <span class="hora-agenda">10:00 - 14:00</span>
                            <h4>Jornada de Puertas Abiertas</h4>
                            <p>Universidad de Barcelona</p>
                        </div>
                        <div class="acciones-agenda">
                            <a href="evento.php" class="btn-detalles">Ver Detalles</a>
                            <button class="btn-borrar">Borrar de calendario</button>
                        </div>
                    </div>

                    <div class="tarjeta-agenda-larga">
                        <div class="img-agenda">
                            <img src="assets/img/evento2.png" alt="Evento UE" onerror="this.style.display='none'">
                        </div>
                        <div class="info-agenda">
                            <span class="hora-agenda">16:00 - 19:30</span>
                            <h4>Feria de Ingeniería</h4>
                            <p>Universidad Europea</p>
                        </div>
                        <div class="acciones-agenda">
                            <a href="evento.php" class="btn-detalles">Ver Detalles</a>
                            <button class="btn-borrar">Borrar de calendario</button>
                        </div>
                    </div>

                </div>

                <h4 class="fecha-separador">Lunes, 24 Noviembre</h4>

                <div class="lista-vertical">

                    <div class="tarjeta-agenda-larga">
                        <div class="img-agenda">
                            <img src="assets/img/evento3.png" alt="Evento Elisava" onerror="this.style.display='none'">
                        </div>
                        <div class="info-agenda">
                            <span class="hora-agenda">09:00 - 13:00</span>
                            <h4>Taller de Diseño UX</h4>
                            <p>Elisava</p>
                        </div>
                        <div class="acciones-agenda">
                            <a href="evento.php" class="btn-detalles">Ver Detalles</a>
                            <button class="btn-borrar">Borrar de calendario</button>
                        </div>
                    </div>
                </div>

            </section>

        </main>
    </div>

</body>

</html>