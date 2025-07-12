<?php
require_once 'Conexion.php';
$conexion = new Conexion();
$conexion->abrir();
$id = intval($_POST['id']);
$sql = "SELECT * FROM productos WHERE id = $id";
$conexion->consulta($sql);
$result = $conexion->obtenerResult();
if ($result && $row = $result->fetch_assoc()) {
    echo json_encode($row);
}
$conexion->cerrar();
?>