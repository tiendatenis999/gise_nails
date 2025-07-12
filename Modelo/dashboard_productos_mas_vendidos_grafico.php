<?php
require_once 'Conexion.php';
$conexion = new Conexion();
$conexion->abrir();
$sql = "SELECT CONCAT(marca, ' ', modelo) as producto, SUM(pedidos.cantidad) as cantidad
        FROM pedidos
        JOIN productos ON pedidos.id_producto = productos.id
        GROUP BY pedidos.id_producto
        ORDER BY cantidad DESC
        LIMIT 5";
$conexion->consulta($sql);
$res = $conexion->obtenerResult();
$productos = [];
$cantidades = [];
while ($row = $res->fetch_assoc()) {
    $productos[] = $row['producto'];
    $cantidades[] = intval($row['cantidad']);
}
echo json_encode(['productos' => $productos, 'cantidades' => $cantidades]);
$conexion->cerrar();
?>