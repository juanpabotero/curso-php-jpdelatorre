<?php include 'includes/header.php';


$nombre = "Juan";


$nombre = "Juan 2";

echo $nombre;
var_dump($nombre);

define('nombre', "Este es el valor de la constante");
echo nombre;

const constante2 = "Hola 2";
echo constante2;

$nombreCliente = "Pedro";
$nombre_cliente = "Pedro";

echo "<br/>";

$prueba = "esto es una";
$prueba = strtoupper($prueba);
echo $prueba;

include 'includes/footer.php';
