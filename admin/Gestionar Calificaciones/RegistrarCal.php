<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    require "../../includes/config/database.php";//Dirección relativa donde se encuentra la conexion a la db
    if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');
    $db =conectarDB();//Funcion dentro que permite la conexión
    if($_SESSION['role']!="admin"){
        $rfc = $_SESSION['rfc'];
        $queryMaes = "SELECT idMaestro FROM maestros WHERE rfc = '{$rfc}'";
        $resultadoMaes = mysqli_query($db, $queryMaes);
        $idMaestro = mysqli_fetch_assoc($resultadoMaes)["idMaestro"];
    }
    $queryCar = "SELECT * FROM carreras";
    if ($_SESSION['role'] !="admin") {
        $queryCar ="SELECT DISTINCT carreras.idCarrera, carreras.nomCarrera FROM carreras, maestros, alumnos,grupos, materiagrupo where alumnos.idCarrera =carreras.idCarrera AND grupos.solicitud = alumnos.solicitud && grupos.idGrupo = materiagrupo.idGrupo AND maestros.rfc = '{$rfc}' AND maestros.idMaestro = '{$idMaestro}';";//Las variables empiezan por $ y pueden almacenar instrucciones SQL
    }
    $queryMat ="SELECT * FROM materias";
    $queryGru ="SELECT DISTINCT letraGrupo FROM grupos";
   
    $resultadoCar =mysqli_query($db, $queryCar);//mysqli_query necesita de un parametro para establecer la conexion y de otro 
    if($_SESSION['role'] === "admin"){
        $resultadoMat = mysqli_query($db, $queryMat);
        $resultadoGrup = mysqli_query($db, $queryGru);
    }

    $ban = true;
    if ($_SERVER['REQUEST_METHOD']=="POST" && $_POST['tipoForm'] === "calificaciones") {
        $carrera = $_GET['carreraS']?? null;
        $materia = $_GET['materiaS']?? null;
        $grupo = $_GET['GrupoS']?? null;   

        $calificaciones = [];//Se declara un array 
        foreach($_POST as $key => $value){
            if (is_int($key)) {
                $calificaciones[$key] = $value;//Dentro de la posisicon del array se guarda la ficha y el valor es igual a la calificación
            }    
        }

        foreach($calificaciones as $key => $value){ //Se obtiene el array con las claves y las calificaciones
            $queryGrupo = "SELECT idGrupo FROM grupos WHERE solicitud = '{$key}' AND letraGrupo = '{$grupo}'";//Seleccionamos el idGrupo cuando la ficha es igual a la llave y la letra es igua a la letra elegida
            $resultadoGrupo  = mysqli_query($db, $queryGrupo);
            $idGrupo = mysqli_fetch_assoc($resultadoGrupo)['idGrupo'];//el id sellecionado lo buscamos en la db

            $queryMateriaG = "SELECT idMateriaGrupo FROM materiagrupo WHERE idMateria = '{$materia}' AND idGrupo = '{$idGrupo}'";
            $resultadoMateriaGrupo = mysqli_query($db, $queryMateriaG);
            $materiaG= mysqli_fetch_assoc($resultadoMateriaGrupo)['idMateriaGrupo'];
            
            if ($materiaG) {
                $queryinsert = "INSERT INTO calificaciones(idMateriaGrupo, solicitud, calif) VALUES ('{$materiaG}','{$key}','{$value}')"; 
                $resultado = mysqli_query($db, $queryinsert);
                if (!$resultado) {
                    $ban = false;
                    break;
                }
            }
        }
        if ($ban) {
			header("location: /admin/Gestionar Calificaciones/GestionarCal.php?alerta=1");
			die();
		}
    }
