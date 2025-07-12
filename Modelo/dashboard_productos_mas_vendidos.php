<?php
require_once 'Conexion.php';
$conexion = new Conexion();
$conexion->abrir();
$sql = "SELECT usuarios.nombre, usuarios.correo, COUNT(pedidos.id) AS pedidos 
        FROM pedidos 
        JOIN usuarios ON pedidos.id_usuario = usuarios.id 
        GROUP BY pedidos.id_usuario 
        ORDER BY pedidos DESC LIMIT 1";
$conexion->consulta($sql);
$res = $conexion->obtenerResult();
$data = $res->fetch_assoc();
echo json_encode($data);
$conexion->cerrar();
?>