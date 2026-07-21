<?php
// index.php (Raíz del proyecto Axioma)

// 1. Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

// Sistema de rutas y matriz de permisos por roles
$route = isset($_GET['url']) ? $_GET['url'] : 'login';
$rolActual = $_SESSION['usuario_rol'] ?? '';

// Definir qué roles tienen permiso para cada ruta
$rutasPermitidas = [
    'login'             => ['Invitado', 'Administrador', 'Vendedor'], // Páginas públicas o controladas aparte
    'registro'          => ['Invitado', 'Administrador', 'Vendedor'],
    'dashboard'         => ['Administrador', 'Vendedor'],
    'clientes'          => ['Administrador', 'Vendedor'],
    'usuarios'          => ['Administrador'],
    'registro_vendedor' => ['Administrador'],
    'registrar_pijamas' => ['Administrador', 'Vendedor'],
    'registrar_pijama'  => ['Administrador', 'Vendedor'],
    'inventario'        => ['Administrador'],
    'lista_vendedores'  => ['Administrador'],
    'ver_inventario'    => ['Administrador', 'Vendedor']
];

// Validación 1: Verificar si la ruta existe
if (!array_key_exists($route, $rutasPermitidas)) {
    http_response_code(404);
    echo "<div style='text-align: center; margin-top: 50px;'><h2>404 - Página no encontrada</h2><a href='index.php?url=dashboard'>Volver</a></div>";
    exit;
}

// Validación 2: Si la ruta no es login/registro, exigir autenticación
if ($route !== 'login' && $route !== 'registro' && !isset($_SESSION['usuario_id'])) {
    header('Location: index.php?url=login');
    exit;
}

// Si ya está logueado y quiere ir al login, mandarlo al dashboard
if ($route === 'login' && isset($_SESSION['usuario_id'])) {
    header('Location: index.php?url=dashboard');
    exit;
}

// Validación 3: Control de acceso por roles
// Si la ruta requiere un rol específico que el usuario actual no posee:
if ($route !== 'login' && $route !== 'registro' && !in_array($rolActual, $rutasPermitidas[$route])) {
    http_response_code(403);
    echo "<div style='text-align: center; margin-top: 50px;'>
            <h2 style='color: #dc3545;'>Acceso Denegado</h2>
            <p>No tienes los permisos de Administrador necesarios para acceder a esta sección.</p>
            <a href='index.php?url=dashboard'>Volver al Dashboard</a>
          </div>";
    exit;
}

// 4. Cargador de vistas mediante switch adaptado
switch ($route) {
    case 'login':
        require_once __DIR__ . '/frontend/src/pages/login.php';
        break;

    case 'dashboard':
        if ($rolActual === 'Vendedor') {
            require_once __DIR__ . '/frontend/src/pages/dashboard_vendedor.php';
        } else {
            require_once __DIR__ . '/frontend/src/pages/dashboard.php';
        }
        break;

    case 'registro':
        require_once __DIR__ . '/frontend/src/pages/registro_cliente.php';
        break;

    case 'clientes':
        require_once __DIR__ . '/frontend/src/pages/clientes.php';
        break;

    case 'usuarios':
        require_once __DIR__ . '/frontend/src/pages/usuarios.php';
        break;

    case 'registro_vendedor':
        require_once __DIR__ . '/frontend/src/pages/registro_vendedor.php';
        break;

    case 'inventario':
        require_once __DIR__ . '/frontend/src/pages/inventario.php';
        break;

    case 'ver_inventario':
        require_once __DIR__ . '/frontend/src/pages/ver_inventario.php';
        break;

    case 'lista_vendedores':
        require_once __DIR__ . '/frontend/src/pages/lista_vendedores.php';
        break;

    case 'registrar_pijamas':
    case 'registrar_pijama':
        require_once __DIR__ . '/frontend/src/pages/registar_pijamas.php';
        break;

    default:
        require_once __DIR__ . '/frontend/src/pages/login.php';
        break;
}
?>