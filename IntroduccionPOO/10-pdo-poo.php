<?php include 'includes/header.php';

// Conectar a la BD con PDO
// PDO('database:host=host; dbname=database', 'user', 'password');
$db = new PDO('mysql:host=localhost; dbname=bienesraices_crud', 'root', '');

// Creamos el query
$query = "SELECT titulo, imagen from propiedades";

// Lo preparamos
$stmt = $db->prepare($query);

// Lo ejecutamos
$stmt->execute();

// Obtener los resultados
// fetchAll(como traer el resultado) nos permite obtener todos los resultados
$resultado = $stmt->fetchAll( PDO::FETCH_ASSOC );

// Iterar
foreach($resultado as $propiedad):
    echo $propiedad['titulo'];
    echo "<br>";
    echo $propiedad['imagen'];
    echo "<br>";
endforeach;


include 'includes/footer.php';