<?php
class InicioSesion
{
    public function login($correo, $clave)
    {
        $Conexion = new Conexion();
        $Conexion->abrir();
        $sql = "SELECT * from usuarios where correo='$correo' and contraseña='$clave' AND rol='admin'";
        $Conexion->consulta($sql);
        $result = $Conexion->obtenerFilasAfectadas();
        $Conexion->cerrar();
        return $result;
    }
    public function CrearPedido(Pedidos $pedido)
    {
        $Conexion = new Conexion();
        $Conexion->abrir();
        $usuarioId = $pedido->obtenerUsuarioId();
        $productoId = $pedido->obtenerProductoId();
        $cantidad = $pedido->obtenerCantidad();
        $fechaPedido = $pedido->obtenerFechaPedido();
        $estado = $pedido->obtenerEstado();
        $sql = "INSERT INTO pedidos (id_usuario, id_producto, cantidad, fecha, estado) VALUES ('$usuarioId', '$productoId', '$cantidad', '$fechaPedido', '$estado')";
        $Conexion->consulta($sql);
        $result = $Conexion->obtenerFilasAfectadas();
        $Conexion->cerrar();
        return $result;
    }
}
