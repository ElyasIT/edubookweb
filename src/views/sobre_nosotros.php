<?php
require_once '../controllers/auth_protect.php';
$rolUsuario = strtolower(trim($_SESSION['user']['rol'] ?? 'explorador'));
$esManager  = ($rolUsuario === 'manager');
$navUserName = $_SESSION['user']['nombre'] ?? 'Usuario';
$navAvatar   = $_SESSION['user']['avatar'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros – EduBook</title>
    <meta name="description" content="Conoce el equipo detrás de EduBook, la plataforma que centraliza eventos universitarios en Barcelona. Nuestra misión, visión y valores.">
    <link rel="stylesheet" href="assets/style.css?v=<?php echo time(); ?>">
</head>
<body>
<div class="diseno-panel">
    <?php include 'partials/sidebar.php'; ?>
    <div class="contenido-principal sin-padding">

        <!-- HEADER TOP -->
        <header class="header-top" style="padding:30px 40px 20px; margin:0;">
            <div class="saludo">
                <h2 class="texto-dorado">Sobre Nosotros</h2>
                <p style="color:var(--color-texto-gris)">El equipo que da vida a EduBook</p>
            </div>
            <div class="perfil-usuario">
                <span class="nombre-corto"><?php echo htmlspecialchars($navUserName); ?></span>
                <div class="avatar-circulo" <?php echo !$navAvatar ? 'style="background:var(--color-acento);color:#111;font-weight:bold;"' : 'style="background:transparent;border:1px solid var(--color-borde);padding:0;overflow:hidden;"'; ?>>
                    <?php if ($navAvatar): ?>
                        <img src="<?php echo htmlspecialchars($navAvatar); ?>" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
                    <?php else: ?>
                        <?php echo strtoupper(substr($navUserName, 0, 1)); ?>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <!-- HERO -->
        <section class="sn-hero">
            <div class="sn-hero-content">
                <span class="sn-badge">Proyecto Académico · Stucom Pelai · Barcelona</span>
                <h1 class="sn-hero-title">Conectamos el mundo universitario</h1>
                <p class="sn-hero-sub">EduBook nació de una idea simple pero poderosa: que ningún estudiante se pierda un evento por falta de información. Somos el <strong>marketplace de eventos universitarios</strong> que centraliza toda la oferta académica de Barcelona en un solo lugar.</p>
                <div class="sn-hero-stats">
                    <div class="sn-stat">
                        <span class="sn-stat-num">100+</span>
                        <span class="sn-stat-label">Eventos publicados</span>
                    </div>
                    <div class="sn-stat">
                        <span class="sn-stat-num">500+</span>
                        <span class="sn-stat-label">Usuarios registrados</span>
                    </div>
                    <div class="sn-stat">
                        <span class="sn-stat-num">15+</span>
                        <span class="sn-stat-label">Centros educativos</span>
                    </div>
                    <div class="sn-stat">
                        <span class="sn-stat-num">2</span>
                        <span class="sn-stat-label">Desarrolladores</span>
                    </div>
                </div>
            </div>
        </section>

        <div class="sn-body">

            <!-- MISIÓN, VISIÓN, VALORES -->
            <section class="sn-section">
                <div class="sn-mvv-grid">
                    <div class="sn-mvv-card">
                        <div class="sn-mvv-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </div>
                        <h3>Misión</h3>
                        <p>Centralizar y facilitar el acceso a todos los eventos universitarios de Barcelona, eliminando la fragmentación actual de la información. Queremos que cada estudiante, profesional o institución pueda encontrar, gestionar y participar en eventos académicos con total facilidad, desde una sola plataforma.</p>
                    </div>
                    <div class="sn-mvv-card sn-mvv-featured">
                        <div class="sn-mvv-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </div>
                        <h3>Visión</h3>
                        <p>Convertirnos en la plataforma de referencia de eventos universitarios en España, expandiéndonos progresivamente a toda la comunidad iberoamericana. Aspiramos a ser el nexo digital entre instituciones educativas, estudiantes y profesionales que buscan oportunidades de aprendizaje y networking.</p>
                    </div>
                    <div class="sn-mvv-card">
                        <div class="sn-mvv-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        </div>
                        <h3>Valores</h3>
                        <ul class="sn-valores-list">
                            <li><strong>Accesibilidad:</strong> Información clara y disponible para todos.</li>
                            <li><strong>Innovación:</strong> Tecnología al servicio de la educación.</li>
                            <li><strong>Comunidad:</strong> Conectamos personas con ideas y oportunidades.</li>
                            <li><strong>Transparencia:</strong> Información veraz y sin intermediarios.</li>
                            <li><strong>Excelencia:</strong> Experiencia de usuario como prioridad.</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- EL PROYECTO -->
            <section class="sn-section">
                <div class="sn-section-header">
                    <h2>¿Qué es EduBook?</h2>
                    <div class="sn-divider"></div>
                </div>
                <div class="sn-proyecto-grid">
                    <div class="sn-proyecto-texto">
                        <p>EduBook surge de una necesidad real detectada en el entorno universitario de Barcelona: cada universidad gestiona sus eventos de forma completamente aislada, a través de sus propias webs, carteles físicos y redes sociales. Esto hace que estudiantes y profesionales pierdan oportunidades valiosas por simple falta de visibilidad.</p>
                        <p style="margin-top:16px;">Nuestra plataforma actúa como un <strong style="color:var(--color-acento)">marketplace especializado</strong>, similar a lo que Eventbrite representa para eventos generales o Meetup para comunidades, pero con un foco exclusivo en el mundo universitario y académico.</p>
                        <div class="sn-features-list">
                            <div class="sn-feature-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-acento)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Explorar y filtrar eventos por categoría, fecha, universidad o modalidad</span>
                            </div>
                            <div class="sn-feature-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-acento)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Reservar plaza o registrarse directamente en los eventos</span>
                            </div>
                            <div class="sn-feature-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-acento)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Gestionar calendario personal de eventos suscritos</span>
                            </div>
                            <div class="sn-feature-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-acento)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Asistente AI integrado para ayudarte a encontrar eventos</span>
                            </div>
                            <div class="sn-feature-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-acento)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Panel de gestión para organizadores (managers) de eventos</span>
                            </div>
                        </div>
                    </div>
                    <div class="sn-proyecto-modelo">
                        <h4 style="color:var(--color-acento); margin-bottom:20px;">Modelo de Negocio</h4>
                        <div class="sn-modelo-item">
                            <div class="sn-modelo-svg"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                            <div>
                                <strong>Usuarios</strong>
                                <p>Planes Avanzado y Premium con funcionalidades exclusivas</p>
                            </div>
                        </div>
                        <div class="sn-modelo-item">
                            <div class="sn-modelo-svg"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-4 0v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg></div>
                            <div>
                                <strong>Instituciones</strong>
                                <p>Planes Básico, Plus y Premium para universidades y centros</p>
                            </div>
                        </div>
                        <div class="sn-modelo-item">
                            <div class="sn-modelo-svg"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div>
                            <div>
                                <strong>Publicidad</strong>
                                <p>Eventos destacados y patrocinios en la plataforma</p>
                            </div>
                        </div>
                        <div class="sn-modelo-item">
                            <div class="sn-modelo-svg"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg></div>
                            <div>
                                <strong>Venta de Entradas</strong>
                                <p>Ticketing para eventos exclusivos y de pago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- EL EQUIPO -->
            <section class="sn-section">
                <div class="sn-section-header">
                    <h2>El Equipo</h2>
                    <div class="sn-divider"></div>
                    <p class="sn-section-sub">Dos desarrolladores apasionados por la tecnología y el diseño de experiencias digitales</p>
                </div>
                <div class="sn-team-grid">
                    <!-- NEIL -->
                    <div class="sn-team-card sn-team-featured">
                        <div class="sn-team-avatar">NA</div>
                        <div class="sn-team-badge-rol">Lead Developer</div>
                        <h3 class="sn-team-nombre">Neil Andrei Sambrana</h3>
                        <p class="sn-team-bio">Responsable principal del desarrollo full-stack de EduBook. Ha liderado la arquitectura del proyecto, la integración con <strong>n8n</strong> para la automatización de flujos de datos, el desarrollo del <strong>chatbot AI</strong>, la gestión de webhooks con Supabase, la autenticación de usuarios y la mayoría de las funcionalidades del backend y frontend.</p>
                        <div class="sn-team-skills">
                            <span class="sn-skill">PHP / MVC</span>
                            <span class="sn-skill">n8n Workflows</span>
                            <span class="sn-skill">Supabase</span>
                            <span class="sn-skill">Chatbot AI</span>
                            <span class="sn-skill">JavaScript</span>
                            <span class="sn-skill">CSS / UI</span>
                        </div>
                    </div>
                    <!-- ELYAS -->
                    <div class="sn-team-card">
                        <div class="sn-team-avatar" style="background: linear-gradient(135deg,#2A5F75,#133240);">EI</div>
                        <div class="sn-team-badge-rol" style="background:rgba(42,95,117,0.2);color:#7dd3fc;border-color:rgba(42,95,117,0.4);">Developer</div>
                        <h3 class="sn-team-nombre">Elyas Iqbal</h3>
                        <p class="sn-team-bio">Co-desarrollador de EduBook. Ha participado activamente en el desarrollo tanto del frontend como del backend, contribuyendo al diseño de interfaces, la implementación de componentes y la estructura del proyecto junto al equipo.</p>
                        <div class="sn-team-skills">
                            <span class="sn-skill">PHP</span>
                            <span class="sn-skill">HTML / CSS</span>
                            <span class="sn-skill">JavaScript</span>
                            <span class="sn-skill">Frontend</span>
                            <span class="sn-skill">UI Design</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- TECNOLOGÍAS -->
            <section class="sn-section">
                <div class="sn-section-header">
                    <h2>Stack Tecnológico</h2>
                    <div class="sn-divider"></div>
                </div>
                <div class="sn-tech-grid">
                    <div class="sn-tech-item">
                        <div class="sn-tech-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        </div>
                        <span>PHP 8 + MVC</span>
                    </div>
                    <div class="sn-tech-item">
                        <div class="sn-tech-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        </div>
                        <span>n8n Workflows</span>
                    </div>
                    <div class="sn-tech-item">
                        <div class="sn-tech-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
                        </div>
                        <span>Supabase (PostgreSQL)</span>
                    </div>
                    <div class="sn-tech-item">
                        <div class="sn-tech-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/><line x1="9" y1="10" x2="9" y2="10"/><line x1="12" y1="10" x2="12" y2="10"/><line x1="15" y1="10" x2="15" y2="10"/></svg>
                        </div>
                        <span>Chatbot AI</span>
                    </div>
                    <div class="sn-tech-item">
                        <div class="sn-tech-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                        </div>
                        <span>CSS / jQuery</span>
                    </div>
                    <div class="sn-tech-item">
                        <div class="sn-tech-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <span>PDO + Bcrypt</span>
                    </div>
                </div>
            </section>

            <!-- MAPA / UBICACIÓN -->
            <section class="sn-section">
                <div class="sn-section-header">
                    <h2>Dónde Estamos</h2>
                    <div class="sn-divider"></div>
                    <p class="sn-section-sub">Desarrollado en Stucom Pelai, en el corazón de Barcelona</p>
                </div>
                <div class="sn-ubicacion-grid">
                    <div class="sn-mapa-wrapper">
                        <div class="ct-mapa-btns" style="padding:12px 14px; border-bottom:1px solid var(--color-borde);">
                            <button id="btn-sn-stucom" class="ct-mapa-btn ct-mapa-btn-primary">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                Dónde Estamos
                            </button>
                            <button id="btn-sn-ubicacion" class="ct-mapa-btn">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/></svg>
                                Mi Ubicación
                            </button>
                        </div>
                        <div id="mapa-sn" style="height:350px;"></div>
                    </div>
                    <div class="sn-ubicacion-info">
                        <div class="sn-info-bloque">
                            <div class="sn-info-icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div>
                                <strong>Dirección</strong>
                                <p>Carrer de Pelai, 12<br>08001 Barcelona, España</p>
                            </div>
                        </div>
                        <div class="sn-info-bloque">
                            <div class="sn-info-icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </div>
                            <div>
                                <strong>Email</strong>
                                <p>info@edubook.es</p>
                            </div>
                        </div>
                        <div class="sn-info-bloque">
                            <div class="sn-info-icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <strong>Horario</strong>
                                <p>Lunes a Viernes: 9:00 - 18:00<br>Atención online disponible 24/7</p>
                            </div>
                        </div>
                        <div class="sn-info-bloque">
                            <div class="sn-info-icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                            </div>
                            <div>
                                <strong>Centro</strong>
                                <p>Stucom Pelai &mdash; Formación Profesional<br>Tecnolog&iacute;a &amp; Dise&ntilde;o Digital</p>
                            </div>
                        </div>
                        <a href="contacto.php" class="btn-principal" style="display:block; text-align:center; text-decoration:none; margin-top:10px; padding:14px;">
                            Contactar con nosotros
                        </a>
                    </div>
                </div>
            </section>

        </div><!-- /sn-body -->

        <?php include 'partials/footer.php'; ?>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const STUCOM_LAT = 41.38307, STUCOM_LNG = 2.16804;
const mapaSN = L.map('mapa-sn').setView([STUCOM_LAT, STUCOM_LNG], 16);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a>'
}).addTo(mapaSN);

