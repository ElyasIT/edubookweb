<?php
require_once '../controllers/auth_protect.php';
$navUserName = $_SESSION['user']['nombre'] ?? 'Usuario';
$navAvatar   = $_SESSION['user']['avatar'] ?? '';

$msgExito = '';
$msgError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre  = trim($_POST['nombre']  ?? '');
    $email   = trim($_POST['email']   ?? '');
    $asunto  = trim($_POST['asunto']  ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');
    $tipo    = trim($_POST['tipo']    ?? 'general');

    if (!$nombre || !$email || !$asunto || !$mensaje) {
        $msgError = 'Por favor, rellena todos los campos obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msgError = 'El email introducido no es válido.';
    } else {
        // Simulación de envío (en producción: webhook n8n o mail())
        $msgExito = '¡Mensaje enviado correctamente! Te responderemos en un plazo máximo de 48 horas.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto – EduBook</title>
    <meta name="description" content="Contacta con el equipo de EduBook. Estamos en Stucom Pelai, Barcelona. Resolvemos tus dudas sobre eventos universitarios.">
    <link rel="stylesheet" href="assets/style.css?v=<?php echo time(); ?>">
</head>
<body>
<div class="diseno-panel">
    <?php include 'partials/sidebar.php'; ?>
    <div class="contenido-principal sin-padding">

        <!-- HEADER -->
        <header class="header-top" style="padding:30px 40px 20px; margin:0;">
            <div class="saludo">
                <h2 class="texto-dorado">Contacto</h2>
                <p style="color:var(--color-texto-gris)">Estamos aquí para ayudarte</p>
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

        <div class="sn-body">

            <!-- CANALES RÁPIDOS -->
            <section class="sn-section" style="padding-top:0;">
                <div class="ct-canales-grid">
                    <div class="ct-canal-card">
                        <div class="ct-canal-icon">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <h4>Email</h4>
                        <p>info@edubook.es</p>
                        <span class="ct-canal-tiempo">Respuesta en menos de 48h</span>
                    </div>
                    <div class="ct-canal-card">
                        <div class="ct-canal-icon">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.42 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.77a16 16 0 0 0 6.29 6.29l.95-1.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 15.27v1.65z"/></svg>
                        </div>
                        <h4>Teléfono</h4>
                        <p>+34 93 318 00 00</p>
                        <span class="ct-canal-tiempo">Lun–Vie de 9:00 a 18:00</span>
                    </div>
                    <div class="ct-canal-card">
                        <div class="ct-canal-icon">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </div>
                        <h4>Chat AI</h4>
                        <p>Chatbot disponible 24/7</p>
                        <span class="ct-canal-tiempo">Respuesta inmediata</span>
                    </div>
                    <div class="ct-canal-card">
                        <div class="ct-canal-icon">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <h4>Oficina</h4>
                        <p>Carrer de Pelai 12, Barcelona</p>
                        <span class="ct-canal-tiempo">Stucom Pelai · 08001</span>
                    </div>
                </div>
            </section>

            <!-- FORMULARIO + MAPA -->
            <section class="sn-section">
                <div class="ct-main-grid">
                    <!-- FORMULARIO -->
                    <div class="ct-form-wrapper">
                        <h3 style="color:var(--color-acento); margin-bottom:8px;">Envíanos un mensaje</h3>
                        <p style="color:var(--color-texto-gris); margin-bottom:28px; font-size:0.95rem;">¿Tienes alguna duda, sugerencia o quieres colaborar? Rellena el formulario y te respondemos pronto.</p>

                        <?php if ($msgExito): ?>
                            <div class="alerta-form ok" style="margin-bottom:24px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                <?php echo htmlspecialchars($msgExito); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($msgError): ?>
                            <div class="alerta-form error" style="margin-bottom:24px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                <?php echo htmlspecialchars($msgError); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="contacto.php" id="form-contacto" novalidate>
                            <!-- Tipo de consulta -->
                            <div class="grupo-input" style="margin-bottom:20px;">
                                <label style="display:block; margin-bottom:8px; font-size:0.9rem;">Tipo de consulta</label>
                                <div class="ct-tipo-grid">
                                    <label class="ct-tipo-btn">
                                        <input type="radio" name="tipo" value="general" <?php echo ($_POST['tipo']??'general')==='general'?'checked':''; ?>>
                                        <span>General</span>
                                    </label>
                                    <label class="ct-tipo-btn">
                                        <input type="radio" name="tipo" value="soporte" <?php echo ($_POST['tipo']??'')==='soporte'?'checked':''; ?>>
                                        <span>Soporte</span>
                                    </label>
                                    <label class="ct-tipo-btn">
                                        <input type="radio" name="tipo" value="institucional" <?php echo ($_POST['tipo']??'')==='institucional'?'checked':''; ?>>
                                        <span>Institucional</span>
                                    </label>
                                    <label class="ct-tipo-btn">
                                        <input type="radio" name="tipo" value="colaboracion" <?php echo ($_POST['tipo']??'')==='colaboracion'?'checked':''; ?>>
                                        <span>Colaboración</span>
                                    </label>
                                </div>
                            </div>

                            <div class="ct-form-row">
                                <div class="grupo-input">
                                    <label for="ct-nombre">Nombre completo *</label>
                                    <input type="text" id="ct-nombre" name="nombre" placeholder="Tu nombre" value="<?php echo htmlspecialchars($_POST['nombre']??$navUserName); ?>" required>
                                </div>
                                <div class="grupo-input">
                                    <label for="ct-email">Email *</label>
                                    <input type="email" id="ct-email" name="email" placeholder="tu@email.com" value="<?php echo htmlspecialchars($_POST['email']??($_SESSION['user']['email']??'')); ?>" required>
                                </div>
                            </div>

                            <div class="grupo-input">
                                <label for="ct-asunto">Asunto *</label>
                                <input type="text" id="ct-asunto" name="asunto" placeholder="¿En qué podemos ayudarte?" value="<?php echo htmlspecialchars($_POST['asunto']??''); ?>" required>
                            </div>

                            <div class="grupo-input">
                                <label for="ct-mensaje">Mensaje *</label>
                                <textarea id="ct-mensaje" name="mensaje" rows="6" placeholder="Escribe tu mensaje aquí..." style="width:100%; padding:12px; background:var(--color-input-bg); border:2px solid transparent; border-radius:5px; color:var(--color-input-text); font-size:1rem; resize:vertical; outline:none; transition:0.3s; font-family:inherit;" required><?php echo htmlspecialchars($_POST['mensaje']??''); ?></textarea>
                            </div>

                            <div class="grupo-input" style="margin-bottom:24px;">
                                <label class="checkbox-terminos" style="cursor:pointer;">
                                    <input type="checkbox" name="acepta_privacidad" required style="min-width:16px;min-height:16px;">
                                    <span>He leído y acepto la <a href="terminos.php#privacidad" style="color:var(--color-acento);">Política de Privacidad</a> de EduBook. *</span>
                                </label>
                            </div>

                            <button type="submit" class="btn-principal" id="btn-enviar-contacto">
                                Enviar mensaje
                            </button>
                        </form>
                    </div>

                    <!-- MAPA Leaflet -->
                    <div class="ct-mapa-aside">
                        <h3 style="color:var(--color-acento); margin-bottom:8px;">Nuestra ubicación</h3>
                        <p style="color:var(--color-texto-gris); margin-bottom:14px; font-size:0.95rem;">Stucom Pelai &middot; Carrer de Pelai 12, Barcelona</p>

                        <div class="ct-mapa-btns">
                            <button id="btn-stucom" class="ct-mapa-btn ct-mapa-btn-primary">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                Dónde Estamos
                            </button>
                            <button id="btn-mi-ubicacion" class="ct-mapa-btn">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/></svg>
                                Mi Ubicación
                            </button>
                        </div>

                        <div id="mapa-contacto" style="height:280px; border-radius:12px; margin-bottom:14px; border:1px solid var(--color-borde);"></div>

                        <div class="ct-faq-box">
                            <h4 style="color:var(--color-acento); margin-bottom:14px;">Preguntas frecuentes</h4>
                            <div class="ct-faq-item">
                                <button class="ct-faq-btn" onclick="this.nextElementSibling.classList.toggle('open')">
                                    &iquest;Cómo me registro como universidad?
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                                </button>
                                <div class="ct-faq-resp">Regístrate con el rol <strong>Manager</strong> en la página de registro. Te enviaremos las instrucciones para verificar tu institución.</div>
                            </div>
                            <div class="ct-faq-item">
                                <button class="ct-faq-btn" onclick="this.nextElementSibling.classList.toggle('open')">
                                    &iquest;Cómo publico un evento?
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                                </button>
                                <div class="ct-faq-resp">Con una cuenta Manager, accede a <em>Crear Evento</em> desde el panel lateral y rellena el formulario.</div>
                            </div>
                            <div class="ct-faq-item">
                                <button class="ct-faq-btn" onclick="this.nextElementSibling.classList.toggle('open')">
                                    &iquest;El servicio es gratuito?
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                                </button>
                                <div class="ct-faq-resp">El acceso básico es gratuito. Disponemos de planes premium con funcionalidades avanzadas.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>

        <?php include 'partials/footer.php'; ?>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Validación formulario
document.getElementById('form-contacto').addEventListener('submit', function() {
    const btn = document.getElementById('btn-enviar-contacto');
    const nombre = document.getElementById('ct-nombre').value.trim();
    const email  = document.getElementById('ct-email').value.trim();
    const asunto = document.getElementById('ct-asunto').value.trim();
    const msg    = document.getElementById('ct-mensaje').value.trim();
    if (nombre && email && asunto && msg) { btn.textContent = 'Enviando...'; btn.disabled = true; }
});
document.getElementById('ct-mensaje').addEventListener('focus', function() { this.style.borderColor='var(--color-acento)'; });
document.getElementById('ct-mensaje').addEventListener('blur', function() { this.style.borderColor='transparent'; });

// MAPA LEAFLET
const STUCOM_LAT = 41.38307, STUCOM_LNG = 2.16804;
const mapaContacto = L.map('mapa-contacto').setView([STUCOM_LAT, STUCOM_LNG], 16);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a>'
}).addTo(mapaContacto);

