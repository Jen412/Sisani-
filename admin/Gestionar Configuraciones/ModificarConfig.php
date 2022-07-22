<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    require "../../includes/config/database.php";
    if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');
    $db =conectarDB();

    $queryCon ="SELECT * FROM config";
    $resultadoCon =mysqli_query($db, $queryCon);
    
    if ($_SERVER['REQUEST_METHOD']=="POST" && $_POST['tipoForm'] === "configuraciones") {
        $nombre =[];
        $descripcion = [];
        $id = [];
        $grupo = [];
        $elemGrupo = [];

        foreach($_POST as $key => $value){
            if ($value == "ID"){//Id de la configuración
                $id [$key] = $value;
            }
            if(strpos($key, 'n')){//Nombre
                $nombre [rtrim($key,'n')] = $value;
            }
            if(strpos($key, 'd')){//Descripción
                $descripcion [rtrim($key,'d')] = $value;
            }
            if(strpos($key, 'x')){//Columna de grupos
                $grupo [rtrim($key,'x')] = $value;
            }
            if(is_int($key) && $value != "ID"){//Columna de Elementos por grupo
                $elemGrupo [$key] = $value;
            } 
        }
        
        foreach($id as $keyId => $valueId){

            foreach($nombre as $key => $value){
                $queryUpdateN = "UPDATE config SET nombre  = '{$value}' WHERE idConfig = '{$keyId}' ";
                $resultado = mysqli_query($db, $queryUpdateN);
            }
            foreach($descripcion as $key => $value){
                $queryUpdateD = "UPDATE config SET descripcion = '{$value}' WHERE idConfig = '{$keyId}' ";
                $resultado = mysqli_query($db, $queryUpdateD);
            }
            foreach($elemGrupo as $key => $value){
                $queryUpdateE = "UPDATE detalles_config SET num_Alumnos  = '{$value}' WHERE idConfig = '{$keyId}'  AND idCarrera = '{$key}' ";
                $resultado = mysqli_query($db, $queryUpdateE);
            }
            foreach($grupo as $key => $value){
                $queryUpdate = "UPDATE detalles_config SET cantidadGrupos = '{$value}' WHERE idConfig = '{$keyId}'  AND idCarrera = '{$key}' "; 
                $resultado = mysqli_query($db, $queryUpdate);
            }
            
            if (!$keyId) {
				$ban = false;
                break;
			}
        }
        
    }  
?>
<main class="g_config">
    <h1>Modificar Configuración</h1>
    <form method="GET">
        <div class="nombreCo">
            <label >Nombre de la configuración</label>
            <select name="config" id="config">
                <option value=""disabled selected>--Seleccione la Configuración--</option>  
                <?php while($configuracion = mysqli_fetch_assoc($resultadoCon)):?>
                    <option value="<?php echo $configuracion['idConfig'];?>">
                        <?php echo $configuracion['nombre'];?>
                    </option>
                <?php endwhile;?>  
            </select>
            <input type="submit" value="Buscar Datos">
        </div>
    </form>

    <form method="POST">
        <input type="hidden" name="tipoForm" value="configuraciones">
        <?php if ($_SERVER['REQUEST_METHOD']=="GET") {
            $config=$_GET['config']?? null;
            
            if($config != null){
                $queryDetalleCon = "SELECT * FROM config WHERE config.idConfig = $config";
                $resultadoDetalleCon =mysqli_query($db, $queryDetalleCon);
                while($row = mysqli_fetch_assoc($resultadoDetalleCon)): ?>
                    <div class="modificarC">
                        <div class="id">
                            <label for="">Id Configuración</label>
                             <input type="text"value="<?php echo $row["idConfig"] ;?>" disabled selected>
                        </div>
                        <div class="nameConfig">
                            <label>Nombre de la Configuración</label>
                            <?php echo ('<input name="'.$row["idConfig"].'n"  value = "'.$row["nombre"].'" type="text" required> ');?>
                        </div>
                        <div class="des">
                            <label>Descripción</label>
                            <?php echo ('<textarea name="'.$row["idConfig"].'d" placeholder="'.$row["descripcion"].'"name="" id="" cols="48" rows="5"  ></textarea>');?>
                        </div>
                    </div>
                    
                    <div class="Con">
                        <div class = "container-table-con">
                            <div class="table__header">Carrera</div>
                            <div class="table__header">Cantidad de grupos</div>
                            <div class="table__header">Cantidad por Grupo</div>
                <?php  endwhile;   
                    $queryConfig = ("SELECT * FROM detalles_config as dc INNER JOIN carreras as c ON dc.idCarrera = c.idCarrera WHERE dc.idConfig = $config");                       
                    $resultadoCon =mysqli_query($db, $queryConfig);
                    while($row = mysqli_fetch_assoc($resultadoCon)):?>
                        <input type="hidden" name="<?php echo $row ["idConfig"] ;?>" value="ID">
                        <div class="table__item"><?php echo $row ["nomCarrera"] ;?></div>
                        <div class="table__item"><?php echo ('<input name="'.$row ["idCarrera"].'x"  value = "'.$row ["cantidadGrupos"].'" type="number" align="right" style="text-align:right;" required min="1" max="10" required placeholder="Ingresa la cantidad de grupos">');?></div>
                        <div class="table__item"><?php echo ('<input name=" '.$row ["idCarrera"].'" value = "'.$row ["num_Alumnos"].'" type="number" align="right" style="text-align:right;" required min="1" max="50" required placeholder="Ingresa la cantidad de elementos por grupo">');?></div>
                    <?php endwhile;
                    echo ('<input type="submit" value="Modificar Configuración" class="btnRCT" >');

                    echo ('
                        </div> 
                    </div>');
            }  
        }?>          
    </form>
</main>
<?php 
    inlcuirTemplate('footer');
    if ($ban && $_SERVER['REQUEST_METHOD']==="POST") {
        echo "<script>exito('Configuración Modificada');</script>";
    }
?>

