<?php

use LDAP\Result;

class Controlador
{
    public function verpagina($ruta)
    {
        require_once $ruta;
    }

    public function login($correo, $clave)
    {
        $login = new InicioSesion();
        $result = $login->login($correo, $clave);
        if ($result > 0) {
            $_SESSION["email"] = $correo;
            $_SESSION["clave"] = $clave;
            echo "ok";
        } else {
            echo "error";
        }
        exit();
    }
    public function guardarPedido($nombre, $correo, $telefono, $direccion, $productoId, $cantidad)
    {
        $conexion = new Conexion();
        $conexion->abrir();

        $sql_usuario = "SELECT id FROM usuarios WHERE correo = '$correo' LIMIT 1";
        $conexion->consulta($sql_usuario);
        $res_usuario = $conexion->obtenerResult();
        if ($res_usuario && $res_usuario->num_rows > 0) {
            $row = $res_usuario->fetch_assoc();
            $usuarioId = $row['id'];
        } else {
            $sql_insert = "INSERT INTO usuarios (nombre, correo, contraseña, rol) VALUES ('$nombre', '$correo', '', 'cliente')";
            $conexion->consulta($sql_insert);
            $usuarioId = $conexion->MySQLI()->insert_id;
        }

        $fechaPedido = date('Y-m-d H:i:s');
        $estado = 'Pendiente';
        $pedido = new Pedidos(null, $usuarioId, $productoId, $cantidad, $fechaPedido, $estado);
        $pedidos = new InicioSesion();

        $conexion->cerrar();

        $result = $pedidos->CrearPedido($pedido);
        if ($result > 0) {
            echo "<script>alert('Pedido agregado con Exito');
                window.location='index.php?accion=vista'</script>";
        } else {
            echo "<script>alert('Pedido no se guardó');
                window.location='index.php?accion=vista'</script>";
        }
    }
        public function crearProducto($marca, $modelo, $tipo, $especificaciones, $precio, $categoria, $imagenesRutas = [])
    {
        $productos = new Productos($marca, $modelo, $tipo, $especificaciones, $precio, $categoria);
        $GestorProductos = new GestorProductos();
        $result = $GestorProductos->crearProducto($productos, $imagenesRutas);
        if ($result > 0) {
            echo "ok";
        } else {
            echo "error";
        }
    }
    public function editarProducto($id, $marca, $modelo, $tipo, $especificaciones, $precio, $categoria, $imagenesRutas = [])
    {
        $GestorProductos = new GestorProductos();
        $result = $GestorProductos->editarProducto($id, $marca, $modelo, $tipo, $especificaciones, $precio, $categoria, $imagenesRutas);
        if ($result > 0) {
            echo "ok";
        } else {
            echo "error";
        }
    }
    public function eliminarProducto($id)
    {
        $GestorProductos = new GestorProductos();
        $result = $GestorProductos->eliminarProducto($id);
        if ($result > 0) {
            echo "ok";
        } else {
            echo "error";
        }
    }
    public function crearCategoria($nombre)
    {
        $Conexion = new Conexion();
        $Conexion->abrir();
        $sql = "INSERT INTO categorias (nombre) VALUES ('$nombre')";
        $Conexion->consulta($sql);
        $result = $Conexion->obtenerFilasAfectadas();
        $Conexion->cerrar();
        if ($result > 0) {
            echo "ok";
        } else {
            echo "error";
        }
    }
    public function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = array();
        session_unset();
        session_destroy();
        header("Location: index.php?accion=vista");
        exit();
    }
}
