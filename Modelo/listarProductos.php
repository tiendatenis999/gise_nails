<?php
require_once 'Conexion.php'; // <-- Agrega esta línea

$conexion = new Conexion();
$conexion->abrir();

$where = [];
if (!empty($_POST['marca'])) {
    $marca = $conexion->MySQLI()->real_escape_string($_POST['marca']);
    $where[] = "productos.marca LIKE '%$marca%'";
}
if (!empty($_POST['tipo'])) {
    $tipo = $conexion->MySQLI()->real_escape_string($_POST['tipo']);
    $where[] = "productos.tipo = '$tipo'";
}
if (!empty($_POST['categoria'])) {
    $categoria = $conexion->MySQLI()->real_escape_string($_POST['categoria']);
    $where[] = "categorias.id = '$categoria'";
}

$sql = "SELECT productos.id, productos.marca, productos.modelo, productos.tipo, productos.especificaciones, productos.precio, categorias.nombre AS categoria 
        FROM productos 
        JOIN categorias ON productos.id_categoria = categorias.id";
if (count($where) > 0) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$conexion->consulta($sql);
$result = $conexion->obtenerResult();

if ($result && $result->num_rows > 0) {
    while ($fila = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$fila['id']}</td>
                <td>{$fila['marca']}</td>
                <td>{$fila['modelo']}</td>
                <td>{$fila['tipo']}</td>
                <td>{$fila['especificaciones']}</td>
                <td>{$fila['precio']}</td>
                <td>{$fila['categoria']}</td>
                <td>
                    <button class='btn-editar' data-producto='{$fila['id']}' title='Editar'>
                        <i class='fas fa-edit'></i>
                    </button>
                    <button class='btn-eliminar' data-producto='{$fila['id']}' title='Eliminar'>
                        <i class='fas fa-trash-alt'></i>
                    </button>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='8' style='text-align:center;'>No hay productos registrados</td></tr>";
}
$conexion->cerrar();
