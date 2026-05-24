<?php
require_once '../controllers/auth_protect.php';
require_once '../models/Event.php';

$rolUsuario = strtolower(trim($_SESSION['user']['rol'] ?? 'explorador'));
$esManager = ($rolUsuario === 'manager');
$userId = $_SESSION['user']['id'] ?? '';

$eventModel = new Event();
$todosEventos = $eventModel->getAll();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar - EduBook</title>
    <link rel="stylesheet" href="assets/style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="diseno-panel">
        <?php include 'partials/sidebar.php'; ?>
        <main class="contenido-principal">

            <header class="header-top">
                <div class="saludo">
                    <h2 class="texto-dorado">Buscador</h2>
                    <p style="color:var(--color-texto-gris)">Explora todos los eventos universitarios</p>
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

            <div class="contenedor-busqueda">
                <svg class="icono-lupa-input" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" id="input-busqueda" class="input-busqueda"
                    placeholder="Buscar por título, universidad, lugar...">
                <button class="btn-buscar" onclick="filtrarEventos()">Buscar</button>
            </div>

            <div class="layout-busqueda">
                <aside class="panel-filtros">
                    <div class="grupo-filtro">
                        <h4>Categorías</h4>
                        <button class="btn-filtro activo" data-filtro="todas">Todas</button>
                        <button class="btn-filtro" data-filtro="ingenieria">Ingeniería</button>
                        <button class="btn-filtro" data-filtro="diseno">Diseño</button>
                        <button class="btn-filtro" data-filtro="marketing">Marketing</button>
                        <button class="btn-filtro" data-filtro="ciencias">Ciencias</button>
                        <button class="btn-filtro" data-filtro="tecnologia">Tecnología</button>
                        <button class="btn-filtro" data-filtro="arte">Arte</button>
                        <button class="btn-filtro" data-filtro="general">General</button>
                    </div>
                    <div class="grupo-filtro">
                        <h4>Modalidad</h4>
                        <button class="btn-filtro" data-filtro="presencial">Presencial</button>
                        <button class="btn-filtro" data-filtro="online">Online</button>
                        <button class="btn-filtro" data-filtro="hibrida">Híbrida</button>
                    </div>
                </aside>

                <section class="resultados-busqueda">
                    <h3 class="titulo-seccion" id="contador-resultados" style="margin-top:0;">
                        Resultados (<?php echo count($todosEventos); ?>)
                    </h3>

                    <div class="grid-tarjetas-2col" id="grid-eventos">
                        <?php if (empty($todosEventos)): ?>
                            <div
                                style="grid-column:1/-1;text-align:center;padding:60px 20px;color:var(--color-texto-gris);">
                                <p>No hay eventos publicados todavía.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($todosEventos as $evt):
                                $esMio = ($evt['creador_id'] === $userId && $esManager);
                                ?>
                                <div class="tarjeta-evento-wrap"
                                    data-titulo="<?php echo htmlspecialchars(strtolower($evt['titulo'])); ?>"
                                    data-uni="<?php echo htmlspecialchars(strtolower($evt['universidad'] . ' ' . $evt['creador_nombre'])); ?>"
                                    data-categoria="<?php echo htmlspecialchars($evt['categoria']); ?>"
                                    data-modalidad="<?php echo htmlspecialchars($evt['modalidad']); ?>"
                                    style="position:relative;">

                                    <a href="evento.php?id=<?php echo urlencode($evt['id']); ?>" class="tarjeta-evento"
                                        style="display:block;text-decoration:none;color:inherit;height:100%;">
                                        <div class="imagen-evento">
                                            <?php $imgSrc = !empty($evt['imagen']) ? htmlspecialchars($evt['imagen']) : 'assets/img/logo.png'; ?>
                                            <img src="<?php echo $imgSrc; ?>"
                                                 alt="<?php echo htmlspecialchars($evt['titulo']); ?>"
                                                 onerror="this.onerror=null; this.src='assets/img/logo.png';"
                                                 style="width:100%;height:100%;object-fit:cover;">
                                        </div>
                                        <div class="contenido-tarjeta">
                                            <div class="rating"><?php echo date('d/m/Y', strtotime($evt['fecha'])); ?>
                                                &nbsp;·&nbsp; <?php echo htmlspecialchars(ucfirst($evt['modalidad'])); ?></div>
                                            <h4><?php echo htmlspecialchars($evt['titulo']); ?></h4>
                                            <p class="uni-nombre">
                                                <?php echo htmlspecialchars($evt['universidad'] ?: $evt['creador_nombre']); ?>
                                            </p>
                                        </div>
                                    </a>

                                    <?php if ($esMio): ?>
                                        <div style="position:absolute;top:10px;right:10px;display:flex;gap:6px;z-index:2;">
                                            <a href="editar_evento.php?id=<?php echo urlencode($evt['id']); ?>"
                                                style="background:rgba(166,155,93,0.9);color:#111;border:none;padding:5px 10px;border-radius:5px;font-size:0.78rem;font-weight:bold;text-decoration:none;cursor:pointer;">
                                                Editar
                                            </a>
                                            <form method="POST" action="panel.php" style="display:inline;"
                                                onsubmit="return confirm('¿Eliminar este evento?')">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="evento_id"
                                                    value="<?php echo htmlspecialchars($evt['id']); ?>">
                                                <button type="submit"
                                                    style="background:rgba(239,68,68,0.9);color:#fff;border:none;padding:5px 10px;border-radius:5px;font-size:0.78rem;font-weight:bold;cursor:pointer;">
                                                    Borrar
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <p id="sin-resultados"
                        style="display:none;color:var(--color-texto-gris);padding:40px 0;text-align:center;">
                        No se encontraron eventos con ese criterio.
                    </p>
                </section>
            </div>
            <?php include 'partials/footer.php'; ?>
        </main>
    </div>

    <script>
        let categoriaActiva = 'todas';

        document.querySelectorAll('.btn-filtro').forEach(btn => {
            btn.addEventListener('click', function () {
                this.closest('.grupo-filtro').querySelectorAll('.btn-filtro').forEach(b => b.classList.remove('activo'));
                this.classList.add('activo');
                categoriaActiva = this.dataset.filtro;
                filtrarEventos();
            });
        });

        document.getElementById('input-busqueda').addEventListener('input', filtrarEventos);

        function filtrarEventos() {
            const query = document.getElementById('input-busqueda').value.toLowerCase().trim();
            const wraps = document.querySelectorAll('#grid-eventos .tarjeta-evento-wrap');
            let visibles = 0;

            wraps.forEach(wrap => {
                const titulo = wrap.dataset.titulo || '';
                const uni = wrap.dataset.uni || '';
                const categoria = wrap.dataset.categoria || '';
                const modalidad = wrap.dataset.modalidad || '';

                const coincideTexto = !query || titulo.includes(query) || uni.includes(query);
                const coincideFiltro = categoriaActiva === 'todas'
                    || categoria === categoriaActiva
                    || modalidad === categoriaActiva;

                if (coincideTexto && coincideFiltro) {
                    wrap.style.display = '';
                    visibles++;
                } else {
                    wrap.style.display = 'none';
                }
            });

            document.getElementById('contador-resultados').textContent = 'Resultados (' + visibles + ')';
            document.getElementById('sin-resultados').style.display = visibles === 0 ? 'block' : 'none';
        }
    </script>
</body>

</html>
