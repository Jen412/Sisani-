<?php  
    require "../../includes/funciones.php";
    require "../../includes/config/database.php";
    inlcuirTemplate('header');
    $db = conectarDB();

    $nombreCarrera = "";
    $idCarrera= "";
    $errores = [];
    $query= "SELECT * FROM carreras";
    $resultado=mysqli_query($db, $query) ?? null;

    echo ($resultado!=null);
    if ($_SERVER['REQUEST_METHOD']==="POST"){
        $idCarrera= $_POST['carrera'];
        if (!$idCarrera) {
            $errores [] = "Es necesario Seleccionar una Carrera";
        }
        if (empty($errores)) {
            $query ="DELETE FROM `carreras` WHERE idCarrera= ${idCarrera}";
            $resultado = mysqli_query($db, $query);
            if ($resultado) {
                header("location: /admin/Gestionar%20Carreras/GestionarCarreras.php?alerta=2");
            }
        }
    }
?>
<main class="eliminarCarrera">
    <section class="w80">   
        <h1>Eliminar Carrera</h1>
        <form method="post">
            <?php foreach($errores as $error): ?>
                <div class="alerta error">
                    <?php  echo $error; ?>
                </div>
            <?php    endforeach;?> 
            <div class="carrera">
                <label for="carrera">Seleccione Carrera</label>
                <select name="carrera" id="carrera">
                    <option value="" disabled selected>--Seleccione--</option>
                    <?php while($carrera = mysqli_fetch_assoc($resultado)):?>
                        <option value="<?php echo $carrera["idCarrera"]?>"><?php echo $carrera["nomCarrera"]?></option>
                    <?php endwhile;?>
                </select>
            </div>

            <input class="eliminar" type="submit" value="Eliminar">
        </form>
    </section>
</main>
<?php 
    inlcuirTemplate('footer');
    
?>