// Marcador Stucom con color acento
const stucomIcon = L.divIcon({
    html: '<div style="width:16px;height:16px;background:#A69B5D;border-radius:50%;border:3px solid #fff;box-shadow:0 0 8px rgba(0,0,0,0.5);"></div>',
    iconSize: [16,16], iconAnchor: [8,8], className:''
});
const stucomMarker = L.marker([STUCOM_LAT, STUCOM_LNG], {icon: stucomIcon})
    .addTo(mapaContacto)
    .bindPopup('<strong style="color:#0F2D3C">Stucom Pelai</strong><br>Carrer de Pelai, 12<br>08001 Barcelona');
stucomMarker.openPopup();

let userMarker = null;

// Botón "Dónde Estamos"
document.getElementById('btn-stucom').addEventListener('click', function() {
    mapaContacto.setView([STUCOM_LAT, STUCOM_LNG], 17, {animate: true});
    stucomMarker.openPopup();
});

// Botón "Mi Ubicación"
document.getElementById('btn-mi-ubicacion').addEventListener('click', function() {
    const btn = this;
    if (!navigator.geolocation) {
        alert('Tu navegador no soporta geolocalización.');
        return;
    }
    btn.textContent = 'Localizando...';
    btn.disabled = true;
    navigator.geolocation.getCurrentPosition(
        function(pos) {
            const lat = pos.coords.latitude, lng = pos.coords.longitude;
            if (userMarker) mapaContacto.removeLayer(userMarker);
            const userIcon = L.divIcon({
                html: '<div style="width:14px;height:14px;background:#3b82f6;border-radius:50%;border:3px solid #fff;box-shadow:0 0 8px rgba(59,130,246,0.7);"></div>',
                iconSize:[14,14], iconAnchor:[7,7], className:''
            });
            userMarker = L.marker([lat, lng], {icon: userIcon})
                .addTo(mapaContacto)
                .bindPopup('Tu ubicación actual').openPopup();
            mapaContacto.setView([lat, lng], 15, {animate: true});
            btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/></svg> Mi Ubicación';
            btn.disabled = false;
        },
        function() {
            btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/></svg> Mi Ubicación';
            btn.disabled = false;
            alert('No se pudo obtener tu ubicación. Comprueba los permisos del navegador.');
        }
    );
});
</script>
</body>
</html>
