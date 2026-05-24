<?php
require_once '../controllers/auth_protect.php';
$navUserName = $_SESSION['user']['nombre'] ?? 'Usuario';
$navAvatar   = $_SESSION['user']['avatar'] ?? '';
$fechaActual = date('d/m/Y');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos y Condiciones – EduBook</title>
    <meta name="description" content="Términos y Condiciones de uso, Política de Privacidad y Política de Cookies de EduBook S.L.">
    <link rel="stylesheet" href="assets/style.css?v=<?php echo time(); ?>">
</head>
<body>
<div class="diseno-panel">
    <?php include 'partials/sidebar.php'; ?>
    <div class="contenido-principal sin-padding">

        <!-- HEADER -->
        <header class="header-top" style="padding:30px 40px 20px; margin:0;">
            <div class="saludo">
                <h2 class="texto-dorado">Aviso Legal</h2>
                <p style="color:var(--color-texto-gris)">Términos, privacidad y cookies · Última actualización: <?php echo $fechaActual; ?></p>
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

            <!-- ÍNDICE NAV -->
            <nav class="legal-nav">
                <a href="#terminos" class="legal-nav-link">Términos y Condiciones</a>
                <a href="#privacidad" class="legal-nav-link">Política de Privacidad</a>
                <a href="#cookies" class="legal-nav-link">Política de Cookies</a>
                <a href="#aviso" class="legal-nav-link">Aviso Legal</a>
            </nav>

            <!-- TÉRMINOS Y CONDICIONES -->
            <section class="legal-section" id="terminos">
                <div class="legal-section-header">
                    <div class="legal-section-icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </div>
                    <div>
                        <h2>Términos y Condiciones de Uso</h2>
                        <span class="legal-fecha">Vigente desde: <?php echo $fechaActual; ?></span>
                    </div>
                </div>

                <div class="legal-content">
                    <div class="legal-intro">
                        El acceso y uso de la plataforma <strong>EduBook</strong> implica la aceptación plena de los presentes Términos y Condiciones. Si no está de acuerdo con los mismos, deberá abstenerse de utilizar la plataforma.
                    </div>

                    <h3>1. Identificación del Titular</h3>
                    <p>En cumplimiento de la Ley 34/2002, de 11 de julio, de Servicios de la Sociedad de la Información y de Comercio Electrónico (LSSI-CE), se informa que la presente plataforma es titularidad de:</p>
                    <div class="legal-datos-box">
                        <div class="legal-dato"><span>Razón Social</span><strong>EduBook S.L.</strong></div>
                        <div class="legal-dato"><span>CIF</span><strong>B-12345678</strong></div>
                        <div class="legal-dato"><span>Domicilio social</span><strong>Carrer de Pelai 12, 08001 Barcelona, España</strong></div>
                        <div class="legal-dato"><span>Email de contacto</span><strong>info@edubook.es</strong></div>
                        <div class="legal-dato"><span>Actividad</span><strong>Plataforma digital de eventos universitarios</strong></div>
                    </div>

                    <h3>2. Objeto y Alcance</h3>
                    <p>EduBook es una plataforma digital que actúa como marketplace de eventos universitarios, facilitando la conexión entre instituciones educativas que organizan eventos (talleres, charlas, seminarios, conferencias, ferias académicas, etc.) y usuarios interesados en participar en los mismos.</p>

                    <h3>3. Registro y Cuentas de Usuario</h3>
                    <p>Para acceder a las funcionalidades completas de EduBook es necesario crear una cuenta. El usuario se compromete a:</p>
                    <ul class="legal-list">
                        <li>Facilitar información veraz, exacta, actualizada y completa durante el registro.</li>
                        <li>Mantener y actualizar los datos facilitados para que sean veraces, exactos y completos.</li>
                        <li>Mantener la confidencialidad de su contraseña y ser responsable de todo acceso a la plataforma realizado con sus credenciales.</li>
                        <li>Notificar inmediatamente a EduBook cualquier uso no autorizado de su cuenta o brecha de seguridad.</li>
                        <li>No crear cuentas con identidades falsas, hacerse pasar por otras personas o entidades.</li>
                    </ul>

                    <h3>4. Roles de Usuario</h3>
                    <p>La plataforma dispone de dos tipologías de usuario:</p>
                    <ul class="legal-list">
                        <li><strong>Explorador:</strong> Usuario estándar que puede consultar, filtrar, suscribirse y guardar eventos como favoritos.</li>
                        <li><strong>Manager:</strong> Cuenta institucional que permite crear, editar, gestionar y publicar eventos en la plataforma. Este rol está reservado para instituciones educativas y organizadores verificados.</li>
                    </ul>

                    <h3>5. Uso Aceptable de la Plataforma</h3>
                    <p>Queda expresamente prohibido:</p>
                    <ul class="legal-list">
                        <li>Utilizar la plataforma con fines ilícitos, fraudulentos o contrarios a los presentes términos.</li>
                        <li>Publicar contenido falso, engañoso, ofensivo, discriminatorio o que vulnere derechos de terceros.</li>
                        <li>Intentar acceder sin autorización a sistemas, servidores o datos de EduBook o de terceros.</li>
                        <li>Realizar actividades de scraping, spam o cualquier forma de extracción masiva de datos.</li>
                        <li>Suplantar la identidad de instituciones educativas para publicar eventos fraudulentos.</li>
                    </ul>

                    <h3>6. Modelo de Negocio y Pagos</h3>
                    <p>EduBook opera bajo un modelo freemium:</p>
                    <ul class="legal-list">
                        <li><strong>Plan Gratuito:</strong> Acceso básico a la exploración de eventos.</li>
                        <li><strong>Planes de Usuario (Avanzado/Premium):</strong> Funcionalidades adicionales de personalización, alertas y acceso prioritario.</li>
                        <li><strong>Planes Institucionales (Básico/Plus/Premium):</strong> Capacidades ampliadas de publicación y analítica.</li>
                        <li><strong>Publicidad:</strong> Las instituciones podrán contratar espacios destacados para sus eventos.</li>
                        <li><strong>Venta de Entradas:</strong> Para eventos de pago, EduBook actuará como intermediario aplicando una comisión de servicio.</li>
                    </ul>

                    <h3>7. Propiedad Intelectual</h3>
                    <p>Todos los contenidos de EduBook (diseño, código, logotipos, textos, imágenes) son propiedad de EduBook S.L. o de sus legítimos titulares y están protegidos por la legislación española e internacional sobre propiedad intelectual e industrial. Queda prohibida su reproducción, distribución, comunicación pública o transformación sin autorización expresa.</p>

                    <h3>8. Limitación de Responsabilidad</h3>
                    <p>EduBook actúa como intermediario entre organizadores y asistentes. No se hace responsable de la calidad, exactitud o cumplimiento de los eventos publicados por terceros. La responsabilidad del contenido publicado recae íntegramente en el organizador del evento.</p>

                    <h3>9. Modificación de los Términos</h3>
                    <p>EduBook se reserva el derecho de modificar los presentes términos en cualquier momento. Los cambios serán notificados a los usuarios registrados y publicados en esta página. El uso continuado de la plataforma tras la notificación implica la aceptación de los nuevos términos.</p>
                </div>
            </section>

            <!-- POLÍTICA DE PRIVACIDAD -->
            <section class="legal-section" id="privacidad">
                <div class="legal-section-header">
                    <div class="legal-section-icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <div>
                        <h2>Política de Privacidad</h2>
                        <span class="legal-fecha">RGPD · Reglamento (UE) 2016/679</span>
                    </div>
                </div>

                <div class="legal-content">
                    <div class="legal-intro">
                        En EduBook nos tomamos muy en serio la privacidad de nuestros usuarios. Esta política describe qué datos recogemos, cómo los usamos y cuáles son tus derechos.
                    </div>

                    <h3>1. Responsable del Tratamiento</h3>
                    <div class="legal-datos-box">
                        <div class="legal-dato"><span>Responsable</span><strong>EduBook S.L.</strong></div>
                        <div class="legal-dato"><span>DPO / Contacto privacidad</span><strong>privacidad@edubook.es</strong></div>
                        <div class="legal-dato"><span>Base jurídica</span><strong>Reglamento (UE) 2016/679 (RGPD) y LOPDGDD</strong></div>
                    </div>

                    <h3>2. Datos que Recogemos</h3>
                    <div class="legal-tabla-wrapper">
                        <table class="legal-tabla">
                            <thead>
                                <tr><th>Dato</th><th>Finalidad</th><th>Base Legal</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>Nombre y apellidos</td><td>Identificación en la plataforma</td><td>Ejecución de contrato</td></tr>
                                <tr><td>Email</td><td>Autenticación y comunicaciones</td><td>Ejecución de contrato</td></tr>
                                <tr><td>Contraseña (hash)</td><td>Seguridad de la cuenta</td><td>Ejecución de contrato</td></tr>
                                <tr><td>Avatar / Foto de perfil</td><td>Personalización del perfil</td><td>Consentimiento</td></tr>
                                <tr><td>Datos de eventos suscritos</td><td>Calendario personal y favoritos</td><td>Ejecución de contrato</td></tr>
                                <tr><td>Logs de acceso (IP, navegador)</td><td>Seguridad y análisis técnico</td><td>Interés legítimo</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <h3>3. No Vendemos tus Datos</h3>
                    <p>EduBook no vende, alquila ni cede datos personales a terceros con fines comerciales. Los datos únicamente se comparten con proveedores técnicos necesarios para el funcionamiento del servicio (Supabase para almacenamiento, n8n para automatización de flujos) bajo contratos de encargo de tratamiento.</p>

                    <h3>4. Tus Derechos (RGPD)</h3>
                    <p>Como interesado, puedes ejercer los siguientes derechos enviando un email a <strong>privacidad@edubook.es</strong>:</p>
                    <div class="legal-derechos-grid">
                        <div class="legal-derecho">
                            <div class="legal-derecho-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div>
                            <div><strong>Acceso</strong><p>Conocer qué datos tenemos sobre ti</p></div>
                        </div>
                        <div class="legal-derecho">
                            <div class="legal-derecho-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
                            <div><strong>Rectificación</strong><p>Corregir datos inexactos</p></div>
                        </div>
                        <div class="legal-derecho">
                            <div class="legal-derecho-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg></div>
                            <div><strong>Supresión</strong><p>Solicitar la eliminación de tus datos</p></div>
                        </div>
                        <div class="legal-derecho">
                            <div class="legal-derecho-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="10" y1="15" x2="10" y2="9"/><line x1="14" y1="15" x2="14" y2="9"/></svg></div>
                            <div><strong>Limitación</strong><p>Restringir el tratamiento de tus datos</p></div>
                        </div>
                        <div class="legal-derecho">
                            <div class="legal-derecho-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></div>
                            <div><strong>Portabilidad</strong><p>Recibir tus datos en formato legible</p></div>
                        </div>
                        <div class="legal-derecho">
                            <div class="legal-derecho-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg></div>
                            <div><strong>Oposición</strong><p>Oponerte al tratamiento por interés legítimo</p></div>
                        </div>
                    </div>

                    <h3>5. Retención de Datos</h3>
                    <p>Los datos se conservan mientras la cuenta esté activa. Puedes eliminar tu cuenta en cualquier momento desde <a href="profile.php" style="color:var(--color-acento);">Mi Perfil</a>. Tras la eliminación, los datos se borran en un plazo máximo de 30 días, excepto aquellos que deban conservarse por obligación legal.</p>
                </div>
            </section>

            <!-- POLÍTICA DE COOKIES -->
            <section class="legal-section" id="cookies">
                <div class="legal-section-header">
                    <div class="legal-section-icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10"/><path d="M12 8v4l3 3"/><circle cx="18" cy="5" r="3"/></svg>
                    </div>
                    <div>
                        <h2>Política de Cookies</h2>
                        <span class="legal-fecha">Directiva ePrivacy · LSSI-CE</span>
                    </div>
                </div>

                <div class="legal-content">
                    <div class="legal-intro">
                        EduBook utiliza cookies y tecnologías similares para garantizar el funcionamiento de la plataforma, mejorar la experiencia de usuario y realizar análisis de uso.
                    </div>

                    <h3>Tipos de Cookies que Utilizamos</h3>
                    <div class="legal-tabla-wrapper">
                        <table class="legal-tabla">
                            <thead>
                                <tr><th>Tipo</th><th>Nombre</th><th>Finalidad</th><th>Duración</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="legal-badge legal-badge-required">Necesaria</span></td>
                                    <td>PHPSESSID</td>
                                    <td>Gestión de sesión de usuario autenticado</td>
                                    <td>Sesión</td>
                                </tr>
                                <tr>
                                    <td><span class="legal-badge legal-badge-required">Necesaria</span></td>
                                    <td>edubook_cookie_consent</td>
                                    <td>Almacena la preferencia de cookies del usuario</td>
                                    <td>1 año</td>
                                </tr>
                                <tr>
                                    <td><span class="legal-badge legal-badge-func">Funcional</span></td>
                                    <td>edubook_prefs</td>
                                    <td>Preferencias de interfaz (filtros, configuración)</td>
                                    <td>30 días</td>
                                </tr>
                                <tr>
                                    <td><span class="legal-badge legal-badge-anl">Analítica</span></td>
                                    <td>_ga, _gid</td>
                                    <td>Análisis anónimo de uso de la plataforma</td>
                                    <td>2 años</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h3>Gestión de Cookies</h3>
                    <p>Puedes configurar tu navegador para rechazar las cookies o ser notificado cuando se envíen. Sin embargo, desactivar las cookies necesarias puede afectar al funcionamiento de la plataforma. Para gestionar tus preferencias, accede a la configuración de privacidad de tu navegador.</p>
                </div>
            </section>

            <!-- AVISO LEGAL -->
            <section class="legal-section" id="aviso">
                <div class="legal-section-header">
                    <div class="legal-section-icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </div>
                    <div>
                        <h2>Aviso Legal</h2>
                        <span class="legal-fecha">Ley 34/2002, LSSI-CE</span>
                    </div>
                </div>
                <div class="legal-content">
                    <h3>Legislación Aplicable y Jurisdicción</h3>
                    <p>Los presentes términos se rigen por la legislación española vigente. Para la resolución de cualquier controversia derivada del uso de esta plataforma, las partes se someten expresamente a la jurisdicción de los Juzgados y Tribunales de la ciudad de Barcelona, con renuncia expresa a cualquier otro fuero que pudiera corresponderles.</p>
                    <h3>Legislación de Referencia</h3>
                    <ul class="legal-list">
                        <li>Reglamento (UE) 2016/679 del Parlamento Europeo — RGPD</li>
                        <li>Ley Orgánica 3/2018, de 5 de diciembre — LOPDGDD</li>
                        <li>Ley 34/2002, de 11 de julio — LSSI-CE</li>
                        <li>Real Decreto Legislativo 1/1996 — Ley de Propiedad Intelectual</li>
                        <li>Ley 7/1998, de 13 de abril — Condiciones Generales de Contratación</li>
                    </ul>
                    <p style="margin-top:20px;">Para cualquier consulta legal, contacta con nosotros en <strong>legal@edubook.es</strong>.</p>
                </div>
            </section>

        </div>

        <?php include 'partials/footer.php'; ?>
    </div>
</div>

<script>
// Smooth scroll para los anchor links del índice
document.querySelectorAll('.legal-nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        document.querySelectorAll('.legal-nav-link').forEach(l => l.classList.remove('activo'));
        this.classList.add('activo');
    });
});
</script>
</body>
</html>
