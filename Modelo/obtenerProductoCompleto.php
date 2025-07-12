<?php
require_once 'Conexion.php';
$conexion = new Conexion();
$conexion->abrir();

$id = intval($_POST['id']);
$sql = "SELECT p.id, p.marca, p.modelo, p.tipo, p.especificaciones, p.precio, c.nombre as categoria
        FROM productos p
        JOIN categorias c ON p.id_categoria = c.id
        WHERE p.id = $id LIMIT 1";
$conexion->consulta($sql);
$res = $conexion->obtenerResult();
$producto = $res && $res->num_rows > 0 ? $res->fetch_assoc() : null;

// Obtener imágenes
$imagenes = [];
if ($producto) {
    $conexion->consulta("SELECT ruta FROM imagenes_producto WHERE id_producto = $id");
    $imgRes = $conexion->obtenerResult();
    while ($imgRes && $img = $imgRes->fetch_assoc()) {
        $imagenes[] = $img['ruta'];
    }
    if (empty($imagenes)) {
        $imagenes[] = 'Vista/img/sin-imagen.png';
    }
}

echo json_encode([
    "producto" => $producto,
    "imagenes" => $imagenes
]);
$conexion->cerrar();
?>