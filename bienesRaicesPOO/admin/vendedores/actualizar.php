<?php
    require '../../includes/app.php';
    use App\Vendedor;

    estaAutenticado();

    // Validar el ID enviado en la URL
    // $_GET sirve para leer los query strings de la URL
    $id = $_GET['id'];
    // validar que sea un entero
    // filter_var(variable, tipo de filtro)
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if(!$id) {
        // redireccionar
        header('Location: /admin');
    }

    // Obtener los datos del vendedor a editar...
    $vendedor = Vendedor::find($id);

    // Arreglo con mensajes de errores
    $errores = Vendedor::getErrores();

    // Ejecutar el código después de que el usuario envia el formulario
    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Asignar los atributos
        // $_POST es una variable global que contiene todos los datos 
        // enviados por el metodo post
        $args = $_POST['vendedor'];

        $vendedor->sincronizar($args);

        // Validación
        $errores = $vendedor->validar();
       

        if(empty($errores)) {
            $vendedor->guardar();
        }
    }
    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Actualizar Vendedor</h1>

        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
        <?php endforeach; ?>

        <form class="formulario" method="POST">
            <!-- incluir el template del formulario -->
            <?php include '../../includes/templates/formulario_vendedores.php'; ?>

            <input type="submit" value="Actualizar Vendedor" class="boton boton-verde">
        </form>
        
    </main>

<?php 
    incluirTemplate('footer');
?> 