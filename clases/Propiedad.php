<?php

namespace App;

class Propiedad {

    // Base de datos
    protected static $db;
    protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'aparcamiento', 'creado', 'vendedores_id'];

    // Errores
    protected static $errores = [];
    
    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $aparcamiento;
    public $creado;
    public $vendedores_id;

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
        if(isset($this->id)) {
            // Actualizar
            $this->actualizar();
        } else {
            // Creando un nuevo registro
            $this->crear();
        }
    }

    public function crear() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

         // Insertar en la base de datos
         $query = " INSERT INTO propiedades ( ";
         $query .= join(', ', array_keys($atributos));
         $query .= " ) VALUES (' ";
         $query .= join("', '", array_values($atributos));
         $query .= " ') ";

         $resultado = self::$db->query($query);

         return $resultado;
    }

    public function actualizar() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }

        $query = "UPDATE propiedades SET ";
        $query .= join(', ', $valores );
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= "LIMIT 1 ";

        $resultado = self::$db->query($query);

        if($resultado) {
            // Redireccionar al usuario
            header('Location: /admin?resultado=2');
        }
    }

    // Identificar y unir los atributos de la BD
    public function atributos() {
        $atributos = [];
        foreach(self::$columnasDB as $columna) {
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];

        foreach($atributos as $key => $value ) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    /** Subida de archivos **/

    public function setImagen($imagen) {
        // Elimina la imagen previa

        if(isset( $this->id) ) {
            // Comprobar si existe el archivo
            $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
            if($existeArchivo) {
                unlink(CARPETA_IMAGENES . $this->imagen);
            }
        }
        // Asignar el atributo de imagen el nombre de la imagen
        if($imagen) {
            $this->imagen = $imagen;
        }
    }

    // Validación 
    public static function getErrores() {
        return self::$errores;
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

            if(!$this->vendedores_id) {
                self::$errores[] = "El vendedor es Obligatorio";
            }

            if(!$this->imagen ) {
                 self::$errores[] = 'La imagen es obligatoria';
             }

            return self::$errores;
    }

    // Listar todas los registros
    public static function all() {
        $query ="SELECT * FROM propiedades";

        $resultado = self::consultarSQL($query);

        return array_shift( $resultado ) ;
    }

    // Busca un registro por su id
    public static function find($id) {
        $query = "SELECT * FROM propiedades WHERE id = ${id}";

        $resultado = self::consultarSQL($query);

        return array_shift( $resultado );
    }

    public static function consultarSQL($query) {
        // Consultar la base de datos
        $resultado = self::$db->query($query);

        // Itinerar los resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }

        //Liberar la memoria
        $resultado->free();

        // Retornar los resultados
        return $array;
    }


    protected static function crearObjeto($registro) {
        $objeto = new self;

        foreach($registro as $key => $value ) {
            if(property_exists( $objeto, $key )) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    // Sincroniza el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar( $args = [] ) {
        foreach($args as $key => $value) {
            if(property_exists($this, $key ) && is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}