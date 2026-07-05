<?php
session_start();
if(!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'Administrador') {
    header("Location: index.php?url=login");
    exit;
}

require_once 'C:/xampp/htdocs/project/Axioma/backend/src/conexion.php';
$database = new Database();
$db = $database->getConnection();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes - Axioma</title>
    <link rel="stylesheet" href="../public/css/styles.css?v=1.1">
</head>
<body>

<?php require_once '../components/header.php'; ?>
    <div class="container">
         <a href="/project/Axioma/index.php?url=dashboard">← Volver al Dashboard</a>

        <div class="header-actions">
            <h1>Lista de Clientes</h1>
            <a href="nuevo_cliente.php" class="btn btn-primary">
                ➕ Agregar Cliente
            </a>
        </div>

        <div class="busqueda-container">
            <input type="text" id="busqueda" placeholder="Buscar cliente...">
        </div>

        <table>
            <thead>
                <tr>
                    <th>NOMBRE COMPLETO</th>
                    <th>EMAIL</th>
                    <th>DIRECCIÓN</th>
                    <th>TELÉFONO</th>
                </tr>
            </thead>
<tbody id="cuerpoTabla">    
                
    <?php
    // 1. Pones esta línea AQUÍ, donde antes tenías el SELECT *
    $stmt = $db->query("SELECT ID_CLIENTE, NOMBRE, APELLIDOS, email, DIRECCION, TELEFONO FROM clientes");

    // 2. Luego verificas los resultados con el mismo bloque de antes
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr class='border-b hover:bg-gray-50'>";
            
            // 3. Y aquí accedes a los datos usando los nombres exactos
            echo "<td class='px-6 py-4 text-sm text-gray-900'>" . 
                 htmlspecialchars(($row['NOMBRE'] ?? '').' '.($row['APELLIDOS'] ?? '')) . "</td>";
            
            echo "<td class='px-6 py-4 text-sm text-gray-500'>" . 
                 htmlspecialchars($row['email'] ?? '') . "</td>";
            
            echo "<td class='px-6 py-4 text-sm text-gray-500'>" . 
                 htmlspecialchars($row['DIRECCION'] ?? '') . "</td>";
            
            echo "<td class='px-6 py-4 text-sm text-gray-500'>" . 
                 htmlspecialchars($row['TELEFONO'] ?? '') . "</td>";
            
            echo "</tr>";

        echo "<td class='px-6 py-4 text-sm font-medium'>";
            echo "<a href='editar_cliente.php?id=" . $row['ID_CLIENTE'] . "' class='text-blue-600 hover:text-blue-900 mr-3'>Editar</a>";
            echo "<a href='eliminar_cliente.php?id=" . $row['ID_CLIENTE'] . "' class='text-red-600 hover:text-red-900' onclick='return confirm(\"¿Estás seguro?\")'>Eliminar</a>";
        echo "</td>";

    echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4' class='px-6 py-4 text-center text-gray-500'>No hay clientes registrados.</td></tr>";
    }
    ?>
</tbody>
            </table>
        </div>
    </div>

    <script>
        const inputBusqueda = document.getElementById('busqueda');
        const cuerpoTabla = document.getElementById('cuerpoTabla');

        inputBusqueda.addEventListener('keyup', function(){
            const filtro = inputBusqueda.value.toLowerCase();
            const filas = cuerpoTabla.getElementsByTagName('tr');

            for (let i = 0; i < filas.length; i++) {
                const textoFila = filas[i].textContent.toLowerCase();
                filas[i].style.display = textoFila.includes(filtro) ? '' : 'none';
            }
        });
    </script>
</body>
</html>