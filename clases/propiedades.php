<?php

namespace App;

class Propiedad {

    // Base de datos
    protected static $db;
    protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'aparcamiento', 'creado', 'vendedores_id'];
    
    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $aparcamiento;
    public $creado;
    public $vendedorId;

        // DEFINIR LA CONEXIÓN A LA BASE DE DATOS
            public static function setDB($database) {
            self::$db = $database;
    }

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? '';
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? 'imagen.jpg';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->aparcamiento = $args['aparcamiento'] ?? '';
        $this->creado = date('Y/m/d');
        $this->vendedores_id = $args['vendedores_id'] ?? '';

    }

    public function guardar() {
        echo "Guardando en la base de datos";

        // Sanitizar los datos
        $atributos =$this->sanitizarAtributos();

         // Insertar en la base de datos
         $query = " INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, aparcamiento, creado, vendedores_id ) VALUES ( '$this->titulo', '$this->precio', '$this->imagen', '$this->descripcion', '$this->habitaciones', '$this->wc', '$this->aparcamiento', '$this->creado', '$this->vendedores_id' ) ";

         $resultado = self::$db->query($query);
    }

    public function sanitizarAtributos() {

    }
}