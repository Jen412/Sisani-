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

    $queryCon ="SELECT * FROM config";
    $resultadoCon =mysqli_query($db, $queryCon);
    
    $queryCarreras= "SELECT * FROM carreras";
    $resultadoCarreras =mysqli_query($db, $queryCarreras);
    $config=$_GET['config']?? null;
    if ($_SERVER['REQUEST_METHOD']=="POST" && $_POST['tipoForm'] === "configuraciones") {
        $carreras=[];
        foreach ($_POST as $key => $value) {
            if (is_numeric($key)) {
                $carreras [] = $value;
            }
        }
        header("location: \admin\Gestionar Configuraciones\ModificarConfig2.php?config=${config}&carreras=".serialize($carreras));
    }  
?>
<main class="g_config">
    <h1>Modificar Configuración</h1>
    <form method="GET">
        <div class="nombreCo">
            <label >Nombre de la configuración</label>
            <select name="config" id="config" required>
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
            
            
            if($config != null){
                $queryDetalleCon = "SELECT * FROM config WHERE config.idConfig = $config";
                $resultadoDetalleCon =mysqli_query($db, $queryDetalleCon);
                $row = mysqli_fetch_assoc($resultadoDetalleCon); ?>
                    <div class="modificarC">
                        <div class="id">
                            <label for="">Id Configuración</label>
                             <input type="text"value="<?php echo $row["idConfig"] ;?>" disabled selected>
                        </div>
                        <div class="nameConfig">
                            <label>Nombre de la Configuración</label>
                            <?php echo ('<input name="'.$row["idConfig"].'n" disabled  value = "'.$row["nombre"].'" type="text" required> ');?>
                        </div>
                        <div class="des">
                            <label>Descripción</label>
                            <?php echo ('<textarea name="'.$row["idConfig"].'d" disabled value="'.'"name="" id="" cols="48" rows="5"  >'.$row["descripcion"].'</textarea>');?>
                        </div>
                        <h3 class="tituloSel">Seleccion de Materias para proceso</h3>
                    </div>
                    <div class="centrado">
                        <div class="checks">
                            <?php while($carrera = mysqli_fetch_assoc($resultadoCarreras)):?>
                                <div class="id<?php echo $carrera["idCarrera"]?>">
                                    <input type="checkbox" name="<?php echo $carrera["idCarrera"]?>" value="<?php echo $carrera["idCarrera"]?>">    
                                    <label for=""><?php echo $carrera['nomCarrera']?></label>
                                </div>
                            <?php endwhile;?>
                        </div>
                    </div>
                    <button class="seleccion" type="submit">
                        Seleccionar
                    </button>
     
            <?php
            }  
        }?>          
    </form>
</main>
<?php 
    inlcuirTemplate('footer');
    // if ($ban && $_SERVER['REQUEST_METHOD']==="POST") {
    //     echo "<script>exito('Configuración Modificada');</script>";
    // }
?>

