<?php
require_once 'Conexion.php';
$conexion = new Conexion();
$conexion->abrir();
$sql = "SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes, COUNT(*) AS cantidad FROM pedidos GROUP BY mes ORDER BY mes";
$conexion->consulta($sql);
$res = $conexion->obtenerResult();
$meses = [];
$cantidades = [];
while ($row = $res->fetch_assoc()) {
    $meses[] = $row['mes'];
    $cantidades[] = $row['cantidad'];
}
echo json_encode(['meses' => $meses, 'cantidades' => $cantidades]);
$conexion->cerrar();
?>