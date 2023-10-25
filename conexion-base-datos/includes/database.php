<?php

// crear la conexión a la base de datos
// mysqli_connect('host', 'usuario', 'password', 'nombre_base_datos');
$db = mysqli_connect('localhost', 'root', 'lulu.19', 'appsalon');

if(!$db) {
    echo "Hubo un error";
    // previene que se siga ejecutando el codigo
    exit;
}