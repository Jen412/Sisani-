<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');
    require "../../includes/config/database.php";//Dirección relativa donde se encuentra la conexion a la db
    $db =conectarDB();//Funcion dentro que permite la conexión

    $materiaSel="";//variables de tipo cadena
    $carreraSel="";
    $grupo="";
    $btnRC="";
    $auxMateria = "";
  
    $queryCar ="SELECT * FROM carreras";//Las variables empiezan por $ y pueden almacenar instrucciones SQL
    $queryMat ="SELECT * FROM materias";
    $queryGru ="SELECT * FROM grupos";
   
    $resultadoCar =mysqli_query($db, $queryCar);//mysqli_query necesita de un parametro para establecer la conexion y de otro 
    $resultadoMat =mysqli_query($db, $queryMat);//en forma de un query sql para interactuar con la db
    $resultadoGru =mysqli_query($db, $queryGru);

    
?>
<main class="registrarCal">
    <section>
        <h1>Registrar Calificaciones</h1>
        <form method="GET"><!-- Se debe de poner el metodo post como si fuese un formulario-->
            <input type="hidden" name="tipoForm" value="seleccion"><!--Sive para comunicar el formulario donde se seleecionan los parametros para mostrarlos en la tabla-->
            <div class="linea">
                <div class="carrera">
                    <label for="carreraS">Selecciona Carrera</label>
                    <select name="carreraS" id="carreraS">
                        <option value=""disabled selected>--Seleccione Carrera--</option>  
                        <?php while($carrera = mysqli_fetch_assoc($resultadoCar)):?><!--como es son varias carreras se guarda la seleccionada en una variable -->
                            <option <?php echo ($carreraSel===$carrera['idCar'] ? 'selected' :'');?> value="<?php echo $carrera['idCar'];?>"><!--la variable contiene referenciando a la db y el query que se esta realizando-->
                                <?php echo $carrera['nombcar'];?><!---para mostrar el resultado en pantalla se muestra en una etiqueta del mismo tipo-->
                            </option><!--con la impresion de la variable de la carrera dentro del while mediante el nombre del campo de la tabla-->
                            <!--cuando se selecciona una opcion se presenta el nombre en base a su id, primero se acede al id y despues al nombre-->
                        <?php endwhile;?>  
                    </select>
                </div>
                <div class="materia">
                    <label for="materiaS">Selecciona Materia</label>
                    <select name="materiaS" id="materiaS">
                        <option value=""disabled selected>--Seleccione Materia--</option>    
                        <?php while($materia = mysqli_fetch_assoc($resultadoMat)):?>
                            <option <?php echo $materiaSel===$materia['idMateria'] ? 'selected' :''; ?>  value="<?php echo $materia['idMateria'];?>"><!---El valor contiene el id de la materia que seleccionemos-->
                                <?php echo $materia['nombre_Mat'];?><!--Se imprime lo que se eligio-->
                            </option>
                        <?php endwhile;?>
                    </select><!--Se envia el id del select desde el formulario conteniendo el valor del id de la materia seleccionada--->
                </div>
                <div class="grupo">
                    <label for="GrupoS">Grupo</label>
                    <select name="GrupoS" id="GrupoS">
                        <option value="" disabled selected>--Seleccione Grupo--</option>    
                        <!-- <option value="A">A</option>
                        <option value="B">B</option> -->
                        <?php 
                            $contA =0;
                            $contB =0;
                            $contC =0;
                            $contD =0;
                            while($grupo = mysqli_fetch_assoc($resultadoGru)){//Para determinar si en existen otros grupos en la db en un futuro
                                if ($grupo['letraGrupo']=='A') {
                                    $contA++;
                                }
                                if ($grupo['letraGrupo']=='B') {
                                    $contB++;
                                }
                                if ($grupo['letraGrupo']=='C') {
                                    $contC++;
                                }
                                if ($grupo['letraGrupo']=='D') {
                                    $contD++;
                                }
                            }
                            if ($contA > 0) {
                                echo '<option value="A">A</option>';
                            }
                            if ($contB > 0) {
                                echo '<option value="B">B</option>';
                            }
                            if ($contC > 0) {
                                echo '<option value="C">C</option>';
                            }
                            if ($contD > 0) {
                                echo '<option value="D">D</option>';
                            }
                        ?>
                    </select>
                </div>
                <input type="submit" value="Buscar" name="btnRC" id="btnRC">
            </div>
        </form>
        <form method="POST">
            <input type="hidden" name="tipoForm" value="calificaciones">
            <input type="hidden" name="carreraSeleccionada" value="<?php echo $carreraSel;?>">
            <input type="hidden" name="materiaSeleccionada" value="<?php echo $materiaSel;?>">
            <input type="hidden" name="grupoSel" value="<?php echo $grupo;?>">
            <div class = "container-table">
                <div class="table__header">Ficha</div>
                <div class="table__header">Nombre</div>
                <div class="table__header">Calificación</div>
                
                <?php  
                    if ($_SERVER['REQUEST_METHOD']=="GET") {//se reciben los datos del formulario con el imput hidden seleccion 
                        $materiaSel=$_GET['materiaS'] ?? null;
                        $carreraSel=$_GET['carreraS']?? null;
                        $grupo = $_GET['GrupoS']?? null;

                        if ($materiaSel!=null) {
                            $queryBtn = ("SELECT d.alufic, d.alunom, d.aluapp, d.aluapm, cl.calif FROM dficha as d INNER JOIN calificaciones as cl ON cl.alufic = d.alufic INNER JOIN grupos as g ON d.alufic = g.alufic WHERE g.letraGrupo = '$grupo'AND cl.id_MateriaG IN (SELECT mg.id_MateriaG FROM calificaciones as cl INNER JOIN materia_grupo as mg ON cl.id_MateriaG = mg.id_MateriaG WHERE mg.idMateria = $materiaSel AND d.alufic IN (SELECT d.alufic FROM carreras as c INNER JOIN dficha as d ON c.idCar = d.carcve1 WHERE d.carcve1 = $carreraSel));");                       
                            $resultadoBtn =mysqli_query($db, $queryBtn);
                            while($btnRC = mysqli_fetch_assoc($resultadoBtn)): 
                                ?>
                                            <div class="table__item"><?php echo ($btnRC["alufic"]);?></div>
                                            <div class="table__item"><?php echo ($btnRC["alunom"]);echo ("  "); echo ($btnRC["aluapp"]); echo ("  ");echo ($btnRC["aluapm"]);?></div>
                                            <div class="table__item"><?php echo ('<input name="'.$btnRC["alufic"].'" type="number" required>');?></div>
                                    <?php endwhile;
                        }    
                }?>
                <input type="submit" value="Registrar Calificaciones" class="btnRCT">
            </div>  
        </form>
        <?php 
            if ($_SERVER['REQUEST_METHOD']=="POST" && $_POST['tipoForm'] === "calificaciones") {
                $auxMateria = $_GET['materiaS']?? null;
                $carrera = $_GET['carreraS']?? null;
                $grupo = $_GET['GrupoS']?? null;
            }
        ?>
    </section>
</main>
<?php 
    inlcuirTemplate('footer');//INSERT INTO calificaciones values calif where alufic = $
?>           

