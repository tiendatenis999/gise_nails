<?php
class GestorProductos
{

    public function CrearProducto(Productos $productos, $imagenesRutas = [])
    {
        $Conexion = new Conexion();
        $Conexion->abrir();
        $marca = $productos->obtenerMarca();
        $modelo = $productos->obtenerModelo();
        $tipo = $productos->obtenerTipo();
        $especificaciones = $productos->obtenerEspecificaciones();
        $precio = $productos->obtenerPrecio();
        $categoria = $productos->obtenerIdCategoria();

        // Insertar producto
        $sql = "INSERT INTO productos (marca, modelo, tipo, especificaciones, precio, id_categoria) 
                VALUES ('$marca','$modelo','$tipo','$especificaciones','$precio','$categoria')";
        $Conexion->consulta($sql);

        // Obtener el id del producto recién insertado
        $id_producto = $Conexion->MySQLI()->insert_id;

        // Insertar imágenes asociadas
        foreach ($imagenesRutas as $ruta) {
            $sqlImg = "INSERT INTO imagenes_producto (id_producto, ruta) VALUES ('$id_producto', '$ruta')";
            $Conexion->consulta($sqlImg);
        }

        $result = $Conexion->obtenerFilasAfectadas();
        $Conexion->cerrar();
        return $result;
    }
    public function editarProducto($id, $marca, $modelo, $tipo, $especificaciones, $precio, $categoria, $imagenesRutas = [])
    {
        $Conexion = new Conexion();
        $Conexion->abrir();

        $sql = "UPDATE productos SET 
        marca='$marca', 
        modelo='$modelo', 
        tipo='$tipo', 
        especificaciones='$especificaciones', 
        precio='$precio', 
        id_categoria='$categoria' 
        WHERE id='$id'";
        $Conexion->consulta($sql);

        // Si hay imágenes nuevas, borra las anteriores y agrega las nuevas
        if (!empty($imagenesRutas)) {
            $Conexion->consulta("DELETE FROM imagenes_producto WHERE id_producto='$id'");
            foreach ($imagenesRutas as $ruta) {
                $sqlImg = "INSERT INTO imagenes_producto (id_producto, ruta) VALUES ('$id', '$ruta')";
                $Conexion->consulta($sqlImg);
            }
        }

        $result = $Conexion->obtenerFilasAfectadas();
        $Conexion->cerrar();
        return $result;
    }
    public function eliminarProducto($id)
    {
        $Conexion = new Conexion();
        $Conexion->abrir();
        $sql = "DELETE FROM productos WHERE id='$id'";
        $Conexion->consulta($sql);
        $result = $Conexion->obtenerFilasAfectadas();
        $Conexion->cerrar();
        return $result;
    }
}
