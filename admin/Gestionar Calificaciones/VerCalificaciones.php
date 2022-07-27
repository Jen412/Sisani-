<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');
    require "../../includes/config/database.php";//Dirección relativa donde se encuentra la conexion a la db
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
   
    $resultadoCar =mysqli_query($db, $queryCar);//mysqli_query necesita de un parametro para establecer la conexion y de otro ;
    if($_SESSION['role'] === "admin"){
        $resultadoMat = mysqli_query($db, $queryMat);
        $resultadoGrup = mysqli_query($db, $queryGru);
    }

?>
<main class="registrarCal">
    <section>
        <h1>Ver Calificaciones</h1>
        <form method="POST"><!-- Se debe de poner el metodo post como si fuese un formulario-->
            <div class="linea">
                <div class="carrera">
                    <label for="">Selecciona Carrera</label>
                    <select name="carreraS" id="carreraS"onchange="buscarMaterias('<?php echo $rfc;?>', event);"required>
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
                <input type="submit" value="Buscar" name="btnRC" id="btnRC" onclick="mostrarTabla();">
            </div>
            <div class = "container-table">
                <?php  
                    if ($_SERVER['REQUEST_METHOD']=="POST") {//se reciben los datos del formulario con el imput hidden seleccion 
                        $materia=$_POST['materiaS'];
                        $carrera=$_POST['carreraS'];
                        $grupo = $_POST['GrupoS'];
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
                        $queryBtn = ("SELECT d.solicitud, d.alu_nombre, d.alu_apeP, d.alu_apeM, cl.calif FROM alumnos as d INNER JOIN calificaciones as cl ON cl.solicitud = d.solicitud INNER JOIN grupos as g ON d.solicitud = g.solicitud WHERE g.letraGrupo = '$grupo'AND cl.idMateriaGrupo IN (SELECT mg.idMateriaGrupo FROM calificaciones as cl INNER JOIN materiagrupo as mg ON cl.idMateriaGrupo = mg.idMateriaGrupo WHERE mg.idMateria = $materia AND d.solicitud IN (SELECT d.solicitud FROM carreras as c INNER JOIN alumnos as d ON c.idCarrera = d.idCarrera WHERE d.idCarrera = $carrera));");                       
                        $resultadoBtn =mysqli_query($db, $queryBtn);
                        while($btnRC = mysqli_fetch_assoc($resultadoBtn)): 
                ?>
                            <div class="table__item"><?php echo $btnRC["solicitud"] ;?></div>
                            <div class="table__item"><?php echo $btnRC["alu_nombre"];echo "  "; echo $btnRC["alu_apeP"]; echo "  ";echo $btnRC["alu_apeM"];?></div>
                            <div class="table__item"><?php echo $btnRC["calif"] ;?></div>
                    <?php endwhile; }?>
            </div> 
        </form>
    </section>
</main>
<?php 
    inlcuirTemplate('footer');
?>

