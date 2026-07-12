<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../../backend/src/conexion.php';
$db = (new Database())->getConnection();

// Traemos las ventas unidas con facturas, clientes y productos
$sql = "SELECT f.FECHA, c.NOMBRE as cliente, p.NOMBRE as producto 
        FROM ventas v
        JOIN facturas f ON v.ID_FACTURA = f.ID_FACTURA
        LEFT JOIN clientes c ON f.ID_CLIENTE = c.ID_CLIENTE
        JOIN productos p ON v.ID_PRODUCTO = p.ID_PRODUCTO
        WHERE v.ID_VENDEDOR = ?
        ORDER BY f.FECHA DESC";

$stmt = $db->prepare($sql);
$stmt->execute([$_SESSION['usuario_id']]);
?>
<table>
    <thead>
        <tr><th>Fecha</th><th>Cliente</th><th>Producto</th></tr>
    </thead>
    <tbody>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo $row['FECHA']; ?></td>
            <td><?php echo $row['cliente']; ?></td>
            <td><?php echo $row['producto']; ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>