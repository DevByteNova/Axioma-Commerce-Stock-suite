<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Seguridad: Si no es Admin, para afuera
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'Administrador') {
    header("Location: index.php?url=login");
    exit;
}

$nombre_usuario = $_SESSION['usuario_name'] ?? 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Axioma</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="min-h-screen flex flex-col">
        
        <header class="bg-white shadow-md border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Panel de Control - Axioma</h1>
            
            <div class="flex items-center space-x-6">
                <span class="text-sm font-medium text-gray-700">
                    Bienvenido, <strong class="text-blue-600"><?php echo htmlspecialchars($nombre_usuario); ?></strong>
                </span>
                
                <a href="index.php?action=logout" class="bg-red-600 hover:bg-red-700 text-white font-bold text-sm px-4 py-2 rounded-lg shadow transition duration-200">
                    🔴 Cerrar Sesión
                </a>
            </div>
        </header>

        <main class="flex-1 p-6">
            <?php
            // 1. Conexión única al principio del main
            require_once 'C:/xampp/htdocs/project/Axioma/backend/src/conexion.php'; 
            $database = new Database();
            $db = $database->getConnection();
            ?>

            <div class="max-w-4xl mx-auto mb-8">
                <a href="http://localhost/project/Axioma/frontend/src/pages/clientes.php" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition duration-200">
                    <span class="mr-2">👥</span> Gestionar Clientes
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 max-w-4xl mx-auto">
              <?php
                $tables = ['clientes', 'productos', 'ventas'];
                foreach ($tables as $t) {
                    $stmt = $db->query("SELECT COUNT(*) FROM $t");
                    $count = $stmt->fetchColumn();
                    
                    $link = ($t == 'clientes') ? 'href="clientes.php"' : '';
                    $cursor = ($t == 'clientes') ? 'cursor-pointer hover:border-blue-500' : 'cursor-default';

                    echo "
                    <a $link class='block bg-white p-6 rounded-xl shadow-sm border border-gray-200 transition-all $cursor'>
                        <h2 class='text-gray-500 text-xs uppercase font-bold tracking-wider'>Total $t</h2>
                        <p class='text-3xl font-bold text-gray-800 mt-2'>$count</p>
                    </a>";
                }
                ?>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 max-w-4xl mx-auto mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Tablas en la Base de Datos</h2>
                <p class="text-gray-600 mb-6">Lista de tablas disponibles en tu sistema:</p>
                
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                    <ul class="space-y-3">
                        <?php
                        try {
                            $query = "SHOW TABLES";
                            $stmt = $db->prepare($query);
                            $stmt->execute();
                            $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);

                            if (count($tablas) > 0) {
                                foreach ($tablas as $tabla) {
                                    echo "<li class='flex items-center space-x-3 bg-white p-3 rounded-md shadow-sm border border-gray-100'>";
                                    echo "  <span class='bg-blue-100 text-blue-600 font-bold px-2.5 py-1 rounded text-xs'>TABLA</span>";
                                    echo "  <span class='text-gray-700 font-medium'>" . htmlspecialchars($tabla) . "</span>";
                                    echo "</li>";
                                }
                            } else {
                                echo "<li class='text-gray-500'>No hay tablas encontradas.</li>";
                            }
                        } catch (Exception $e) {
                            echo "<li class='text-red-500'>Error de conexión: " . $e->getMessage() . "</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </main>

    </div>
</body>
</html>