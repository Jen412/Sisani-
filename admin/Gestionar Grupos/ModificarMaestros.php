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
    $db = conectarDB();
    $query= "SELECT * FROM materias";
    $resultado = mysqli_query($db, $query);
    $ban = true;
    if ($_SERVER['REQUEST_METHOD']==="POST") {
        // echo "<pre>";
        // var_dump($_POST);
        // echo "</pre>";
        foreach ($_POST as $key => $value) {
            $values = explode("_", $key);
            $grupo =$values[0];
            $carrera = $values[1];
            $materia=$_GET['materiaS'] ?? null;
            // $query ="INSERT INTO materiagrupo(idMateria, idMaestro, idGrupo) VALUES ('{$materia}','{$value}','{$grupo}')";  
            // $resultado = mysqli_query($db, $query);
            // if ($resultado) {
            //     header("location: /admin/index.php");
            //     die();
            // }
            if (!$key) {
				$ban = false;
			}
        }
    }
?>
<main class="c_grupos">
    <h1>Modificar Asignación de Maestros Para El Curso</h1>
    <form method="GET">
        <div class="buscar">
            <label for="sel">Seleccionar Materia</label>
            <select name="materiaS" id="materiaS" required>
                <option value="" disabled selected>--Seleccione Materia--</option>
                <?php while($mat = mysqli_fetch_assoc($resultado)):?>
                <option value="<?php echo $mat['idMateria']?>"><?php echo $mat['nombreMateria']?></option>
                <?php endwhile;?>
            </select>
            <input type="submit" value="Buscar">
        </div>
    </form>
    <form  method="post" class="asigMaes">
        <div class = "container-table">
        <?php  
        if ($_SERVER['REQUEST_METHOD']=="GET") {//se reciben los datos del formulario con el imput hidden seleccion 
            $materia=$_GET['materiaS'] ?? null;
            $queryNomM ="SELECT nombreMateria FROM materias WHERE idMateria ={$materia}";
            $resultadoMat = mysqli_query($db,$queryNomM);
            if ($materia!=null) {
                $nombreMat = mysqli_fetch_assoc($resultadoMat)['nombreMateria'];
                $queryRC = "SELECT DISTINCT carreras.idCarrera, nomCarrera, grupos.letraGrupo, idGrupo FROM carreras, grupos, alumnos WHERE grupos.solicitud = alumnos.solicitud AND carreras.idCarrera = alumnos.idCarrera;";                       
                $resultadoRC =mysqli_query($db, $queryRC);
                while($row = mysqli_fetch_assoc($resultadoRC)): 
                    $queryGrup ="SELECT idMaestro FROM materiagrupo WHERE idMateria= {$materia} AND idGrupo = '{$row ["idGrupo"]}';";
                    $result = mysqli_query($db, $queryGrup);
                    $idMaest = mysqli_fetch_assoc($result)['idMaestro'];
                ?>
                <?php echo ('<div class="table__header">'.$row['nomCarrera'].'</div>');?>
                <div class="contRow">
                    <div class="table__item"><?php echo ("Grupo ".$row ["letraGrupo"]);?></div>
                    <div class="table__item"><?php echo($nombreMat);?></div>
                    <div class="table__item"><?php 
                    
                    echo ('<select name="'.$row ["idGrupo"]." ".$row['idCarrera'].'" align="right" style="text-align:center;" required>');
                    $queryMaestros = "SELECT * FROM maestros";
                    $resultadoMaes = mysqli_query($db, $queryMaestros);
                    while ($maestros = mysqli_fetch_assoc($resultadoMaes)): ?>
                        <option <?php echo$idMaest ===$maestros['idMaestro'] ? 'selected' :'';?>  value="<?php echo $maestros['idMaestro']?>"><?php echo $maestros['nombreMaestro']?></option>
                    <?php
                    endwhile;
                    echo "</select>";
                    ?></div>
                </div>
                <?php endwhile;
                echo ('<input type="submit" value="Modificar Asignación" class="btnRCT">');
            }    
        }?>
        </div> 
    </form>
</main>
<?php 
    inlcuirTemplate('footer');
    if ($ban && $_SERVER['REQUEST_METHOD']==="POST") {
        echo "<script>exito('Modificación Exitosa');</script>";
    }
?>