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
    $query = "SELECT * FROM alumnos";
    $resultado = mysqli_query($db, $query);
    $array = array();
    if ($resultado) {
        while ($row = mysqli_fetch_array($resultado)) {
            array_push($array, $row['solicitud']);
        }
    }
    $ban = true;
    if ($_SERVER['REQUEST_METHOD']==="POST") {
        $ficha =$_POST['numFicha'];
        $calificacion=$_POST['prom'];
        $query ="UPDATE alumnos SET cal_ceneval={$calificacion} WHERE solicitud = {$ficha};";
        $resultado = mysqli_query($db, $query);
        if (!$resultado) {
            $ban = false;
        }
    }
?>
<main class="modPromCen">
    <section class="w80">
        <h1>Modificar Promedio Examen Cenval</h1>
        <form method="POST">
            <div class="numFicha">
                <label for="numFicha">Número de Ficha: </label>
                <input type="text" name="numFicha" id="numFicha"  onchange="buscarAlumno(event);" required>
            </div>
            <div class="nomAlumno">
                <label for="nomAlumno">Nombre: </label>
                <input type="text" name="nomAlumno" id="nomAlumno" disabled>
            </div>
            <div class="prom">
                <label for="prom">Promedio Ceneval: </label>
                <input type="number" name="prom" id="prom" required>
            </div>
            <div class="but">
                <input type="submit" value="Modificar">
            </div>
        </form>
    </section>
</main>

<script type="text/javascript">
$(document).ready(function(){
    let items = <?= json_encode($array);?>; 
    $("#numFicha").autocomplete({
        source : items 
    });
});

</script>
<?php 
    inlcuirTemplate('footer');
    if ($ban && $_SERVER['REQUEST_METHOD']==="POST") {
        echo "<script>exito('Promedio Modificado');</script>";
    }
?>