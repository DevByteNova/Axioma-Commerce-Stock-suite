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
    <link rel="stylesheet" href="./frontend/src/public/css/styles.css">
    <style>
        /* Estilos específicos limpios para el dashboard */
        body {
            background-color: #f4f7f6;
            margin: 0;
            font-family: Arial, sans-serif;
            color: #333;
        }
        .dashboard-header {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .dashboard-main {
            padding: 30px;
            max-width: 900px;
            margin: 0 auto;
        }
        .btn-accion {
            display: inline-flex;
            align-items: center;
            padding: 10px 18px;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: opacity 0.2s;
        }
        .btn-accion:hover { opacity: 0.9; }
        .bg-blue { background-color: #007bff; }
        .bg-indigo { background-color: #6610f2; }
        .bg-purple { background-color: #6f42c1; }
        .bg-green { background-color: #28a745; }
        .bg-red { background-color: #dc3545; }
        
        .grid-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border: 1px solid #e1e1e1;
            text-decoration: none;
            color: inherit;
            display: block;
            transition: border-color 0.2s;
        }
        .stat-card.clickable:hover {
            border-color: #007bff;
        }
        .stat-card h2 {
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
            margin: 0 0 10px 0;
        }
        .stat-card p {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
            color: #222;
        }
        .seccion-tablas {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border: 1px solid #e1e1e1;
        }
        .lista-tablas {
            list-style: none;
            padding: 0;
            margin: 15px 0 0 0;
        }
        .lista-tablas li {
            background: #f9f9f9;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 10px;
            border: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .badge-tabla {
            background: #e7f1ff;
            color: #007bff;
            font-weight: bold;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <div style="min-height: 100vh; display: flex; flex-direction: column;">
        
        <header class="dashboard-header">
            <h1 style="font-size: 20px; margin: 0; color: #333;">Panel de Control - Axioma</h1>
            
            <div style="display: flex; align-items: center; gap: 20px;">
                <span style="font-size: 14px; color: #555;">
                    Bienvenido, <strong style="color: #007bff;"><?php echo htmlspecialchars($nombre_usuario); ?></strong>
                </span>
                
                <a href="index.php?action=logout" class="btn-accion bg-red" style="padding: 6px 12px; font-size: 13px;">
                    🔴 Cerrar Sesión
                </a>
            </div>
        </header>

        <main class="dashboard-main">
            <?php
            // Conexión única al principio del main
            require_once 'C:\xampp\htdocs\Axioma-Commerce-Stock-suite\backend\src\conexion.php'; 
            $database = new Database();
            $db = $database->getConnection();
            ?>

            <!-- BOTONES DE NAVEGACIÓN Y GESTIÓN -->
            <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 30px;">
                <a href="index.php?url=clientes" class="btn-accion bg-blue">
                    👥 Gestionar Clientes
                </a>

                <a href="index.php?url=ver_inventario" class="btn-accion bg-purple">
                    📋 Ver Inventario
                </a>

                <a href="index.php?url=registro_vendedor" class="btn-accion bg-green">
                    🧑‍💼 Registrar Vendedor
                </a>
                <a href="index.php?url=registrar_pijamas" class="btn-accion bg-green">
                    🛏️ Registrar Pijamas
                </a>
            </div>

            <!-- CONTADORES DE ESTADÍSTICAS -->
            <div class="grid-stats">
              <?php
                $tables = ['clientes', 'productos', 'ventas'];
                foreach ($tables as $t) {
                    $stmt = $db->query("SELECT COUNT(*) FROM $t");
                    $count = $stmt->fetchColumn();
                    
                    // Enlaces interactivos según la tabla
                    if ($t == 'clientes') {
                        $link = 'href="index.php?url=clientes"';
                        $claseClick = 'clickable';
                    } elseif ($t == 'productos') {
                        $link = 'href="index.php?url=ver_inventario"';
                        $claseClick = 'clickable';
                    } else {
                        $link = '';
                        $claseClick = '';
                    }

                    echo "
                    <a $link class='stat-card $claseClick'>
                        <h2>Total $t</h2>
                        <p>$count</p>
                    </a>";
                }
                ?>
            </div>

            <!-- LISTADO DE TABLAS DE LA BASE DE DATOS -->
            <div class="seccion-tablas">
                <h2 style="font-size: 18px; margin: 0 0 5px 0; color: #333;">Tablas en la Base de Datos</h2>
                <p style="color: #666; font-size: 14px; margin: 0 0 15px 0;">Lista de tablas disponibles en tu sistema:</p>
                
                <div style="background: #fafafa; border: 1px solid #eee; border-radius: 6px; padding: 15px;">
                    <ul class="lista-tablas">
                        <?php
                        try {
                            $query = "SHOW TABLES";
                            $stmt = $db->prepare($query);
                            $stmt->execute();
                            $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);

                            if (count($tablas) > 0) {
                                foreach ($tablas as $tabla) {
                                    echo "<li>";
                                    echo "<span class='badge-tabla'>TABLA</span>";
                                    echo "<span style='color: #444; font-weight: 500;'>" . htmlspecialchars($tabla) . "</span>";
                                    echo "</li>";
                                }
                            } else {
                                echo "<li style='color: #777; background: none; border: none;'>No hay tablas encontradas.</li>";
                            }
                        } catch (Exception $e) {
                            echo "<li style='color: red; background: none; border: none;'>Error de conexión: " . $e->getMessage() . "</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </main>

    </div>
</body>
</html>