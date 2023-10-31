<?php

namespace App;

// clase ActiveRecord (Patrón de diseño para definir la forma de 
// interactuar con los datos)
class ActiveRecord {

    // Base DE DATOS
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    // Errores
    protected static $errores = [];

    
    // Definir la conexión a la BD
    public static function setDB($database) {
        // self:: para acceder a una propiedad estática de la misma clase
        self::$db = $database;
    }

    // Validación
    public static function getErrores() {
        // static:: acceder a una propiedad estática de una clase hija
        return static::$errores;
    }
    public function validar() {
        // static:: acceder a una propiedad estática de una clase hija
        static::$errores = [];
        return static::$errores;
    }

    // Registros - CRUD
    public function guardar() {
        if(!is_null($this->id)) {
            // actualizar
            $this->actualizar();
        } else {
            // Creando un nuevo registro
            $this->crear();
        }
    }

    public static function all() {
        // construir el query
        // static:: acceder a una propiedad estática de una clase hija
        $query = "SELECT * FROM " . static::$tabla;

        // self:: para acceder a una propiedad estática de la misma clase
        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    // Busca un registro por su id
    public static function find($id) {
        // Construir la consulta
        // static:: acceder a una propiedad estática de una clase hija
        $query = "SELECT * FROM " . static::$tabla  ." WHERE id = $id";

        // self:: para acceder a una propiedad estática de la misma clase
        $resultado = self::consultarSQL($query);

        // array_shift(array): elimina y devuelve el primer elemento de un array
        return array_shift( $resultado ) ;
    }

    // crea un nuevo registro
    public function crear() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Insertar en la base de datos
        // Construir la consulta
        // static:: acceder a una propiedad estática de una clase hija
        // join(separador, array): une los elementos de un array en un string
        // array_keys(array): devuelve todas las claves de un array
        // array_values(array): devuelve todos los valores de un array
        $query = " INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES (' "; 
        $query .= join("', '", array_values($atributos));
        $query .= " ') ";

        // Resultado de la consulta
        // self:: para acceder a una propiedad estática de la misma clase
        // query(query): realiza una consulta a la base de datos
        $resultado = self::$db->query($query);

        // Mensaje de exito
        if($resultado) {
            // Redireccionar al usuario.
            header('Location: /admin?resultado=1');
        }
    }

    public function actualizar() {

        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }

        // Construir la consulta
        // static:: acceder a una propiedad estática de una clase hija
        // self:: para acceder a una propiedad estática de la misma clase
        // escape_string(valor): escapa los caracteres especiales de una cadena
        $query = "UPDATE " . static::$tabla ." SET ";
        $query .=  join(', ', $valores );
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 "; 

        // self:: para acceder a una propiedad estática de la misma clase
        // query(query): realiza una consulta a la base de datos
        $resultado = self::$db->query($query);

        if($resultado) {
            // Redireccionar al usuario.
            header('Location: /admin?resultado=2');
        }
    }

    // Eliminar un registro
    public function eliminar() {
        // Eliminar el registro
        // Construir la consulta
        // static:: acceder a una propiedad estática de una clase hija
        // self:: para acceder a una propiedad estática de la misma clase
        // escape_string(valor): escapa los caracteres especiales de una cadena
        $query = "DELETE FROM "  . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";

        // self:: para acceder a una propiedad estática de la misma clase
        // query(query): realiza una consulta a la base de datos
        $resultado = self::$db->query($query);

        if($resultado) {
            $this->borrarImagen();
            header('location: /admin?resultado=3');
        }
    }

    public static function consultarSQL($query) {
        // Consultar la base de datos
        $resultado = self::$db->query($query);

        // Iterar los resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            // static:: acceder a una propiedad estática de una clase hija
            // $array[] = para agregar un elemento al final del array
            $array[] = static::crearObjeto($registro);
        }

        // liberar memoria
        $resultado->free();

        // retornar los resultados
        return $array;
    }

    protected static function crearObjeto($registro) {
        // new static: crea un nuevo objeto de la clase que lo invoca
        $objeto = new static;

        foreach($registro as $key => $value ) {
            // property_exists(objeto, propiedad): comprueba si la propiedad existe en el objeto
            if(property_exists( $objeto, $key )) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }



    // Identificar y unir los atributos de la BD
    public function atributos() {
        $atributos = [];
        // static:: acceder a una propiedad estática de una clase hija
        foreach(static::$columnasDB as $columna) {
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value ) {
            // self:: para acceder a una propiedad estática de la misma clase
            // escape_string(valor): escapa los caracteres especiales de una cadena
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    // sincroniza el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar($args=[]) { 
        foreach($args as $key => $value) {
          // property_exists(objeto, propiedad): comprueba si la propiedad existe en el objeto
          if(property_exists($this, $key) && !is_null($value)) {
            $this->$key = $value;
          }
        }
    }

    // Subida de archivos
    public function setImagen($imagen) {
        // Elimina la imagen previa
        if( !is_null($this->id) ) {
            $this->borrarImagen();
        }
        // Asignar al atributo de imagen el nombre de la imagen
        if($imagen) {
            $this->imagen = $imagen;
        }
    }

    // Elimina el archivo
    public function borrarImagen() {
        // Comprobar si existe el archivo
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
        if($existeArchivo) {
            // Eliminar la imagen previa
            // unlink('ruta del archivo'): elimina un archivo
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
    }
}