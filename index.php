<?php
// index.php (Raíz del proyecto Axioma)

// 1. Iniciamos la sesión en la primera línea de todas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🚪 2. MÓDULO LOGOUT: Aquí va lo segundo que te pasé.
// Si el botón rojo manda la señal 'logout', este bloque limpia todo y te expulsa.
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = array(); 
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        ); 
    }
    session_destroy(); 
    header("Location: index.php?url=login"); 
    exit;
}

// 3. Activación de errores por si algo falla
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 4. Tu sistema de rutas (Switch) de siempre
$route = isset($_GET['url']) ? $_GET['url'] : 'login';

switch ($route) {
    case 'login':
        if (isset($_SESSION['usuario_id'])) {
            header('Location: index.php?url=dashboard');
            exit;
        }
        require_once __DIR__ . '/frontend/src/pages/login.php';
        break;

    case 'dashboard':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?url=login');
            exit;
        }
        require_once __DIR__ . '/frontend/src/pages/dashboard.php';
        break;

    case 'registro':
        require_once __DIR__ . '/frontend/src/pages/registro_cliente.php';
        break;

    case 'registro-key-master-admin-99':
        require_once __DIR__ . '/frontend/src/pages/registro_admin.php';
        break;

    default:
        require_once __DIR__ . '/frontend/src/pages/login.php';
        break;
}
?>