?>
<main class="registrarCal">
    <section>
        <h1>Registrar Calificaciones</h1>
        <form method="GET"><!-- Se debe de poner el metodo post como si fuese un formulario-->
            <input type="hidden" name="tipoForm" value="seleccion"><!--Sive para comunicar el formulario donde se seleecionan los parametros para mostrarlos en la tabla-->
            <div class="linea">
                <div class="carrera">
                    <label for="">Selecciona Carrera</label>
                    <select name="carreraS" id="carreraS"onchange="buscarMaterias('<?php echo $rfc;?>', event);" required>
                        <option value=""disabled selected>--Seleccione Carrera--</option>  
                        <?php while($carrera = mysqli_fetch_assoc($resultadoCar)):?><!--como es son varias carreras se guarda la seleccionada en una variable -->
                            <option value="<?php echo $carrera['idCarrera'];?>"><!--la variable contiene referenciando a la db y el query que se esta realizando-->
                                <?php echo $carrera['nomCarrera'];?><!---para mostrar el resultado en pantalla se muestra en una etiqueta del mismo tipo-->
                            </option><!--con la impresion de la variable de la carrera dentro del while mediante el nombre del campo de la tabla-->
                            <!--cuando se selecciona una opcion se presenta el nombre en base a su id, primero se acede al id y despues al nombre-->
                        <?php endwhile;?>  
                    </select>
                </div>
                <div class="materia">
                    <label for="">Selecciona Materia</label>
                    <select name="materiaS" id="materiaS"onchange="buscarGrupo('<?php echo $rfc;?>', event);" required>
                        <option value=""disabled selected>--Seleccione Materia--</option>    
                        <?php 
                        if ($_SESSION['role']==="admin") {
                            while ($materia = mysqli_fetch_assoc($resultadoMat)): ?>
                                <option value="<?php echo $materia['idMateria'];?>"><?php echo $materia['nombreMateria'];?></option> 
                            <?php endwhile;
                        }
                        ?>
                    </select><!--Se envia el id del select desde el formulario conteniendo el valor del id de la materia seleccionada--->
                </div>
                <div class="grupo">
                    <label for="">Grupo</label>
                    <select name="GrupoS" id="GrupoS" required>
                        <option value="" disabled selected>--Seleccione Grupo--</option>    
                        <?php 
                        if ($_SESSION['role']==="admin") {
                            while($grupo = mysqli_fetch_assoc($resultadoGrup)){
                                if ($grupo['letraGrupo']=='A') {
                                    echo '<option value="A">A</option>';
                                }
                                if ($grupo['letraGrupo']=='B') {
                                    echo '<option value="B">B</option>';
                                }
                                if ($grupo['letraGrupo']=='C') {
                                    echo '<option value="C">C</option>';
                                }
                                if ($grupo['letraGrupo']=='D') {
                                    echo '<option value="D">D</option>';
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
                <input type="submit" value="Buscar" name="btnRC" id="btnRC" >
            </div>
        </form>

        <form method="POST">
            <input type="hidden" name="tipoForm" value="calificaciones">
            <div class = "container-table">
                <?php  
                    if ($_SERVER['REQUEST_METHOD']=="GET") {//se reciben los datos del formulario con el imput hidden seleccion 
                        $carrera=$_GET['carreraS']?? null;
                        $materia=$_GET['materiaS'] ?? null;
                        $grupo = $_GET['GrupoS']?? null;
                        if ($carrera!=null) {
                            $queryCar ="SELECT nomCarrera FROM carreras WHERE idCarrera = $carrera";
                            $resultadoCar = mysqli_query($db, $queryCar);

                            while($row = mysqli_fetch_assoc($resultadoCar)){
                                echo ('<div class="table__title">');
                                echo ($row ["nomCarrera"]);
                                echo ('</div>');
                            }
                            
                            $queryMat ="SELECT nombreMateria FROM materias WHERE idMateria = $materia";
                            $resultadoMat = mysqli_query($db, $queryMat);

                            while($row = mysqli_fetch_assoc($resultadoMat)){
                                echo ('<div class="table__title">');
                                echo ($row ["nombreMateria"]);
                                echo ('</div>');
                            }
                            echo ('<div class="table__title">');
                            echo ($grupo);
                            echo ('</div>');
                            
                            echo ('<div class="table__header">Ficha</div>');
                            echo ('<div class="table__header">Nombre</div>');
                            echo ('<div class="table__header">Calificación</div>');
                            $queryRC = ("SELECT d.solicitud, d.alu_nombre, d.alu_apeP, d.alu_apeM FROM alumnos as d INNER JOIN grupos as g ON d.solicitud = g.solicitud INNER JOIN materiagrupo as mg ON g.idGrupo = mg.idGrupo WHERE g.letraGrupo = '$grupo'AND mg.idMateriaGrupo IN (SELECT mg.idMateriaGrupo FROM materiagrupo as mg WHERE mg.idMateria = $materia AND d.solicitud IN (SELECT d.solicitud FROM carreras as c INNER JOIN alumnos as d ON c.idCarrera = d.idCarrera WHERE d.idCarrera = $carrera));");                       
                            $resultadoRC =mysqli_query($db, $queryRC);
                            while($row = mysqli_fetch_assoc($resultadoRC)): 
                ?>
                                <div class="table__item"><?php echo ($row ["solicitud"]);?></div>
                                <div class="table__item"><?php echo ($row ["alu_nombre"]);echo ("  "); echo ($row["alu_apeP"]); echo ("  ");echo ($row["alu_apeM"]);?></div>
                                <div class="table__item"><?php echo ('<input name="'.$row ["solicitud"].'" type="number" align="right" style="text-align:right;" required min="0" max="100" placeholder="Ingresa una calificación menor o igual a 100">');?></div>
                            <?php endwhile;
                            echo ('<input type="submit" value="Registrar Calificaciones" class="btnRCT">');
                        }    
                }?>
            </div>  
        </form>
    </section>
</main>
<?php 
    inlcuirTemplate('footer');
?>           