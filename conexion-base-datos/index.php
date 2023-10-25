<?php
// cargar las variables de entorno con la libreria de Dotenv
require '../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '../.env');
$dotenv->safeLoad();

// __DIR__ -> devuelve la ruta absoluta de la carpeta donde se encuentra el archivo
require __DIR__ . '/includes/funciones.php';
$consulta = obtener_servicios();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Salón de Belleza</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="build/css/app.css">
</head>

<body>
    <div class="contenedor-estetica">
        <div class="imagen"></div>
        <div class="app">
            <header class="header">
                <h1>App Peluqueria</h1>
            </header>

            <div class="seccion">
                <h2>Servicios</h2>
                <p class="text-center">Elige tus Servicios a Continuación</p>
                <div id="servicios" class="listado-servicios">
                    <?php
                    // acceder a la consulta
                    // mysqli_fetch_assoc() -> obtiene un registro de la base de datos,
                    // devuelve un array asociativo
                    // con el while va a estar ejecutando el código mientras haya registros
                    while ($servicio = mysqli_fetch_assoc($consulta)) { ?>
                        <div class="servicio">
                            <p class="nombre-servicio"><?php echo $servicio['nombre']; ?></p>
                            <p class="precio-servicio">$<?php echo $servicio['precio']; ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>