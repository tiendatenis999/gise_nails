<?php
class Productos{

    private $marca;
    private $modelo;
    private $tipo;
    private $especificaciones;
    private $precio;
    private $id_categoria;

    public function __construct($marca, $modelo, $tipo, $especificaciones, $precio, $id_categoria)
    {
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->tipo = $tipo;
        $this->especificaciones = $especificaciones;
        $this->precio = $precio;
        $this->id_categoria = $id_categoria;
    }

    public function obtenerMarca(){
        return $this->marca;
    }
    public function obtenerModelo(){
        return $this->modelo;
    }
    public function obtenerTipo(){
        return $this->tipo;
    }
    public function obtenerEspecificaciones(){
        return $this->especificaciones;
    }
    public function obtenerPrecio(){
        return $this->precio;
    }
    public function obtenerIdCategoria(){
        return $this->id_categoria;
    }
}

    