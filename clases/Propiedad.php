<?php

namespace App;

class Propiedad extends ActiveRecord {
    protected static $tabla = 'propiedades';
    protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'aparcamiento', 'creado', 'vendedores'];

    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $aparcamiento;
    public $creado;
    public $vendedores;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? '';
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->aparcamiento = $args['aparcamiento'] ?? '';
        $this->creado = date('Y/m/d');
        $this->vendedores = $args['vendedores'] ?? '';
   
    }

    public function validar() {
        //Mensajes de errores

        if(!$this->titulo) {
            self::$errores[] = "Debes añadir un titulo";
        }

        if(!$this->precio) {
            self::$errores[] = "El precio es Obligatorio";
        }

        if( strlen( $this->descripcion ) < 50 ) {
            self::$errores[] = "La descripción es Obligatoria y debe tener al menos 50 caracteres";
        }

        if(!$this->habitaciones) {
            self::$errores[] = "El número de habitaciones es Obligatorio";
        }

        if(!$this->wc) {
            self::$errores[] = "El número de baños es Obligatorio";
        }

        if(!$this->aparcamiento) {
            self::$errores[] = "El número de estacionamientos es Obligatorio";
        }

        if(!$this->vendedores) {
            self::$errores[] = "El vendedor es Obligatorio";
        }

        if(!$this->imagen ) {
             self::$errores[] = 'La imagen de la propiedad es obligatoria';
         }

        return self::$errores;
}
}