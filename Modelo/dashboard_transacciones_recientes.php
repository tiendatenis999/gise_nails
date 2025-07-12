<?php
require_once 'Conexion.php';
$conexion = new Conexion();
$conexion->abrir();
$sql = "SELECT pedidos.id, CONCAT(productos.marca, ' ', productos.modelo) as descripcion, categorias.nombre as categoria, pedidos.fecha, pedidos.cantidad, pedidos.estado, productos.precio
        FROM pedidos
        JOIN productos ON pedidos.id_producto = productos.id
        JOIN categorias ON productos.id_categoria = categorias.id
        ORDER BY pedidos.id DESC
        LIMIT 7";
$conexion->consulta($sql);
$res = $conexion->obtenerResult();
$estados = [
    "Pendiente" => "pendiente",
    "Enviado" => "enviado",
    "Entregado" => "completado",
    "Cancelado" => "cancelado"
];
while ($row = $res->fetch_assoc()) {
    $monto = $row['cantidad'] * $row['precio'];
    $badge = isset($estados[$row['estado']]) ? $estados[$row['estado']] : "pendiente";
    echo "<tr>
        <td>{$row['descripcion']}</td>
        <td>{$row['categoria']}</td>
        <td>{$row['fecha']}</td>
        <td>$" . number_format($monto, 2) . "</td>
        <td><span class='badge {$badge}'>" . ucfirst($row['estado']) . "</span></td>
    </tr>";
}
$conexion->cerrar();
?>