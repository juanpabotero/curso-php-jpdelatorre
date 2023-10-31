<?php 
    require '../../includes/app.php';
    use App\Propiedad;
    use App\Vendedor;

    estaAutenticado();

    // Importar libreria Intervention Image
    use Intervention\Image\ImageManagerStatic as Image;

    // Crear el objeto
    $propiedad = new Propiedad;

    // Consultar para obtener los vendedores
    // :: para acceder a un método estático
    $vendedores = Vendedor::all();

    // Arreglo con mensajes de errores
    // :: para acceder a un método estático
    $errores = Propiedad::getErrores();

    // Ejecutar el código después de que el usuario envia el formulario
    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        /** Crea una nueva instancia */
        // $_POST es una variable global que contiene todos los datos 
        // enviados por el metodo post
        $propiedad = new Propiedad($_POST['propiedad']);

        // Generar un nombre único
        // md5: genera un hash de 32 caracteres (ya no se deberia usar)
        // uniqid: genera un id unico
        // en la base de datos se guarda solo el nombre de la imagen
        $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg";

        // Setear la imagen
        if($_FILES['propiedad']['tmp_name']['imagen']) {
            // Realiza un resize a la imagen con Intervention Image
            $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
            $propiedad->setImagen($nombreImagen);
        }
        
        // Validar
        $errores = $propiedad->validar();

        if(empty($errores)) {
        
            // Crear la carpeta para subir imagenes
            if(!is_dir(CARPETA_IMAGENES)) {
                mkdir(CARPETA_IMAGENES);
            }

            // Guarda la imagen en el servidor
            $image->save(CARPETA_IMAGENES . $nombreImagen);

            // Guarda en la base de datos
            $propiedad->guardar();
        }
    }

    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Crear</h1>

        

        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
        <?php endforeach; ?>

        <form class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
            <!-- incluir el template del formulario -->
            <?php include '../../includes/templates/formulario_propiedades.php'; ?>

            <input type="submit" value="Crear Propiedad" class="boton boton-verde">
        </form>
        
    </main>

<?php 
    incluirTemplate('footer');
?> 