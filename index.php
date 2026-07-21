<?php
// index.php (Raíz del proyecto Axioma)

// 1. Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
//echo "Mi rol actual en la sesión es: " . ($_SESSION['usuario_rol'] ?? 'No definido'); 
//die();

// 2. Logout
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

// 3. Activación de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Sistema de rutas
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
        if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'Vendedor') {
            require_once __DIR__ . '/frontend/src/pages/dashboard_vendedor.php';
        } else {
            require_once __DIR__ . '/frontend/src/pages/dashboard.php';
        }
        break;

    case 'registro':
        require_once __DIR__ . '/frontend/src/pages/registro_cliente.php';
        break;

    case 'clientes':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?url=login');
            exit;
        }
        require_once __DIR__ . '/frontend/src/pages/clientes.php';
        break;

    case 'registro_vendedor':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?url=login');
            exit;
        }
        require_once __DIR__ . '/frontend/src/pages/registro_vendedor.php';
        break;

    default:
        require_once __DIR__ . '/frontend/src/pages/login.php';
        break;
}
?>