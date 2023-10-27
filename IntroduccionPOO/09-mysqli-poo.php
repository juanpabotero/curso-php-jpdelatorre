<?php include 'includes/header.php';

// Conectar a la BD con Mysqli.
// mysqli('host', 'user', 'password', 'database');
$db = new mysqli('localhost', 'root', '', 'bienesraices_crud');

// Creamos el query
$query = "SELECT titulo, imagen from propiedades";

// Lo preparamos
$stmt = $db->prepare($query);

// Lo ejecutamos
$stmt->execute();

// creamos las variables donde se guardaran los resultados
// bind_result() nos permite pasar los resultados a variables
$stmt->bind_result($titulo, $imagen);

// imprimir el resultado
// fetch() nos permite asignar los resultados
while($stmt->fetch()):
    var_dump($titulo);
endwhile;

include 'includes/footer.php';