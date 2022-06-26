<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    require "../../includes/config/database.php";
    if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');
    $db =conectarDB();

    if ($_SERVER['REQUEST_METHOD']==="POST") {
        $nombreCondig = $_POST['nombreCon'];
        $descripcion = $_POST['descripcion'];
        $cantidadG= $_POST['cantidadGrupo'];
        $cantidadxG = $_POST['cantidadxG'];
        
        $queryId = "SELECT MAX(idConfig)+1 FROM config ";//puedo obtener el ultimo valor de la columna con MAX y sumarle uno
        $resultadoId =mysqli_query($db, $queryId);
        $auxId = mysqli_fetch_assoc($resultadoId);
        
        foreach($auxId as $value){
            $queryConfig = "INSERT INTO config (idConfig, nombre, descripcion) VALUES ('{$value}','{$nombreCondig}','{$descripcion}');";
            $resultadoConfig = mysqli_query($db, $queryConfig);//Query para insertar la configuraión 
        
            $queryCar ="SELECT idCar FROM carreras WHERE idCar != 18 AND idCar !=25";
            $resultadoCar =mysqli_query($db, $queryCar);


            while($row = mysqli_fetch_array($resultadoCar)){
                $queryinsert = "INSERT INTO detalles_config (idConfig, idCar, cant_Grupos, cant_Elem_Grupo) VALUES ('{$value}',$row[0],'{$cantidadG}','{$cantidadxG}')"; 
                $resultadoCar2 =mysqli_query($db, $queryinsert);
            }
        }
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

