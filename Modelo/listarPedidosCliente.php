<?php
session_start();
require_once 'Conexion.php';
if (!isset($_SESSION["cliente_id"])) {
    exit;
}
$cliente_id = intval($_SESSION["cliente_id"]);
$conexion = new Conexion();
$conexion->abrir();

$where = ["pedidos.id_usuario = $cliente_id"];
if (!empty($_GET['producto'])) {
    $producto = $conexion->MySQLI()->real_escape_string($_GET['producto']);
    $where[] = "(productos.marca LIKE '%$producto%' OR productos.modelo LIKE '%$producto%')";
}
if (!empty($_GET['estado'])) {
    $estado = $conexion->MySQLI()->real_escape_string($_GET['estado']);
    $where[] = "pedidos.estado = '$estado'";
}

$sql = "SELECT pedidos.id, CONCAT(productos.marca, ' ', productos.modelo) AS producto, pedidos.cantidad, pedidos.fecha, pedidos.estado
        FROM pedidos
        JOIN productos ON pedidos.id_producto = productos.id
        WHERE " . implode(" AND ", $where) . "
        ORDER BY pedidos.id DESC";
$conexion->consulta($sql);
$result = $conexion->obtenerResult();

if ($result && $result->num_rows > 0) {
    while ($fila = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$fila['id']}</td>
                <td>{$fila['producto']}</td>
                <td>{$fila['cantidad']}</td>
                <td>{$fila['fecha']}</td>
                <td>{$fila['estado']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5' style='text-align:center;'>No tienes pedidos registrados</td></tr>";
}
$conexion->cerrar();
?>