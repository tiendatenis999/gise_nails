<?php
require_once 'Conexion.php';
$conexion = new Conexion();
$conexion->abrir();
$sql = "SELECT CONCAT(marca, ' ', modelo) AS producto, SUM(cantidad) AS cantidad 
        FROM pedidos 
        JOIN productos ON pedidos.id_producto = productos.id 
        GROUP BY pedidos.id_producto 
        ORDER BY cantidad ASC LIMIT 1";
$conexion->consulta($sql);
$res = $conexion->obtenerResult();
$data = $res->fetch_assoc();
echo json_encode($data);
$conexion->cerrar();
?>