<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    require "../../includes/config/database.php";
    if (!$auth) {
       header('location: /'); die();
    }
    if ($_SESSION['role']!="admin") {
        header('location: /admin/index.php'); 
        die();
    }
    inlcuirTemplate('header');
    $db =conectarDB();

    $queryId = "SELECT MAX(idConfig)+1 FROM config ";//Ultimo id de la configuracion 
    $queryCar ="SELECT idCarrera FROM carreras";//Ids de las carreras de ingenieria a execpcion de maestria

    $resultadoId =mysqli_query($db, $queryId);
    $resultadoCar =mysqli_query($db, $queryCar);
    
    $ban = true;
    if ($_SERVER['REQUEST_METHOD']==="POST") {
        $nombreCondig = $_POST['nombreCon'];
        $descripcion = $_POST['descripcion'];
        $cantidadG= $_POST['cantidadGrupo'];
        $cantidadxG = $_POST['cantidadxG'];
        $auxId = mysqli_fetch_assoc($resultadoId);
        $resId = [];

        foreach($auxId as $value){
            if($value < 1){
                $value += 1;
            }
            $queryConfig = "INSERT INTO config (idConfig, nombre, descripcion) VALUES ('{$value}','{$nombreCondig}','{$descripcion}');";
            $resultadoConfig = mysqli_query($db, $queryConfig);

            while($row = mysqli_fetch_array($resultadoCar)){
                $queryinsert = "INSERT INTO detalles_config (idConfig, idCarrera, cantidadGrupos, num_Alumnos) VALUES ('{$value}',$row[0],'{$cantidadG}','{$cantidadxG}')"; 
                $resultadoCar2 =mysqli_query($db, $queryinsert);
            }
            if (!$value) {
                $ban = false;
                break;
            }
        }
        
			
		
    }
?>
<main class="g_config">
    <h1>Registrar Configuración</h1>
    <form method="POST">
        <div class="nombre">
            <label >Nombre de la configuración</label>
            <input type="text" name="nombreCon" id="nombreCon"required onkeypress="return checkLetters(event);" >
        </div>
        <div class="descripcion">
            <label >Descripción</label>
            <textarea id="descripcion"  name="descripcion" cols="48" rows="5" required></textarea>
        </div>
        <div class="parametros">
            <div class="cantidadG">
                <label>Cantidad Grupos </label>
                <input type="number" name="cantidadGrupo" id="cantidadGrupo" min="1" max="10" required>
            </div>
            <div class="cantidadXG">
                <label>Cantidad por Grupo</label>
                <input type="number" name="cantidadxG" id="cantidadxG" min="1" max="50"required>
            </div>
            <div class="btnAgregar">
                <input type="submit" value="Agregar">
            </div>            
        </div>
    </form>
</main>
<?php 
    inlcuirTemplate('footer');
    if ($ban && $_SERVER['REQUEST_METHOD']==="POST") {
        echo "<script>exito('Configuración Registrada');</script>";
    }
?>

