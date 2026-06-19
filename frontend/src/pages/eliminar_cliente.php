<?php
session_start();
require_once 'C:/xampp/htdocs/project/Axioma/backend/src/conexion.php';
$db = (new Database())->getConnection();

if (!empty($_GET['id'])) {
    $db = (new Database())->getConnection();

    $stmt = $db->prepare("DELETE FROM clientes WHERE ID_CLIENTE = ?");
    $stmt->execute([$_GET['id']]);
}
header("Location: clientes.php");
exit;