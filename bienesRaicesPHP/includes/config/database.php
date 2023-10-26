<?php

function conectarDB(string $dir = ''): mysqli
{
    // cargar las variables de entorno con la libreria de Dotenv
    require_once $dir . '../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '../../../.env');
    $dotenv->safeLoad();

    // leer las variables de entorno gracias a la libreria de Dotenv
    $password = $_ENV['DB_PASSWORD'];

    // crear la conexión a la base de datos
    // mysqli_connect('host', 'usuario', 'password', 'nombre_base_datos');
    $db = mysqli_connect('localhost', 'root', $password, 'bienesraices_crud');

    if (!$db) {
        echo "Error no se pudo conectar";
        // previene que se siga ejecutando el codigo
        exit;
    }

    return $db;
}
