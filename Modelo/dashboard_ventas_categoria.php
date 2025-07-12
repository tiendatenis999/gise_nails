<?php
require_once 'Conexion.php';
$conexion = new Conexion();
$conexion->abrir();
$sql = "SELECT categorias.nombre as categoria, SUM(pedidos.cantidad) as cantidad
        FROM pedidos
        JOIN productos ON pedidos.id_producto = productos.id
        JOIN categorias ON productos.id_categoria = categorias.id
        GROUP BY categorias.id
        ORDER BY cantidad DESC";
$conexion->consulta($sql);
$res = $conexion->obtenerResult();
$categorias = [];
$cantidades = [];
while ($row = $res->fetch_assoc()) {
    $categorias[] = $row['categoria'];
    $cantidades[] = intval($row['cantidad']);
}
echo json_encode(['categorias' => $categorias, 'cantidades' => $cantidades]);
$conexion->cerrar();
?>