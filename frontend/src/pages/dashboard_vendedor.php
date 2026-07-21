<?php
// dashboard_vendedor.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Vendedor - Axioma</title>
    <link rel="stylesheet" href="frontend/public/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenido, Vendedor: <?php echo htmlspecialchars($_SESSION['usuario_name'] ?? 'Usuario'); ?></h1>
        <p>Este es tu panel de control de ventas.</p>
        
        <nav>
            <a href="index.php?url=nueva_venta">Registrar Nueva Venta</a> |
            <a href="index.php?action=logout">Cerrar Sesión</a>
        </nav>
    </div>
</body>
</html>