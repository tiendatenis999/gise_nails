<?php
class Pedidos {
    private $pedidoId;
    private $usuarioId;
    private $productoId;
    private $cantidad;
    private $fechaPedido;
    private $estado;

    public function __construct($pedidoId, $usuarioId, $productoId, $cantidad, $fechaPedido, $estado)
    {
        $this->pedidoId = $pedidoId;
        $this->usuarioId = $usuarioId;
        $this->productoId = $productoId;
        $this->cantidad = $cantidad;
        $this->fechaPedido = $fechaPedido;
        $this->estado = $estado;
    }
    public function obtenerPedidoId() {
        return $this->pedidoId;
    }
    public function obtenerUsuarioId() {
        return $this->usuarioId;
    }
    public function obtenerProductoId() {
        return $this->productoId;
    }
    public function obtenerCantidad() {
        return $this->cantidad;
    }
    public function obtenerFechaPedido() {
        return $this->fechaPedido;
    }
    public function obtenerEstado() {
        return $this->estado;
    }
}


?>