const stucomIconSN = L.divIcon({
    html: '<div style="width:16px;height:16px;background:#A69B5D;border-radius:50%;border:3px solid #fff;box-shadow:0 0 8px rgba(0,0,0,0.5);"></div>',
    iconSize:[16,16], iconAnchor:[8,8], className:''
});
const stucomMarkerSN = L.marker([STUCOM_LAT, STUCOM_LNG], {icon: stucomIconSN})
    .addTo(mapaSN)
    .bindPopup('<strong style="color:#0F2D3C">Stucom Pelai</strong><br>Carrer de Pelai, 12<br>08001 Barcelona');
stucomMarkerSN.openPopup();

let userMarkerSN = null;

document.getElementById('btn-sn-stucom').addEventListener('click', function() {
    mapaSN.setView([STUCOM_LAT, STUCOM_LNG], 17, {animate:true});
    stucomMarkerSN.openPopup();
});

document.getElementById('btn-sn-ubicacion').addEventListener('click', function() {
    const btn = this;
    if (!navigator.geolocation) { alert('Tu navegador no soporta geolocalización.'); return; }
    btn.textContent = 'Localizando...';
    btn.disabled = true;
    navigator.geolocation.getCurrentPosition(
        function(pos) {
            const lat = pos.coords.latitude, lng = pos.coords.longitude;
            if (userMarkerSN) mapaSN.removeLayer(userMarkerSN);
            const userIconSN = L.divIcon({
                html: '<div style="width:14px;height:14px;background:#3b82f6;border-radius:50%;border:3px solid #fff;box-shadow:0 0 8px rgba(59,130,246,0.7);"></div>',
                iconSize:[14,14], iconAnchor:[7,7], className:''
            });
            userMarkerSN = L.marker([lat, lng], {icon: userIconSN}).addTo(mapaSN).bindPopup('Tu ubicación').openPopup();
            mapaSN.setView([lat, lng], 15, {animate:true});
            btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/></svg> Mi Ubicación';
            btn.disabled = false;
        },
        function() {
            btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/></svg> Mi Ubicación';
            btn.disabled = false;
            alert('No se pudo obtener tu ubicación.');
        }
    );
});
</script>
</body>
</html>
