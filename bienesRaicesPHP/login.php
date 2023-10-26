<?php

// Importar la conexión
require 'includes/config/database.php';
$db = conectarDB();

// Autenticar el usuario

$errores = [];

// Ejecutar el código después de que el usuario envia el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";

    // sanitizar los inputs
    // mysqli_real_escape_string(conexion, dato): evita que se ejecuten scripts.
    // filter_var($variable, validacion) para validar un dato.
    // $_POST es una variable global que contiene todos los datos 
    // enviados por el metodo post
    $email = mysqli_real_escape_string($db,  filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
    $password = mysqli_real_escape_string($db,  $_POST['password']);

    if (!$email) {
        $errores[] = "El email es obligatorio o no es válido";
    }

    if (!$password) {
        $errores[] = "El Password es obligatorio";
    }

    if (empty($errores)) {

        // Revisar si el usuario existe.
        $query = "SELECT * FROM usuarios WHERE email = '$email' ";
        // consulta a la base de datos
        // mysqli_query('conexion', 'consulta')
        $resultado = mysqli_query($db, $query);

        // comprobar si hay resultados
        if ($resultado->num_rows) {
            // mysqli_fetch_assoc($resultadoConsulta): obtiene un registro de la consulta y
            // devuelve un array asociativo
            $usuario = mysqli_fetch_assoc($resultado);

            // Verificar si el password es correcto
            // password_verify($contraseña, $contraseñaHasheada) para verificar una contraseña.
            $auth = password_verify($password, $usuario['password']);

            // El usuario esta autenticado
            if ($auth) {
                // Iniciar sesión
                session_start();

                // Llenar el arreglo de la sesión
                $_SESSION['usuario'] = $usuario['email'];
                $_SESSION['login'] = true;

                header('Location: /admin');
            } else {
                $errores[] = 'El password es incorrecto';
            }
        } else {
            $errores[] = "El Usuario no existe";
        }
    }
}

// Incluye el header
require 'includes/funciones.php';
incluirTemplate('header');

?>

<main class="contenedor seccion contenido-centrado">
    <h1>Iniciar Sesión</h1>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <!-- method: metodo a ejecutar cuando se haga submit,
        GET expone los datos en la url, POST no -->
    <!-- action: define la ruta a la que se va a enviar la información del formulario,
        por defecto lo envia al mismo archivo  -->
    <form method="POST" class="formulario" novalidate>
        <fieldset>
            <legend>Email y Password</legend>

            <label for="email">E-mail</label>
            <input type="email" name="email" placeholder="Tu Email" id="email">

            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Tu Password" id="password">
        </fieldset>

        <input type="submit" value="Iniciar Sesión" class="boton boton-verde">
    </form>
</main>

<?php
incluirTemplate('footer');
?>