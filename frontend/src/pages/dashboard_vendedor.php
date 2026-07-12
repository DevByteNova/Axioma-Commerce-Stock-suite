<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Verificación de seguridad básica
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Vendedor') {
    header('Location: index.php?url=login');
    exit;
}

require_once __DIR__ . '/../../../backend/src/conexion.php';
$db = (new Database())->getConnection();

// Consultar cuántas ventas ha hecho este vendedor hoy
$stmt = $db->prepare("SELECT COUNT(*) as total FROM ventas WHERE ID_VENDEDOR = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$total_ventas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Vendedor - Axioma</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <?php require_once '../components/navbar.php'; ?>
    
    <div class="container">
        <h1>Panel de Vendedor</h1>
        <p>Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Vendedor'); ?></strong></p>

        <div class="stats-grid">
            <div class="card">
                <h3>Ventas Realizadas</h3>
                <p class="big-number"><?php echo $total_ventas; ?></p>
            </div>
        </div>

        <div class="dashboard-actions">
            <h2>Acciones Rápidas</h2>
            <a href="index.php?url=nueva_venta" class="btn btn-primary">Registrar Nueva Venta</a>
            <a href="index.php?url=historial_ventas" class="btn btn-secondary">Ver Mis Ventas</a>
            <a href="index.php?url=productos" class="btn btn-secondary">Consultar Catálogo</a>
        </div>
    </div>
</body>
</html>