<?php
session_start();
require_once 'C:/xampp/htdocs/project/Axioma/backend/src/conexion.php';
$database = new Database();
$db = $database->getConnection();
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Axioma</title>
    <link rel="stylesheet" href="/project/Axioma/frontend/src/public/css/styles.css">
</head>
<body>
    <?php require_once 'C:/xampp/htdocs/project/Axioma/frontend/src/components/navbar.php'; ?>
    <div class="container">
        <h1>Catalogo de Productos</h1>
        <a href="nuevo_producto.php" class="btn btn-primary">Agregar Nuevo Producto</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <<tbody>
            <?php
            $stmt = $db->query("SELECT ID_PRODUCTO, NOMBRE, DESCRIPCION, PRECIO FROM productos");
            
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['ID_PRODUCTO'] . "</td>";
                    echo "<td><strong>" . htmlspecialchars($row['NOMBRE']) . "</strong></td>";
                    echo "<td>" . htmlspecialchars($row['DESCRIPCION']) . "</td>";
                    echo "<td>$" . number_format($row['PRECIO'], 2) . "</td>";
                    // Agregué una celda vacía para "Stock" ya que no tienes la columna en el SELECT
                    echo "<td>N/A</td>"; 
                    echo "<td>
                            <a href='editar_producto.php?id=" . $row['ID_PRODUCTO'] . "'>Editar</a> | 
                            <a href='eliminar_producto.php?id=" . $row['ID_PRODUCTO'] . "' onclick='return confirm(\"¿Seguro?\")'>Eliminar</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No hay productos disponibles.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>