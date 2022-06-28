<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');
    require "../../includes/config/database.php";//Dirección relativa donde se encuentra la conexion a la db
    $db =conectarDB();//Funcion dentro que permite la conexión

    $materia="";//variables de tipo cadena
    $carrera="";
    $grupo="";
    $btnRC="";

    $queryCar ="SELECT * FROM carreras";//Las variables empiezan por $ y pueden almacenar instrucciones SQL
    $queryMat ="SELECT * FROM materias";
    $queryGru ="SELECT * FROM grupos";
   
    $resultadoCar =mysqli_query($db, $queryCar);//mysqli_query necesita de un parametro para establecer la conexion y de otro 
    $resultadoMat =mysqli_query($db, $queryMat);//en forma de un query sql para interactuar con la db
    $resultadoGru =mysqli_query($db, $queryGru);

    if ($_SERVER['REQUEST_METHOD']=="POST" && $_POST['tipoForm'] === "calificaciones") {
        $carrera = $_GET['carreraS']?? null;
        $materia = $_GET['materiaS']?? null;
        $grupo = $_GET['GrupoS']?? null;   

        $calificaciones = [];
        foreach($_POST as $key => $value){
            if (is_int($key)) {
                $calificaciones[$key] = $value;
            }    
        }
        foreach($calificaciones as $key => $value){
            $queryGrupo = "SELECT idGrupo FROM grupos WHERE alufic = '{$key}' AND letraGrupo = '{$grupo}'";
            $resultadoGrupo  = mysqli_query($db, $queryGrupo);
            $idGrupo = mysqli_fetch_assoc($resultadoGrupo)['idGrupo'];
            $queryMateriaG = "SELECT id_MateriaG FROM materia_grupo WHERE idMateria = '{$materia}' AND idGrupo = '{$idGrupo}'";
            $resultadoMateriaGrupo = mysqli_query($db, $queryMateriaG);

            $materiaG= mysqli_fetch_assoc($resultadoMateriaGrupo)['id_MateriaG'];
            if ($materiaG) {
                $queryinsert = "UPDATE calificaciones set calif ='{$value}' WHERE alufic = '{$key}' AND id_MateriaG = '{$materiaG}'"; 
                $resultado = mysqli_query($db, $queryinsert);
                if ($resultado) {
                    header("location: /admin/index.php");
                }
            }
        }
    }
?>
<main class="registrarCal">
    <section>
        <h1>Modificar Calificaciones</h1>
        <form method="GET"><!-- Se debe de poner el metodo post como si fuese un formulario-->
            <input type="hidden" name="tipoForm" value="seleccion"><!--Sive para comunicar el formulario donde se seleecionan los parametros para mostrarlos en la tabla-->
            <div class="linea">
                <div class="carrera">
                    <label for="">Selecciona Carrera</label>
                    <select name="carreraS" id="carreraS">
                        <option value="">--Seleccione Carrera--</option>  
                        <?php while($carrera = mysqli_fetch_assoc($resultadoCar)):?><!--como es son varias carreras se guarda la seleccionada en una variable -->
                            <option value="<?php echo $carrera['idCar'];?>"><!--la variable contiene referenciando a la db y el query que se esta realizando-->
                                <?php echo $carrera['nombcar'];?><!---para mostrar el resultado en pantalla se muestra en una etiqueta del mismo tipo-->
                            </option><!--con la impresion de la variable de la carrera dentro del while mediante el nombre del campo de la tabla-->
                            <!--cuando se selecciona una opcion se presenta el nombre en base a su id, primero se acede al id y despues al nombre-->
                        <?php endwhile;?>  
                    </select>
                </div>
                <div class="materia">
                    <label for="">Selecciona Materia</label>
                    <select name="materiaS" id="materiaS">
                        <option value="">--Seleccione Materia--</option>    
                        <?php while($materia = mysqli_fetch_assoc($resultadoMat)):?>
                            <option value="<?php echo $materia['idMateria'];?>"><!---El valor contiene el id de la materia que seleccionemos-->
                                <?php echo $materia['nombre_Mat'];?><!--Se imprime lo que se eligio-->
                            </option>
                        <?php endwhile;?>
                    </select><!--Se envia el id del select desde el formulario conteniendo el valor del id de la materia seleccionada--->
                </div>
                <div class="grupo">
                    <label for="">Grupo</label>
                    <select name="GrupoS" id="GrupoS">
                        <option value="" disabled selected>--Seleccione Grupo--</option>    
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <?php 
                            while($grupo = mysqli_fetch_assoc($resultadoGru)){
                                if ($grupo['letraGrupo']=='C') {
                                    echo '<option value="C">C</option>';
                                }
                                if ($grupo['letraGrupo']=='D') {
                                    echo '<option value="D">D</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
                <input type="submit" value="Buscar" name="btnRC" id="btnRC" onclick="mostrarTabla();">
            </div>
        </form>
        <form method="POST">
            <input type="hidden" name="tipoForm" value="calificaciones">
            <div class= "container-table">
                <div class="table__header">Ficha</div>
                <div class="table__header">Nombre</div>
                <div class="table__header">Calificación</div>
                <?php  
                    if ($_SERVER['REQUEST_METHOD']=="GET") {//se reciben los datos del formulario con el imput hidden seleccion 
                        $carrera=$_GET['carreraS']?? null;
                        $materia=$_GET['materiaS'] ?? null;
                        $grupo = $_GET['GrupoS']?? null;
                        if ($carrera!=null) {
                            $queryBtn = ("SELECT d.alufic, d.alunom, d.aluapp, d.aluapm, cl.calif FROM dficha as d INNER JOIN calificaciones as cl ON cl.alufic = d.alufic INNER JOIN grupos as g ON d.alufic = g.alufic WHERE g.letraGrupo = '$grupo'AND cl.id_MateriaG IN (SELECT mg.id_MateriaG FROM calificaciones as cl INNER JOIN materia_grupo as mg ON cl.id_MateriaG = mg.id_MateriaG WHERE mg.idMateria = $materia AND d.alufic IN (SELECT d.alufic FROM carreras as c INNER JOIN dficha as d ON c.idCar = d.carcve1 WHERE d.carcve1 = $carrera));");                                              
                            $resultadoBtn =mysqli_query($db, $queryBtn);
                            while($btnRC = mysqli_fetch_assoc($resultadoBtn)): 
                ?>
                                <div class="table__item"><?php echo ($btnRC["alufic"]);?></div>
                                <div class="table__item"><?php echo ($btnRC["alunom"]);echo ("  "); echo ($btnRC["aluapp"]); echo ("  ");echo ($btnRC["aluapm"]);?></div>
                                <div class="table__item"><?php echo ('<input name="'.$btnRC["alufic"].'" value = "'.$btnRC["calif"].'" align="right" style="text-align:right;" required min="0" max="100" placeholder="Ingresa una calificación menor o igual a 100"> ');?></div> 
                            <?php endwhile;
                        }    
                }?>
                <input type="submit" value="Modificar Calificaciones" class="btnRCT">
            </div>  
        </form>
    </section>
</main>
<?php 
    inlcuirTemplate('footer');
?>  

       




