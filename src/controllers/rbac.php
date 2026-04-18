<?php
// RBAC 

function requireRole($requiredRole)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }

    // NORMALIZAR ROL 
    $userRol = strtolower(trim($_SESSION['user']['rol'] ?? 'explorador'));
    $required = strtolower(trim($requiredRole));

    // FALLBACK EXPLORADOR 
    if (empty($userRol)) {
        $userRol = 'explorador';
        // ACTUALIZAR SESION 
        $_SESSION['user']['rol'] = 'explorador';
    }

    if ($userRol !== $required) {
        header('Location: index.php');
        exit;
    }
}
