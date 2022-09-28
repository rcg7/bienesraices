<?php 


require '../../includes/app.php';
use App\Vendedor;
estaAutenticado();

// Validar que sea un ID válido

$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if(!$id) {
    header('Location: /admin');
}

// Obtener el arreglo del vendedor
$vendedor = Vendedor::find($id);

// Arreglo con mensajes de errores
 $errores = Vendedor::getErrores();

 if($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Asignar los Valores
    $args = $_POST['vendedor'];

    // Sincronizar el objeto en memoria con lo que el usuario escribió
    $vendedores->sincronizar($args);

    // Validación
    $errores = $vendedores->validar();

    if(empty($errores)) {
        $vendedores->guardar();
    }
 }

 incluirTemplate('header'); 
?>
    <main class="contenedor seccion">
        <h1>Actualizar Vendedor(a)</h1>

        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error): ?>
        <div class="alerta error"> 
            <?php echo $error; ?> 
        </div>
        <?php endforeach; ?>
       
        <form class="formulario" method="POST">
            <?php include '../../includes/templates/formulario_vendedores.php'; ?>
        
            <input type="submit" value="Guardar Cambios" class="boton boton-verde">
        </form>

    </main>

<?php 
        incluirTemplate('footer');
?>