<?php

require '../includes/funciones.php';
$auth = estaAutenticado();

if (!$auth) {
    header('Location: /');
}

// Importar la conexión
require '../includes/config/database.php';
$db = conectarDB('../');

// Escribir el Query
$query = "SELECT * FROM propiedades";

// Consultar la BD 
// mysqli_query('conexion', 'query')
$resultadoConsulta = mysqli_query($db, $query);


// Muestra mensaje condicional
// $_GET sirve para leer los query strings de la URL
$resultado = $_GET['resultado'] ?? null;


// Ejecutar el código después de que el usuario envia el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // $_POST es una variable global que contiene todos los datos 
    // enviados por el metodo post
    $id = $_POST['id'];
    // validar que sea un entero
    // filter_var(variable, tipo de filtro)
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if ($id) {

        // Eliminar el archivo
        $query = "SELECT imagen FROM propiedades WHERE id = $id";

        // realizar la consulta
        // mysqli_query('conexion', 'consulta')
        $resultado = mysqli_query($db, $query);
        // mysqli_fetch_assoc('resultado de la consulta'): obtener un registro de la consulta,
        // devuelve un array asociativo
        $propiedad = mysqli_fetch_assoc($resultado);

        // Eliminar la imagen previa
        // unlink('ruta del archivo'): elimina un archivo
        unlink('../imagenes/' . $propiedad['imagen']);

        // Eliminar la propiedad
        $query = "DELETE FROM propiedades WHERE id = $id";
        $resultado = mysqli_query($db, $query);

        if ($resultado) {
            header('location: /admin?resultado=3');
        }
    }
}

// Incluye un template

incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Administrador de Bienes Raices</h1>
    <?php if (intval($resultado) === 1) : ?>
        <p class="alerta exito">Anuncio Creado Correctamente</p>
    <?php elseif (intval($resultado) === 2) : ?>
        <p class="alerta exito">Anuncio Actualizado Correctamente</p>
    <?php elseif (intval($resultado) === 3) : ?>
        <p class="alerta exito">Anuncio Eliminado Correctamente</p>
    <?php endif; ?>

    <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>


    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody> <!-- Mostrar los Resultados -->
            <!-- mysqli_fetch_assoc($resultadoConsulta): obtiene un registro de la consulta y
                devuelve un array asociativo -->
            <!-- el while va a ejecutar el código mientras haya registros -->
            <?php while ($propiedad = mysqli_fetch_assoc($resultadoConsulta)) : ?>
                <tr>
                    <td><?php echo $propiedad['id']; ?></td>
                    <td><?php echo $propiedad['titulo']; ?></td>
                    <td> <img src="/imagenes/<?php echo $propiedad['imagen']; ?>" class="imagen-tabla"> </td>
                    <td>$ <?php echo $propiedad['precio']; ?></td>
                    <td>

                        <form method="POST" class="w-100">
                            <input type="hidden" name="id" value="<?php echo $propiedad['id']; ?>">
                            <input type="submit" class="boton-rojo-block" value="Eliminar">
                        </form>

                        <a href="admin/propiedades/actualizar.php?id=<?php echo $propiedad['id']; ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<?php

// Cerrar la conexion
mysqli_close($db);

incluirTemplate('footer');
?>