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

    if ($_SERVER['REQUEST_METHOD']==="POST") {
        $ficha =$_POST['numFicha'];
        $calificacion=$_POST['prom'];
        $query ="UPDATE dficha SET alupro={$calificacion} WHERE alufic = {$ficha};";
        $resultado = mysqli_query($db, $query);
        if ($resultado) {
            header('location: /');
        }
    }
?>
<main class="modPromCen">
    <section class="w80">
        <h1>Modificar Promedio Alumno</h1>
        <form method="POST">
        <div class="numFicha">
                <label for="numFicha">Número de Ficha: </label>
                <select name="numFicha" id="numFicha" onchange="buscarAlumnoProm(event);">
                    <option value="" disabled selected>--Seleccione Ficha--</option>
                    <?php while($alumno = mysqli_fetch_assoc($resultado)):?>
                        <option value="<?php echo $alumno['alufic']?>"><?php echo $alumno['alufic']?></option>        
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="nomAlumno">
                <label for="nomAlumno">Nombre: </label>
                <input type="text" name="nomAlumno" id="nomAlumno" disabled>
            </div>
            <div class="prom">
                <label for="prom">Promedio: </label>
                <input type="number" name="prom" id="prom">
            </div>
            <div class="but">
                <input type="submit" value="Enviar">
            </div>
        </form>
    </section>
</main>
<?php 
    inlcuirTemplate('footer');
?>