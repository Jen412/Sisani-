<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');
    require "../../includes/config/database.php";
    $db =conectarDB();

    $queryCon ="SELECT * FROM config";
    $resultadoCon =mysqli_query($db, $queryCon);
    $configuracion = "";

    if ($_SERVER['REQUEST_METHOD']=="POST" && $_POST['tipoForm'] === "configuraciones") {

        var_dump($_POST);
        /*
        $conf = [];
        foreach($_POST as $key => $value){
            if (is_int($key)) {
                $conf[$key] = $value;
            }    
        }
        foreach($conf as $key => $value){
            var_dump($key);
            var_dump($value);
        }
        */
    }
    
    
?>
<main class="g_config">
    <h1>Modificar Configuración</h1>
    <form method="GET">
        <div class="nombreCo">
            <label >Nombre de la configuración</label>
            <select name="config" id="config">
                <option value="">--Seleccione la Configuración--</option>  
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
                            <input type="text" value="<?php echo $row["nombre"] ;?>" disabled selected>
                        </div>
                        <div class="des">
                            <label>Descripción</label>
                            <textarea placeholder="<?php echo $row["descripcion"] ;?>"name="" id="" cols="48" rows="5" disabled selected></textarea>
                        </div>
                    </div>
                    
                    <div class="Con">
                        <div class = "container-table-con">
                            <div class="table__header">Carrera</div>
                            <div class="table__header">Cantidad de grupos</div>
                            <div class="table__header">Cantidad por Grupo</div>
                <?php  endwhile;   
                    $queryBtn = ("SELECT * FROM detalles_config as dc INNER JOIN carreras as c ON dc.idCar = c.idCar WHERE dc.idConfig = $config AND c.idCar != 18 AND c.idCar !=25");                       
                    $resultadoBtn =mysqli_query($db, $queryBtn);
                    while($btnRC = mysqli_fetch_assoc($resultadoBtn)):?>
                        <div class="table__item"><?php echo $btnRC["nombcar"] ;?></div>
                        <div class="table__item"><?php echo ('<input name="'.$btnRC["idCar"].'" value = "'.$btnRC["cant_Grupos"].'" align="right" style="text-align:right;" required min="0" max="100" > ');?></div>
                        <div class="table__item"><?php echo ('<input name="'.$btnRC["idCar"].'" value = "'.$btnRC["cant_Elem_Grupo"].'" align="right" style="text-align:right;" required min="0" max="100"> ');?></div>
                    <?php endwhile;
            }  
        }?>
                        <div class="modificarConfig"><input type="submit" value="Modificar Configuración"></div>
                    </div> 
                </div>
        
    </form>
</main>
<?php 
    inlcuirTemplate('footer');
?>

