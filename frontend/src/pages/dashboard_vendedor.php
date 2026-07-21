<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'Vendedor') {
    header("Location: index.php?url=login");
    exit;
}

$nombre_usuario = $_SESSION['usuario_name'] ?? 'Vendedor';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Vendedor - Axioma</title>
    <link rel="stylesheet" href="./frontend/src/public/css/styles.css">
</head>
<body>
    <div class="container" style="max-width: 900px; margin: 24px auto;">
        <div class="header-actions">
            <h1>Panel de Vendedor - Axioma</h1>
            <a href="index.php?action=logout" class="btn btn-danger">🔴 Cerrar Sesión</a>
        </div>

        <div class="card" style="padding: 24px; margin-bottom: 20px;">
            <p style="margin-bottom: 12px; color: #555;">
                Bienvenido, <strong style="color: var(--secondary-color);"><?php echo htmlspecialchars($nombre_usuario); ?></strong>
            </p>
            <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                <a href="index.php?url=ver_inventario" class="btn btn-primary" style="background: linear-gradient(135deg, #6f42c1, #8b5cf6);">📋 Ver Inventario y Stock</a>
                <a href="index.php?url=clientes" class="btn btn-primary" style="background: linear-gradient(135deg, #3498db, #2563eb);">👥 Gestionar Clientes</a>
                <a href="index.php?url=registrar_pijamas" class="btn btn-primary" style="background: linear-gradient(135deg, #16a34a, #22c55e);">🛏️ Registrar Pijamas</a>
            </div>
        </div>

        <div class="card" style="padding: 24px;">
            <h2 style="margin-bottom: 10px;">Área de Operaciones</h2>
            <p style="color: #666;">Utiliza los botones superiores para consultar el inventario disponible o gestionar los clientes registrados en el sistema.</p>
        </div>
    </div>
</body>
</html>