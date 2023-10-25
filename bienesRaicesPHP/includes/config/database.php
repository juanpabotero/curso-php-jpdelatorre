<?php

function conectarDB(): mysqli
{
    // crear la conexión a la base de datos
    // mysqli_connect('host', 'usuario', 'password', 'nombre_base_datos');
    $db = mysqli_connect('localhost', 'root', 'lulu.19', 'bienesraices_crud');

    if (!$db) {
        echo "Error no se pudo conectar";
        // previene que se siga ejecutando el codigo
        exit;
    }

    return $db;
}
