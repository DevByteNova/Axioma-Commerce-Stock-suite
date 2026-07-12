<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Seguridad: Si no es Admin, para afuera
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'Administrador') {
    header("Location: ../../../index.php?url=login");
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
    <link rel="stylesheet" href="/project/Axioma/frontend/src/public/css/styles.css">
</head>
<body class="page-body dashboard-body">
    <div class="page-shell">
        <header class="page-header dashboard-header">
            <h1>Panel de Control - Axioma</h1>

            <div class="header-actions">
                <span class="welcome-text">
                    Bienvenido, <strong><?php echo htmlspecialchars($nombre_usuario); ?></strong>
                </span>

                <a href="index.php?action=logout" class="btn btn-danger">
                    Cerrar Sesión
                </a>
            </div>
        </header>

        <main class="dashboard-main">
            <?php
            require_once 'C:/xampp/htdocs/project/Axioma/backend/src/conexion.php';
            $database = new Database();
            $db = $database->getConnection();
            ?>

            <div class="dashboard-actions">
                <a href="frontend/src/pages/clientes.php" class="btn btn-primary btn-large">
                    Gestionar Clientes
                </a>
            </div>

            <div class="stats-grid">
                <?php
                $tables = ['clientes', 'productos', 'ventas'];
                foreach ($tables as $t) {
                    $stmt = $db->query("SELECT COUNT(*) FROM $t");
                    $count = $stmt->fetchColumn();

                    $link = ($t == 'clientes') ? 'href="frontend/src/pages/clientes.php"' : '';
                    $cursor = ($t == 'clientes') ? 'stat-card-link' : 'stat-card-disabled';

                    echo "
                    <a $link class='stat-card $cursor'>
                        <h2>Total $t</h2>
                        <p>$count</p>
                    </a>";
                }
                ?>
            </div>

            <section class="panel-card">
                <h2>Tablas en la Base de Datos</h2>
                <p>Lista de tablas disponibles en tu sistema:</p>

                <div class="table-list">
                    <ul>
                        <?php
                        try {
                            $query = "SHOW TABLES";
                            $stmt = $db->prepare($query);
                            $stmt->execute();
                            $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);

                            if (count($tablas) > 0) {
                                foreach ($tablas as $tabla) {
                                    echo "<li class='table-item'>";
                                    echo "  <span class='table-badge'>TABLA</span>";
                                    echo "  <span>" . htmlspecialchars($tabla) . "</span>";
                                    echo "</li>";
                                }
                            } else {
                                echo "<li class='empty-state'>No hay tablas encontradas.</li>";
                            }
                        } catch (Exception $e) {
                            echo "<li class='error-state'>Error de conexión: " . $e->getMessage() . "</li>";
                        }
                        ?>
                    </ul>
                </div>
            </section>
        </main>
    </div>
</body>
</html>