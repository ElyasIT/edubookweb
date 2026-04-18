<?php
require_once '../controllers/auth_protect.php';
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
                    <div class="avatar-circulo"
                        style="background: transparent; border: 1px solid var(--color-borde); padding: 0; overflow: hidden;">
                        <img src="assets/img/logo.png" alt="Avatar"
                            style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                </div>
            </header>

            <section class="card-info-personal" style="max-width: 900px; margin: 0 auto;">

                <div style="text-align: center; margin-bottom: 30px;">
                    <img src="assets/img/evento1.png" alt="Imagen Jornada Puertas Abiertas"
                        style="max-width: 100%; height: 250px; object-fit: cover; border-radius: 8px; margin-bottom: 20px;">

                    <h1 style="color: var(--color-texto); font-size: 2rem; margin-bottom: 10px;">Jornada de Puertas
                        Abiertas</h1>
                    <p style="color: var(--color-acento); font-size: 1.2rem; font-weight: bold;">Universidad de
                        Barcelona</p>
                </div>

                <div class="grid-inputs-perfil" style="margin-bottom: 30px;">
                    <div class="grupo-input-perfil">
                        <label style="color: var(--color-texto-gris);">📅 Fecha</label>
                        <p style="color: #fff; font-size: 1.1rem;">20 de Noviembre, 2024</p>
                    </div>
                    <div class="grupo-input-perfil">
                        <label style="color: var(--color-texto-gris);">⏰ Hora</label>
                        <p style="color: #fff; font-size: 1.1rem;">10:00 - 14:00</p>
                    </div>
                    <div class="grupo-input-perfil">
                        <label style="color: var(--color-texto-gris);">📍 Ubicación</label>
                        <p style="color: #fff; font-size: 1.1rem;">Gran Via de les Corts Catalanes, 585</p>
                    </div>
                    <div class="grupo-input-perfil">
                        <label style="color: var(--color-texto-gris);">🏷️ Modalidad</label>
                        <p style="color: #fff; font-size: 1.1rem;">Presencial</p>
                    </div>
                </div>

                <div style="margin-bottom: 40px;">
                    <h3
                        style="color: var(--color-texto); margin-bottom: 15px; border-bottom: 1px solid var(--color-borde); padding-bottom: 10px;">
                        Sobre este evento</h3>
                    <p class="texto-descriptivo" style="text-align: justify; max-width: 100%;">
                        Ven a conocer la Universidad de Barcelona en nuestra jornada anual de puertas abiertas. Podrás
                        visitar las instalaciones, hablar con profesores y alumnos actuales, y recibir orientación sobre
                        los grados de Ingeniería, Ciencias y Humanidades.
                        <br><br>
                        Habrá sesiones informativas específicas para cada facultad y un tour guiado por el campus
                        histórico. ¡No pierdas la oportunidad de resolver todas tus dudas antes de la preinscripción!
                    </p>
                </div>

                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <button class="btn-principal" style="max-width: 200px;">Inscribirme ahora</button>

                    <button
                        style="background: transparent; border: 1px solid var(--color-acento); color: var(--color-acento); padding: 14px 20px; border-radius: 5px; font-weight: bold; cursor: pointer;">
                        Guardar en favoritos
                    </button>
                </div>

            </section>

        </main>
    </div>

</body>

</html>