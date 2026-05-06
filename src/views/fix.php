<?php
$files = glob(__DIR__ . '/*.php');
$replacement = <<<EOT
                <div class="perfil-usuario">
                    <?php 
                    \$navUserName = \$_SESSION['user']['nombre'] ?? 'Usuario';
                    \$navAvatar = \$_SESSION['user']['avatar'] ?? '';
                    ?>
                    <span class="nombre-corto"><?php echo htmlspecialchars(\$navUserName); ?></span>
                    <div class="avatar-circulo" <?php echo !\$navAvatar ? 'style="background:var(--color-acento);color:#111;font-weight:bold;"' : 'style="background:transparent;border:1px solid var(--color-borde);padding:0;overflow:hidden;"'; ?>>
                        <?php if (\$navAvatar): ?>
                            <img src="<?php echo htmlspecialchars(\$navAvatar); ?>" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
                        <?php else: ?>
                            <?php echo strtoupper(substr(\$navUserName, 0, 1)); ?>
                        <?php endif; ?>
                    </div>
                </div>
EOT;

foreach ($files as $f) {
    if (preg_match('/(profile|login|register)\.php$/', $f)) continue;
    
    $c = file_get_contents($f);
    // Use regex to match the <div class="perfil-usuario">...</div> block exactly
    $nc = preg_replace('/[ \t]*<div class="perfil-usuario">.*?<\/div>\s*<\/div>/s', $replacement, $c);
    
    if ($c !== $nc) {
        file_put_contents($f, $nc);
        echo "Updated " . basename($f) . "\n";
    }
}
