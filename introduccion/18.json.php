<?php include 'includes/header.php';

$productos = [
    [
        'nombre' => 'Tablet',
        'precio' => 200,
        'disponible' => true
    ],
    [
        'nombre' => 'Televisión 24"',
        'precio' => 300,
        'disponible' => true
    ],
    [
        'nombre' => 'Monitor Curvo',
        'precio' => 400,
        'disponible' => false
    ]
];

echo "<pre>";
var_dump($productos);

// convertir un array a un string JSON.
$json = json_encode($productos, JSON_UNESCAPED_UNICODE);

// para convertir un string JSON a un array.
$json_array = json_decode($json);

var_dump($json);
var_dump($json_array);
var_dump($json_array[0]->nombre);
echo "</pre>";




include 'includes/footer.php';
