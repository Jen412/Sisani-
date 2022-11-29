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
        $nombreCarrera = $_POST['nombre'];

        if (!$nombreCarrera) {
            $errores [] = "El nombre de la Carrera es Obligatoria";
        }
        if (!$idCarrera) {
            $errores [] = "Es necesario Seleccionar una Carrera";
        }
        if (empty($errores)) {
            $nombreCarrera=strtoupper($nombreCarrera);
            $query ="UPDATE `carreras` SET `nomCarrera`='${nombreCarrera}' WHERE idCarrera = ${idCarrera}";
            $resultado = mysqli_query($db, $query);
            if ($resultado) {
                header("location: /admin/Gestionar%20Carreras/GestionarCarreras.php?alerta=1");
            }
        }
    }
?>
<main class="modificarCarrera">
    <section class="w80">   
        <h1>Modificar Carrera</h1>
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

            <div class="nombreCarrera">
                <label for="nombre">Nombre Carrera</label>
                <input type="text" name="nombre" id="nombre" required placeholder="Nombre de la Carrera">
            </div>                
            <input class="modificar" type="submit" value="Modificar">
        </form>
    </section>
</main>
<?php 
    inlcuirTemplate('footer');
    
?>