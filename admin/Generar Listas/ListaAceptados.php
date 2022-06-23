<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    require "../../includes/config/database.php";
    if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');
    $db = conectarDB();
    $query = "SELECT * FROM carreras";
    $resultado = mysqli_query($db, $query);

    if ($_SERVER['REQUEST_METHOD']==="POST") {
        $carrera=$_POST['carrera'];

        
    }
?>
<main class="g_listas">
    <h1>Listas de Aceptados</h1>
    <form method="POST">
        <div class="carreras">
            <label>Selecciona una carrera</label>
            <select name="carrera" id="carrera">
                <option value="" disabled selected>--Seleccione Carrera--</option>
                <?php while($carrera = mysqli_fetch_assoc($resultado)):?>
                    <option value="<?php echo $carrera['idCar']?>"><?php echo $carrera['nombcar']?></option>    
                <?php endwhile;?>
            </select>
        </div>
        <div class="modal">
            <input type="submit" value="Generar Lista">
        </div>
    </form>
</main>
<?php 
    inlcuirTemplate('footer');
?>
