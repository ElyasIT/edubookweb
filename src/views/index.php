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
    <link rel="stylesheet" href="assets/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    <style>
        .slick-prev:before,
        .slick-next:before {
            color: var(--color-primario);
        }
    </style>
</head>

<body>
    <div class="diseno-panel">
        <?php include 'partials/sidebar.php'; ?>
        <main class="contenido-principal">

            <header class="header-top">
                <div class="saludo">
                    <h2 class="texto-dorado">Bienvenido,
                        <?php echo htmlspecialchars($_SESSION['user']['nombre'] ?? ''); ?>
                    </h2>
                    <p style="color:var(--color-texto-gris)">Descubre eventos universitarios cerca de ti</p>
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
                                    <?php 
                                        $imgSrc = !empty($evt['imagen']) ? htmlspecialchars($evt['imagen']) : 'assets/img/logo.png'; 
                                    ?>
                                    <img src="<?php echo $imgSrc; ?>" 
                                         alt="<?php echo htmlspecialchars($evt['titulo']); ?>"
                                         onerror="this.onerror=null; this.src='assets/img/logo.png';"
                                         style="width:100%; height:100%; object-fit:cover;">
                                </div>
                                <div class="contenido-tarjeta">
                                    <div class="rating"><?php echo date('d/m/Y', strtotime($evt['fecha'])); ?></div>
                                    <h4><?php echo htmlspecialchars($evt['titulo']); ?></h4>
                                    <p class="uni-nombre">
                                        <?php echo htmlspecialchars($evt['universidad'] ?: $evt['creador_nombre']); ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <!-- SLIDERS EVENTOS Y PROMOTORES -->
            <section class="seccion-dashboard seccion-sliders">
                <h3 class="titulo-seccion">Eventos Destacados</h3>
                <div class="slider-concerts slider-wrapper">
                    <div class="slider-item">
                        <div class="tarjeta-evento tarjeta-slider">
                            <div class="imagen-evento img-slider-container">
                                <img src="assets/img/eventos/tedtalk.png" alt="TED Talk" class="img-slider-cover">
                            </div>
                            <div class="contenido-tarjeta">
                                <div class="rating">15/10/2026</div>
                                <h4>TEDx University</h4>
                                <p class="uni-nombre">Auditori Principal</p>
                            </div>
                        </div>
                    </div>
                    <div class="slider-item">
                        <div class="tarjeta-evento tarjeta-slider">
                            <div class="imagen-evento img-slider-container">
                                <img src="assets/img/eventos/taller_de_ciencia.png" alt="Workshop" class="img-slider-cover">
                            </div>
                            <div class="contenido-tarjeta">
                                <div class="rating">22/10/2026</div>
                                <h4>Taller de Liderazgo</h4>
                                <p class="uni-nombre">Sala de Grados</p>
                            </div>
                        </div>
                    </div>
                    <div class="slider-item">
                        <div class="tarjeta-evento tarjeta-slider">
                            <div class="imagen-evento img-slider-container">
                                <img src="assets/img/eventos/hackathon.png" alt="Hackathon" class="img-slider-cover">
                            </div>
                            <div class="contenido-tarjeta">
                                <div class="rating">05/11/2026</div>
                                <h4>Hackathon Campus</h4>
                                <p class="uni-nombre">Laboratorios IT</p>
                            </div>
                        </div>
                    </div>
                    <div class="slider-item">
                        <div class="tarjeta-evento tarjeta-slider">
                            <div class="imagen-evento img-slider-container">
                                <img src="assets/img/eventos/conferencia_ciencia.png" alt="Conferencia" class="img-slider-cover">
                            </div>
                            <div class="contenido-tarjeta">
                                <div class="rating">12/11/2026</div>
                                <h4>Conferencia Ciencia</h4>
                                <p class="uni-nombre">Facultad de Ciencias</p>
                            </div>
                        </div>
                    </div>
                    <div class="slider-item">
                        <div class="tarjeta-evento tarjeta-slider">
                            <div class="imagen-evento img-slider-container">
                                <img src="assets/img/evento1.png" alt="Seminario" class="img-slider-cover">
                            </div>
                            <div class="contenido-tarjeta">
                                <div class="rating">20/11/2026</div>
                                <h4>Seminario de Innovación</h4>
                                <p class="uni-nombre">Centro de Investigación</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="seccion-dashboard">
                <h3 class="titulo-seccion">Organizadores y Promotores</h3>
                <div class="slider-promotors slider-wrapper">
                    <div class="slider-item">
                        <div class="tarjeta-evento tarjeta-promotor">
                            <div class="icono-acceso icono-promotor">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            </div>
                            <h4 style="color:var(--color-acento); margin-bottom: 5px;">Consejo de Estudiantes</h4>
                            <p style="font-size:14px; color:var(--color-texto-gris);">Organizador de debates y charlas.</p>
                        </div>
                    </div>
                    <div class="slider-item">
                        <div class="tarjeta-evento tarjeta-promotor">
                            <div class="icono-acceso icono-promotor">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                            </div>
                            <h4 style="color:var(--color-acento); margin-bottom: 5px;">Departamento IT</h4>
                            <p style="font-size:14px; color:var(--color-texto-gris);">Especialistas en talleres tecnológicos.</p>
                        </div>
                    </div>
                    <div class="slider-item">
                        <div class="tarjeta-evento tarjeta-promotor">
                            <div class="icono-acceso icono-promotor">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            </div>
                            <h4 style="color:var(--color-acento); margin-bottom: 5px;">TEDx Club</h4>
                            <p style="font-size:14px; color:var(--color-texto-gris);">Grupo universitario de conferencias.</p>
                        </div>
                    </div>
                    <div class="slider-item">
                        <div class="tarjeta-evento tarjeta-promotor">
                            <div class="icono-acceso icono-promotor">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                            </div>
                            <h4 style="color:var(--color-acento); margin-bottom: 5px;">Facultad de Negocios</h4>
                            <p style="font-size:14px; color:var(--color-texto-gris);">Seminarios para emprendedores.</p>
                        </div>
                    </div>
                    <div class="slider-item">
                        <div class="tarjeta-evento tarjeta-promotor">
                            <div class="icono-acceso icono-promotor">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                            </div>
                            <h4 style="color:var(--color-acento); margin-bottom: 5px;">Oficina de Alumni</h4>
                            <p style="font-size:14px; color:var(--color-texto-gris);">Networking y charlas motivacionales.</p>
                        </div>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.slider-concerts').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 3,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            arrows: false
                        }
                    }
                ]
            });

            $('.slider-promotors').slick({
                dots: false,
                infinite: true,
                speed: 500,
                slidesToShow: 4,
                slidesToScroll: 2,
                autoplay: true,
                autoplaySpeed: 2000,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            arrows: false
                        }
                    }
                ]
            });
        });
    </script>
</body>

</html>