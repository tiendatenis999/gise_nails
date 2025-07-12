<?php
require_once 'Conexion.php';
$conexion = new Conexion();
$conexion->abrir();

$where = [];
if (!empty($_POST['id'])) {
    $id = intval($_POST['id']);
    $where[] = "pedidos.id = $id";
}
if (!empty($_POST['cliente'])) {
    $cliente = $conexion->MySQLI()->real_escape_string($_POST['cliente']);
    $where[] = "usuarios.nombre LIKE '%$cliente%'";
}
if (!empty($_POST['fecha'])) {
    $fecha = $conexion->MySQLI()->real_escape_string($_POST['fecha']);
    $where[] = "DATE(pedidos.fecha) = '$fecha'";
}
if (!empty($_POST['estado'])) {
    $estado = $conexion->MySQLI()->real_escape_string($_POST['estado']);
    $where[] = "pedidos.estado = '$estado'";
}

$sql = "SELECT pedidos.id, usuarios.nombre AS cliente, 
               CONCAT(productos.marca, ' ', productos.modelo) AS producto, 
               pedidos.cantidad, pedidos.fecha, pedidos.estado
        FROM pedidos
        JOIN usuarios ON pedidos.id_usuario = usuarios.id
        JOIN productos ON pedidos.id_producto = productos.id";
if (count($where) > 0) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY pedidos.id DESC";
$conexion->consulta($sql);
$result = $conexion->obtenerResult();

if ($result && $result->num_rows > 0) {
    while ($fila = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$fila['id']}</td>
                <td>{$fila['cliente']}</td>
                <td>{$fila['producto']}</td>
                <td>{$fila['cantidad']}</td>
                <td>{$fila['fecha']}</td>
                <td>
                  <select class='select-estado-pedido' data-id='{$fila['id']}'>
                    <option value='Pendiente' " . ($fila['estado'] == 'Pendiente' ? 'selected' : '') . ">Pendiente</option>
                    <option value='Enviado' " . ($fila['estado'] == 'Enviado' ? 'selected' : '') . ">Enviado</option>
                    <option value='Entregado' " . ($fila['estado'] == 'Entregado' ? 'selected' : '') . ">Entregado</option>
                    <option value='Cancelado' " . ($fila['estado'] == 'Cancelado' ? 'selected' : '') . ">Cancelado</option>
                  </select>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6' style='text-align:center;'>No hay pedidos registrados</td></tr>";
}
$conexion->cerrar();
?>