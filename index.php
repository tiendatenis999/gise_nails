<?php
require_once "Controlador/Controlador.php";
require_once "Modelo/Conexion.php";
require_once "Modelo/InicioSesion.php";
require_once "Modelo/productos.php";
require_once "Modelo/pedidos.php";
require_once "Modelo/gestorProductos.php";


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$controlador = new Controlador();
$accion = isset($_GET["accion"]) ? $_GET["accion"] : null;

switch ($accion) {
    case "vista":
        $controlador->verpagina('Vista/html/index.html');
        break;

    case "zonaAdmin":
        $controlador->verpagina('Vista/html/loginAdmin.html');
        break;

    case "Administradores":
    case "productos":
    case "pedidos":
    case "categorias":
        // Verifica si el usuario es admin
        if (!isset($_SESSION["email"]) || !isset($_SESSION["clave"])) {
            header("Location: index.php?accion=zonaAdmin");
            exit;
        }
        // Muestra la página correspondiente
        if ($accion === "Administradores" || $accion === "productos") {
            $controlador->verpagina('Vista/html/administrador.html');
        } elseif ($accion === "pedidos") {
            $controlador->verpagina('Vista/html/pedidos.html');
        } elseif ($accion === "categorias") {
            $controlador->verpagina('Vista/html/categorias.html');
        }
        break;

    case "iniciarCliente":
        $controlador->verpagina('Vista/html/loginCliente.html');
        break;

    case "registro":
        $controlador->verpagina('Vista/html/RegistroCliente.html');
        break;
    case "registrarCliente":
        $nombre = $_POST["nombre"];
        $correo = $_POST["email"];
        $clave = $_POST["clave"];
        $hash = password_hash($clave, PASSWORD_DEFAULT);
        $Conexion = new Conexion();
        $Conexion->abrir();
        // Verifica si ya existe el correo
        $Conexion->consulta("SELECT id FROM usuarios WHERE correo='$correo' LIMIT 1");
        $res = $Conexion->obtenerResult();
        if ($res && $res->num_rows > 0) {
            echo "Correo ya existe";
        } else {
            $Conexion->consulta("INSERT INTO usuarios (nombre, correo, contraseña, rol) VALUES ('$nombre', '$correo', '$hash', 'cliente')");
            if ($Conexion->obtenerFilasAfectadas() > 0) {
                // Obtener el usuario recién creado
                $Conexion->consulta("SELECT id, nombre, correo FROM usuarios WHERE correo='$correo' LIMIT 1");
                $nuevo = $Conexion->obtenerResult();
                if ($nuevo && $nuevo->num_rows > 0) {
                    $user = $nuevo->fetch_assoc();
                    $_SESSION["cliente_id"] = $user["id"];
                    $_SESSION["cliente_nombre"] = $user["nombre"];
                    $_SESSION["cliente_correo"] = $user["correo"];
                }
                echo "ok";
            } else {
                echo "error";
            }
        }
        $Conexion->cerrar();
        exit;
        break;

    case "login":
        $controlador->login(
            $_POST["email"],
            $_POST["clave"]
        );
        break;

    case "logout":
        $controlador->logout();
        break;

    case "addPedido":
        $controlador->guardarPedido(
            $_POST["nombre"],
            $_POST["correo"],
            $_POST["telefono"],
            $_POST["direccion"],
            $_POST["producto"],
            $_POST["cantidad"]
        );
        break;

    case "addProducto":
        $imagenesRutas = [];
        $ruta_indexphp = "uploads";
        $extensiones = array('image/jpg', 'image/jpeg', 'image/png', 'image/webp');
        $max_tamanyo = 1024 * 1024 * 8;

        if (isset($_FILES['imagenes']) && count($_FILES['imagenes']['name']) > 0) {
            for ($i = 0; $i < count($_FILES['imagenes']['name']); $i++) {
                if ($_FILES['imagenes']['error'][$i] === 0) {
                    $tipo = $_FILES['imagenes']['type'][$i];
                    $tam = $_FILES['imagenes']['size'][$i];
                    if (in_array($tipo, $extensiones) && $tam < $max_tamanyo) {
                        $nombreImagen = uniqid() . "_" . basename($_FILES['imagenes']['name'][$i]);
                        $ruta_nuevo_destino = $ruta_indexphp . '/' . $nombreImagen;
                        if (move_uploaded_file($_FILES['imagenes']['tmp_name'][$i], $ruta_nuevo_destino)) {
                            $imagenesRutas[] = $ruta_nuevo_destino;
                        }
                    }
                }
            }
        }

        $controlador->crearProducto(
            $_POST["marca"],
            $_POST["modelo"],
            $_POST["tipo"],
            $_POST["especificaciones"],
            $_POST["precio"],
            $_POST["categoria"], // <-- Aquí debe ir la categoría
            $imagenesRutas        // <-- Y aquí las imágenes
        );
        break;

    case "editarProducto":
        $imagenesRutas = [];
        $ruta_indexphp = "uploads";
        $extensiones = array('image/jpg', 'image/jpeg', 'image/png');
        $max_tamanyo = 1024 * 1024 * 8;

        if (isset($_FILES['imagenes']) && count($_FILES['imagenes']['name']) > 0 && $_FILES['imagenes']['name'][0] != "") {
            for ($i = 0; $i < count($_FILES['imagenes']['name']); $i++) {
                if ($_FILES['imagenes']['error'][$i] === 0) {
                    $tipo = $_FILES['imagenes']['type'][$i];
                    $tam = $_FILES['imagenes']['size'][$i];
                    if (in_array($tipo, $extensiones) && $tam < $max_tamanyo) {
                        $nombreImagen = uniqid() . "_" . basename($_FILES['imagenes']['name'][$i]);
                        $ruta_nuevo_destino = $ruta_indexphp . '/' . $nombreImagen;
                        if (move_uploaded_file($_FILES['imagenes']['tmp_name'][$i], $ruta_nuevo_destino)) {
                            $imagenesRutas[] = $ruta_nuevo_destino;
                        }
                    }
                }
            }
        }

        $controlador->editarProducto(
            $_POST["id"],
            $_POST["marca"],
            $_POST["modelo"],
            $_POST["tipo"],
            $_POST["especificaciones"],
            $_POST["precio"],
            $_POST["categoria"],
            $imagenesRutas
        );
        exit;
        break;

    case "eliminarProducto":
        $id = $_POST["id"];
        $Conexion = new Conexion();
        $Conexion->abrir();
        $Conexion->consulta("SELECT COUNT(*) as total FROM pedidos WHERE id_producto='$id'");
        $res = $Conexion->obtenerResult();
        $row = $res->fetch_assoc();
        if ($row['total'] > 0) {
            echo "tiene_pedidos";
        } else {
            $controlador->eliminarProducto($id);
        }
        $Conexion->cerrar();
        exit;
        break;

    case "addCategoria":
        $nombre = $_POST["nombre"];
        $controlador->crearCategoria($nombre);
        exit;
        break;

    case "editarCategoria":
        $id = $_POST["id"];
        $nombre = $_POST["nombre"];
        $Conexion = new Conexion();
        $Conexion->abrir();
        $Conexion->consulta("UPDATE categorias SET nombre='$nombre' WHERE id='$id'");
        $result = $Conexion->obtenerFilasAfectadas();
        $Conexion->cerrar();
        echo $result > 0 ? "ok" : "error";
        exit;
        break;

    case "eliminarCategoria":
        $id = $_POST["id"];
        $Conexion = new Conexion();
        $Conexion->abrir();
        $Conexion->consulta("SELECT COUNT(*) as total FROM productos WHERE id_categoria='$id'");
        $res = $Conexion->obtenerResult();
        $row = $res->fetch_assoc();
        if ($row['total'] > 0) {
            echo "tiene_productos";
        } else {
            $Conexion->consulta("DELETE FROM categorias WHERE id='$id'");
            $result = $Conexion->obtenerFilasAfectadas();
            echo $result > 0 ? "ok" : "error";
        }
        $Conexion->cerrar();
        exit;
        break;

    case "cambiarEstadoPedido":
        $id = $_POST["id"];
        $estado = $_POST["estado"];
        $Conexion = new Conexion();
        $Conexion->abrir();
        $Conexion->consulta("UPDATE pedidos SET estado='$estado' WHERE id='$id'");
        $result = $Conexion->obtenerFilasAfectadas();
        $Conexion->cerrar();
        echo $result > 0 ? "ok" : "error";
        exit;
        break;

    case "dashboard":
        $controlador->verpagina('Vista/html/dashboard.html');
        break;

    case "loginCliente":
        $correo = $_POST["email"];
        $clave = $_POST["clave"];
        $Conexion = new Conexion();
        $Conexion->abrir();
        $Conexion->consulta("SELECT id, nombre, correo, contraseña FROM usuarios WHERE correo='$correo' AND rol='cliente' LIMIT 1");
        $res = $Conexion->obtenerResult();
        if ($res && $res->num_rows > 0) {
            $user = $res->fetch_assoc();
            if (password_verify($clave, $user["contraseña"])) {
                $_SESSION["cliente_id"] = $user["id"];
                $_SESSION["cliente_nombre"] = $user["nombre"];
                $_SESSION["cliente_correo"] = $user["correo"];
                echo "ok";
            } else {
                echo "error";
            }
        } else {
            echo "error";
        }
        $Conexion->cerrar();
        exit;
        break;

    case "vistaProducto":
        $controlador->verpagina('Vista/html/vistaProducto.html');
        break;

    case "loginAdmin":
        $correo = $_POST["email"];
        $clave = $_POST["clave"];
        $controlador->login($correo, $clave);
        break;

    case "misPedidos":
        if (!isset($_SESSION["cliente_id"])) {
            header("Location: index.php?accion=iniciarCliente");
            exit;
        }
        $controlador->verpagina('Vista/html/misPedidos.html');
        break;

    default:
        $controlador->verpagina('Vista/html/index.html');
        break;
}
