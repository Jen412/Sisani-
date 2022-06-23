<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    require "../../includes/config/database.php";
    if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');
    $db = conectarDB();
    $query = "SELECT * FROM dficha";
    $resultado = mysqli_query($db, $query);
    $array = array();
    if ($resultado) {
        while ($row = mysqli_fetch_array($resultado)) {
            array_push($array, $row['alufic']);
        }
    }

    if ($_SERVER['REQUEST_METHOD']==="POST") {
        $ficha =$_POST['numFicha'];
        $calificacion=$_POST['prom'];
        $query ="UPDATE dficha SET calificacionCeneval={$calificacion} WHERE alufic = {$ficha};";
        $resultado = mysqli_query($db, $query);
        if ($resultado) {
            header('location: /admin/Funciones Secundarias/FuncionesSec.php'); 
            die();
        }
    }
?>
<main class="modPromCen">
    <section class="w80">
        <h1>Modificar Promedio Examen Cenval</h1>
        <form method="POST">
            <div class="numFicha">
                <label for="numFicha">Número de Ficha: </label>
                <input type="text" name="numFicha" id="numFicha"  onchange="buscarAlumnoProm(event);">
                
            </div>
            <div class="nomAlumno">
                <label for="nomAlumno">Nombre: </label>
                <input type="text" name="nomAlumno" id="nomAlumno" disabled>
            </div>
            <div class="prom">
                <label for="prom">Promedio Ceneval: </label>
                <input type="number" name="prom" id="prom">
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
?>