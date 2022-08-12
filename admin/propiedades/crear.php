<?php 


require '../../includes/app.php';

use App\Propiedad;



estaAtenticado();

    // Base de datos
    $db = conectarDB();

    // Consultar para obtener lo vendedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db, $consulta);

    // Arreglo con mensajes de errores
    $errores = [];


    $titulo = '';
    $precio = '';
    $descripcion = '';
    $habitaciones = '';
    $wc = '';
    $aparcamiento = '';
    $vendedores_id = '';



    // Ejecutar el código después de que el usuario envia el formulario
    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $propiedad = new Propiedad($_POST);

        $propiedad->guardar();
       

        
        // echo "<pre>";
        // var_dump($_POST);
        // echo "</pre>";

        // echo "<pre>";
        // var_dump($_FILES);
        // echo "</pre>";

      
        $titulo = mysqli_real_escape_string( $db, $_POST['titulo'] );
        $precio = mysqli_real_escape_string( $db, $_POST['precio'] );
        $descripcion = mysqli_real_escape_string( $db, $_POST['descripcion'] );
        $habitaciones = mysqli_real_escape_string( $db, $_POST['habitaciones'] );
        $wc = mysqli_real_escape_string( $db, $_POST['wc'] );
        $aparcamiento = mysqli_real_escape_string( $db, $_POST['aparcamiento'] );
        $vendedores_id = mysqli_real_escape_string( $db, $_POST['vendedores_id'] );
        $creado = date('Y/m/d');


    // Asignar files hacia una variable

    $imagen = $_FILES['imagen'];


    //Mensajes de errores

        if(!$titulo) {
            $errores[] = "Debes añadir un titulo";
        }
        if(!$precio) {
            $errores[] = "El precio es Obligatorio";
        }
        if( strlen( $descripcion ) < 50 ) {
            $errores[] = "La descripción es Obligatoria y debe tener al menos 50 caracteres";
        }
        if(!$habitaciones) {
            $errores[] = "El número de habitaciones es Obligatorio";
        }
        if(!$wc) {
            $errores[] = "El número de baños es Obligatorio";
        }
        if(!$aparcamiento) {
            $errores[] = "El número de estacionamientos es Obligatorio";
        }
        if(!$vendedores_id) {
            $errores[] = "El vendedor es Obligatorio";
        }

        if( !$imagen['name'] || $imagen['error' ] ) {
            $errores[] = 'La imagen es obligatoria';
        }

        // Validar por tamaño

        $medida = 1000 * 1000;

        if($imagen['size'] > $medida ) {
            $errores[] = 'La imagen es muy pesada';
        }

        // Revisar que el array de errores este vacio

        if(empty($errores)) {

        /** Subida de archivos */ 

        // Crear carpeta

        $carpetaImagenes = '../../imagenes/';

        if(!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes);
        }

        // Generar un nombre único

        $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg";

        // Subir imagenes

        move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen );

        // echo $query;
        $resultado = mysqli_query($db, $query);

        if($resultado) {

           // Redireccionar al usuario
           header('Location: /admin?resultado=1');
        }

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
            <fieldset>
                <legend>Información General</legend>

                <label for="titulo">Titulo</label>
                <input type="text" id="titulo" name="titulo" placeholder="Titulo Propiedad" value="<?php echo $titulo; ?>">
                
                <label for="precio">Precio</label>
                <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo $precio; ?>">

                
                <label for="imagen">Imagen</label>
                <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

                <label for="descripcion">Descripción</label>
                <textarea id="descripcion"name="descripcion"><?php echo $descripcion; ?></textarea>

            </fieldset>

            <fieldset>
                <legend>Información de la Propiedad</legend>

                <label for="habitaciones">Habitaciones</label>
                <input type="number" id="habitaciones" name="habitaciones"  value="<?php echo $habitaciones; ?>" placeholder="Ej: 3" min="1" max="9">
                
                <label for="wc">Baños</label>
                <input type="number" id="wc" name="wc"  value="<?php echo $wc; ?>" placeholder="Ej: 3" min="1" max="9">

                <label for="aparcamiento">Estacionamiento</label>
                <input type="number" id="aparcamiento" name="aparcamiento"  value="<?php echo $aparcamiento; ?>" placeholder="Ej: 3" min="1" max="9">

            </fieldset>

            <fieldset>
                <legend>Vendedor</legend>

                <select name="vendedores_id"  value="<?php echo $vendedores_id; ?>" >
                    <option value="">-- Seleccione --</option>
                    <?php while($vendedor = mysqli_fetch_assoc($resultado) ) : ?>
                        <option <?php echo $vendedores_id === $vendedor['id'] ? 'selected' : ''; ?> value="<?php echo $vendedor['id']; ?>"> <?php echo $vendedor['nombre'] . " " . $vendedor['apellido']; ?> </option>
                        <?php endwhile; ?>
                </select>
            </fieldset>
            
          

        <input type="submit" value="Crear Propiedad" class="boton boton-verde">
        
        </form>

    </main>

<?php 
        incluirTemplate('footer');
?>
