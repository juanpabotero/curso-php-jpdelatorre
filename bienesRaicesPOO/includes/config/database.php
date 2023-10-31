<?php 

function conectarDB() : mysqli {
    // Crear una conexión a la base de datos
    // mysqli('host', 'usuario', 'password', 'nombre_base_datos');
    $db = new mysqli('localhost', 'root', '', 'bienesraices_crud');

    if(!$db) {
        echo "Error no se pudo conectar";
        // previene que se siga ejecutando el codigo
        exit;
    } 

    return $db;
    
}