<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Normalizamos el rol para evitar problemas de mayúsculas o espacios
$rol = strtolower(trim($_SESSION['user']['rol'] ?? 'explorador'));
$esManager = ($rol === 'manager');

// Detectamos la página activa para marcarla en el menú
$paginaActual = basename($_SERVER['PHP_SELF']);
function navLink($href, $label, $icon, $paginaActual) {
    $activo = ($paginaActual === $href) ? ' activo' : '';
    return "<a href=\"{$href}\" class=\"item-nav{$activo}\">{$icon}{$label}</a>";
}
?>
<aside class="barra-lateral">
    <div class="logo-sidebar">
        <img src="assets/img/logo.png" alt="EduBook Logo">
    </div>

    <nav class="menu-nav">
        <?php echo navLink('index.php', 'Inicio', '
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
        ', $paginaActual); ?>

        <?php echo navLink('buscar.php', 'Buscar', '
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        ', $paginaActual); ?>

        <?php if (!$esManager): ?>
        <?php echo navLink('calendario.php', 'Calendario', '
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        ', $paginaActual); ?>

        <?php echo navLink('favoritos.php', 'Favoritos', '
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
        ', $paginaActual); ?>
        <?php endif; ?>

        <?php if ($esManager): ?>
        <?php echo navLink('panel.php', 'Panel de Gestión', '
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
        ', $paginaActual); ?>

        <?php echo navLink('crear_evento.php', 'Crear Evento', '
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
        ', $paginaActual); ?>
        <?php endif; ?>

        <?php echo navLink('profile.php', 'Perfil', '
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        ', $paginaActual); ?>

        <div class="nav-separator">Información</div>

        <?php echo navLink('sobre_nosotros.php', 'Sobre Nosotros', '
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        ', $paginaActual); ?>

        <?php echo navLink('contacto.php', 'Contacto', '
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        ', $paginaActual); ?>

        <a href="../controllers/logout.php" class="item-nav btn-logout">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
            Cerrar sesión
        </a>
    </nav>

    <div class="tip-container">
        <p class="texto-tip">Usa <strong>Tab</strong> para navegar por teclado</p>
    </div>
</aside>

<?php
// Chatbot widget — disponible en todas las páginas autenticadas
$chatbotPath = __DIR__ . '/chatbot.php';
if (file_exists($chatbotPath)) {
    require_once '../../config/webhooks.php';
    include $chatbotPath;
}
?>
