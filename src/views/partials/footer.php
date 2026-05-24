<?php
// partials/footer.php
$year = date('Y');
$paginaFooter = basename($_SERVER['PHP_SELF']);
?>
<footer class="footer-principal">
    <div class="footer-inner">
        <div class="footer-grid">
            <!-- BRAND -->
            <div class="footer-col footer-brand">
                <img src="assets/img/logo.png" alt="EduBook Logo" class="footer-logo">
                <p class="footer-desc">La plataforma que centraliza y simplifica el acceso a eventos universitarios en Barcelona. Conectamos estudiantes, profesionales e instituciones educativas en un solo lugar.</p>
                <div class="footer-social">
                    <a href="#" class="social-btn" aria-label="Twitter/X" title="Twitter">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.261 5.638 5.903-5.638zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="#" class="social-btn" aria-label="Instagram" title="Instagram">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                    <a href="#" class="social-btn" aria-label="LinkedIn" title="LinkedIn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                    <a href="#" class="social-btn" aria-label="YouTube" title="YouTube">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                </div>
            </div>

            <!-- NAV -->
            <div class="footer-col">
                <h4 class="footer-titulo">Plataforma</h4>
                <ul class="footer-links">
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="buscar.php">Explorar Eventos</a></li>
                    <li><a href="calendario.php">Calendario</a></li>
                    <li><a href="favoritos.php">Mis Favoritos</a></li>
                    <li><a href="profile.php">Mi Perfil</a></li>
                </ul>
            </div>

            <!-- COMPANY -->
            <div class="footer-col">
                <h4 class="footer-titulo">Empresa</h4>
                <ul class="footer-links">
                    <li><a href="sobre_nosotros.php">Sobre Nosotros</a></li>
                    <li><a href="contacto.php">Contacto</a></li>
                    <li><a href="terminos.php">Términos y Condiciones</a></li>
                    <li><a href="terminos.php#privacidad">Política de Privacidad</a></li>
                    <li><a href="terminos.php#cookies">Política de Cookies</a></li>
                </ul>
            </div>

            <!-- CONTACT -->
            <div class="footer-col">
                <h4 class="footer-titulo">Contacto</h4>
                <ul class="footer-contacto-list">
                    <li>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>Carrer de Pelai 12, 08001 Barcelona</span>
                    </li>
                    <li>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <span>info@edubook.es</span>
                    </li>
                    <li>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.42 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.77a16 16 0 0 0 6.29 6.29l.95-1.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 15.27v1.65z"/></svg>
                        <span>+34 93 318 00 00</span>
                    </li>
                    <li>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span>Lun – Vie: 9:00 – 18:00</span>
                    </li>
                </ul>
                <a href="contacto.php" class="btn-footer-contacto">Enviar mensaje →</a>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="footer-copy">&copy; <?php echo $year; ?> EduBook S.L. · Todos los derechos reservados · CIF: B-12345678</p>
            <div class="footer-bottom-links">
                <a href="terminos.php">Términos</a>
                <span class="footer-sep">·</span>
                <a href="terminos.php#privacidad">Privacidad</a>
                <span class="footer-sep">·</span>
                <a href="terminos.php#cookies">Cookies</a>
                <span class="footer-sep">·</span>
                <a href="sobre_nosotros.php">Sobre Nosotros</a>
                <span class="footer-sep">·</span>
                <a href="contacto.php">Contacto</a>
            </div>
        </div>
    </div>
</footer>
