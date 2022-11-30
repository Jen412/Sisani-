<?php  
    require "../../includes/funciones.php";
    $auth = estaAutenticado();
    require "../../includes/config/database.php";
    if (!$auth) {
        header('location: /'); die();
    }
    if ($_SESSION['role']!="admin") {
        header('location: /admin/index.php'); 
        die();
    }
    $db = conectarDB();
    inlcuirTemplate('header');

    $config= $_GET["config"];
    $carreras = unserialize($_GET["carreras"])??null;
    $ban = true;

    if ($carreras == null) {
        header("location: /admin/index.php");
    }
    $carrerasStr = "";
    for ($i=0; $i <count($carreras); $i++) { 
        
        if($i != (count($carreras)-1)){
            $carrerasStr.= $carreras[$i].",";
        }
        else{
            $carrerasStr.= $carreras[$i];
        }
    }

    $queryConfig = "SELECT * FROM detalles_config as dc , carreras as c  WHERE c.idCarrera IN (".$carrerasStr.") AND dc.idCarrera = c.idCarrera AND dc.idConfig = ${config};";

    if ($_SERVER['REQUEST_METHOD']=="POST") {
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
            $query= "UPDATE `detalles_config` SET `cantidadGrupos`= 0,`num_Alumnos`= 0 WHERE idConfig= ${config}";
            $resultado = mysqli_query($db,$query);
            // foreach($nombre as $key => $value){
            //     $queryUpdateN = "UPDATE config SET nombre  = '{$value}' WHERE idConfig = '{$keyId}' ";
            //     $resultado = mysqli_query($db, $queryUpdateN);
            // }
            // foreach($descripcion as $key => $value){
            //     $queryUpdateD = "UPDATE config SET descripcion = '{$value}' WHERE idConfig = '{$keyId}' ";
            //     $resultado = mysqli_query($db, $queryUpdateD);
            // }
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
<main class="g_confing2">
    <section class="w80">
        <h1>Colocar Cantidad de Grupos y Cantidad por grupo</h1>
        <form action="" method="post">
            <div class="Con">
                <div class = "container-table-con">
                    <div class="table__header">Carrera</div>
                    <div class="table__header">Cantidad de grupos</div>
                    <div class="table__header">Cantidad por Grupo</div>
                <?php 
                                        
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
                ?>   
        </form>        
    </section>
</main>
<?php 
    inlcuirTemplate('footer');
    if ($ban && $_SERVER['REQUEST_METHOD']==="POST") {
        echo "<script>mostrarAlerta('Configuración Modificada', '\GestionarConfiguraciones.php');</script>";
    }
?>