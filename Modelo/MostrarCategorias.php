<?php
require_once 'Conexion.php';
$conexion = new Conexion();
$conexion->abrir();

$sql = "SELECT id, nombre FROM categorias";
$conexion->consulta($sql);
$result = $conexion->obtenerResult();

echo '<option value="">Todas las categorías</option>';
if ($result && $result->num_rows > 0) {
    while ($fila = $result->fetch_assoc()) {
        echo '<option value="' . $fila['id'] . '">' . $fila['nombre'] . '</option>';
    }
}

$conexion->cerrar();
?>