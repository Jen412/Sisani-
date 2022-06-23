<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    require "../../includes/config/database.php";
    if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');
    $db =conectarDB();

    $query = "SELECT * FROM carreras";
    $resultado =mysqli_query($db, $query);

    if ($_SERVER['REQUEST_METHOD']==="POST") {
        $nombreCondig = $_POST['nombreCon'];
        $descripcion = $_POST['descripcion'];
        $carrera = $_POST['carrera'];
        $cantidadG= $_POST['cantidadGrupo'];
        $cantidadxG = $_POST['cantidadxG'];

        
    }
?>
<main class="g_config">
    <h1>Registrar Configuración</h1>
    <form method="POST">
        <div class="nombre">
            <label >Nombre de la configuración</label>
            <input type="text" name="nombreCon" id="nombreCon"onkeypress="return checkLetters(event);" >
        </div>
        <div class="descripcion">
            <label >Descripción</label>
            <textarea id="descripcion"  name="descripcion" cols="48" rows="5"></textarea>
        </div>
        <div class="parametros">
            <div class="carrera">
                <label for="">Carrera</label>
                <select id="carrera" name="carrera">
                    <option value="" disabled selected>--Seleccione Carrera--</option>    
                    <?php while($carrera = mysqli_fetch_assoc($resultado)):?>
                    <option value="<?php echo $carrera['idCar']?>"><?php echo $carrera['nombcar']?></option>    
                    <?php endwhile;?>
                </select>
            </div>
            <div class="cantidadG">
                <label>Cantidad Grupos </label>
                <input type="number" name="cantidadGrupo" id="cantidadGrupo">
            </div>
            <div class="cantidadXG">
                <label>Cantidad por Grupo</label>
                <input type="number" name="cantidadxG" id="cantidadxG">
            </div>
            <div class="btnAgregar">
                <input type="submit" value="Agregar">
            </div>            
        </div>
    </form>
</main>
<?php 
    inlcuirTemplate('footer');
?>

