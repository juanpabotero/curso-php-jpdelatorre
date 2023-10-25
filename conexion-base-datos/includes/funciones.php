<?php

function obtener_servicios() {
    try {
        // Importar las credenciales
        require 'database.php';

        // Consulta SQL
        $sql = "SELECT * FROM servicios;";

        // Realizar la consulta
        // mysqli_query('conexion', 'consulta')
        $consulta = mysqli_query($db, $sql);

        // cerrar la conexión
        // Es opcional porq PHP cierra la conexión automáticamente al finalizar
        // la ejecución del script
        // mysqli_close($db);

        return $consulta;
    } catch (\Throwable $th) {
        // Throwable es una clase que se ejecuta cuando hay un error
        var_dump($th);
    }
}

obtener_servicios();
