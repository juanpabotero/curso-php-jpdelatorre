<?php


    require '../../includes/app.php';
    use App\Propiedad;
    use App\Vendedor;

    // Importar Intervention Image
    use Intervention\Image\ImageManagerStatic as Image;

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

    // Obtener los datos de la propiedad
    $propiedad = Propiedad::find($id);

    // Consultar para obtener los vendedores
    $vendedores = Vendedor::all();

    // Arreglo con mensajes de errores
    $errores = Propiedad::getErrores();

    // Ejecutar el código después de que el usuario envia el formulario
    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Asignar los atributos
        // $_POST es una variable global que contiene todos los datos 
        // enviados por el metodo post
        $args = $_POST['propiedad'];

        $propiedad->sincronizar($args);


        // Validación
        $errores = $propiedad->validar();

        // Subida de archivos
        // Generar un nombre único
        // md5: genera un hash de 32 caracteres (ya no se deberia usar)
        // uniqid: genera un id unico
        // en la base de datos se guarda solo el nombre de la imagen
        $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg";

        if($_FILES['propiedad']['tmp_name']['imagen']) {
            // Realiza un resize a la imagen con Intervention Image
            $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
            $propiedad->setImagen($nombreImagen);
        }
        
        if(empty($errores)) {
            // Almacenar la imagen
            if($_FILES['propiedad']['tmp_name']['imagen']) {
                $image->save(CARPETA_IMAGENES . $nombreImagen);
            }

            $propiedad->guardar();
        }
    }
    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Actualizar Propiedad</h1>

        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
        <?php endforeach; ?>

        <form class="formulario" method="POST" enctype="multipart/form-data">
            <!-- incluir el template del formulario -->
            <?php include '../../includes/templates/formulario_propiedades.php'; ?>

            <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
        </form>
        
    </main>

<?php 
    incluirTemplate('footer');
?> 