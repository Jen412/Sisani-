<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    if (!$auth) {
        header('location: /');
    }
    inlcuirTemplate('header');
    require "../../includes/config/database.php";
    $db =conectarDB();
    $queryMat ="SELECT * FROM materias";
    $resultadoMat =mysqli_query($db, $queryMat);

    $grupo="";
    $materia="";
    $carrera="";

    if ($_SERVER['REQUEST_METHOD']=="POST") {
        $grupo = $_POST['GrupoS'];
        $materia=$_POST['materiaS'];
        $carrera=$_POST['carreraS'];
    }
?>
<main class="registrarCal">
    <section>
        <h1>Registrar Calificaciones</h1>
        <form>
            <div class="linea">
                <div class="carrera">
                    <label for="">Selecciona Carrera</label>
                    <select name="v" id="carreraS">
                        <option value="" disabled selected>--Seleccione Carrera--</option>    
                    </select>
                </div>
                <div class="materia">
                    <label for="">Selecciona Materia</label>
                    <select name="materiaS" id="materiaS">
                        <option value="" disabled selected>--Seleccione Materia--</option>    
                        <?php while($materia = mysqli_fetch_assoc($resultadoMat)):?>
                            <option value="<?php echo $materia['idMateria'];?>"><?php echo $materia['nombre_Mat'];?></option>
                        <?php endwhile;?>
                    </select>
                </div>
                <div class="grupo">
                    <label for="">Grupo</label>
                    <select name="GrupoS" id="GrupoS">
                        <option value="" disabled selected>--Seleccione Grupo--</option>    
                    </select>
                </div>
                <input type="submit" value="Buscar">
            </div>
        </form>
    </section>
</main>
<?php 
    inlcuirTemplate('footer');
?>