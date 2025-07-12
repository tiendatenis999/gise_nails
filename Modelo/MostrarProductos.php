<?php
require_once 'Conexion.php';
$conexion = new Conexion();
$conexion->abrir();

$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$categoria = isset($_POST['categoria']) ? trim($_POST['categoria']) : '';
$pagina = isset($_POST['pagina']) ? max(1, intval($_POST['pagina'])) : 1;
$por_pagina = isset($_POST['por_pagina']) ? intval($_POST['por_pagina']) : 6;
$offset = ($pagina - 1) * $por_pagina;

$sql_base = "SELECT productos.id as cod, productos.marca, productos.modelo, productos.tipo, productos.especificaciones, productos.precio, categorias.nombre AS nombre_categoria
        FROM productos
        JOIN categorias ON productos.id_categoria = categorias.id";

$where = [];
if ($nombre !== "") {
    $where[] = "(productos.marca LIKE '%" . $conexion->MySQLI()->real_escape_string($nombre) . "%' OR productos.modelo LIKE '%" . $conexion->MySQLI()->real_escape_string($nombre) . "%')";
}
if ($categoria !== "") {
    $where[] = "categorias.id = '" . $conexion->MySQLI()->real_escape_string($categoria) . "'";
}
$sql = $sql_base;
if (count($where) > 0) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

// Total de productos para paginación
$sql_count = "SELECT COUNT(*) as total FROM productos JOIN categorias ON productos.id_categoria = categorias.id";
if (count($where) > 0) {
    $sql_count .= " WHERE " . implode(" AND ", $where);
}
$conexion->consulta($sql_count);
$total = $conexion->obtenerResult()->fetch_assoc()['total'];

$sql .= " LIMIT $por_pagina OFFSET $offset";
$conexion->consulta($sql);
$result = $conexion->obtenerResult();
$filas = $result ? $result->num_rows : 0;

// Paginación ARRIBA del catálogo (fuera del grid)
$total_paginas = ceil($total / $por_pagina);
if ($total_paginas > 1) {
    echo '<div class="paginacion">';
    for ($i = 1; $i <= $total_paginas; $i++) {
        $active = $i == $pagina ? 'class="pagina-link active"' : 'class="pagina-link"';
        echo "<a href='#' $active data-pagina='$i'>$i</a> ";
    }
    echo '</div>';
}
?>

<div class="catalogo">
<?php
if ($filas >= 1) {
    while ($fila = $result->fetch_assoc()) {
        $imgSql = "SELECT ruta FROM imagenes_producto WHERE id_producto = " . intval($fila['cod']) . " LIMIT 1";
        $conexion->consulta($imgSql);
        $imgResult = $conexion->obtenerResult();
        $imagen = ($imgResult && $imgResult->num_rows > 0) ? $imgResult->fetch_assoc()['ruta'] : 'Vista/img/sin-imagen.png';
?>
    <div class="producto">
        <div class="img">
            <img src="<?php echo htmlspecialchars($imagen); ?>" alt="Imagen producto" style="max-width:150px;max-height:150px;">
        </div>
        <div class="info">
            <h3><?php echo htmlspecialchars($fila['marca']); ?></h3>
            <p><strong>Modelo:</strong> <?php echo htmlspecialchars($fila['modelo']); ?></p>
            <p><strong>Categoría:</strong> <?php echo htmlspecialchars($fila['nombre_categoria']); ?></p>
            <p><strong>Especificaciones:</strong> <?php echo nl2br(htmlspecialchars($fila['especificaciones'])); ?></p>
            <p><strong>Precio:</strong> $<?php echo number_format($fila['precio'], 2); ?></p>
        </div>
        <div class="controls">
            <button class="btn-ver-producto" data-id="<?php echo $fila['cod']; ?>">Ver producto</button>
        </div>
    </div>
<?php
    }
} else {
    echo '<h3 style="grid-column: 1/-1; text-align:center;">Producto no encontrado</h3>';
}
?>
</div>
<?php
$conexion->cerrar();
?>