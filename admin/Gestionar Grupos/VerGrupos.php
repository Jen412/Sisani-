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
?>
<main class="c_grupos">
    <h1>Grupos</h1>
    <form method="GET">
        <div class="buscarC">
            <label for="sel">Seleccionar Carrera</label>
            <select name="carrera" id="carrera">
                <option value="" disabled selected>--Seleccione Carrera--</option>
                <?php while($carreras = mysqli_fetch_assoc($resultado)):?>
                <option value="<?php echo $carreras['idCar']?>"><?php echo $carreras['nombcar']?></option>
                <?php endwhile;?>
            </select>
            <input type="submit" value="Buscar">
        </div>
    </form>
    <div class="tabla">
        <div class = "container-table">
        <?php  
        if ($_SERVER['REQUEST_METHOD']=="GET") {//se reciben los datos del formulario con el imput hidden seleccion 
            $carrera=$_GET['carrera'] ?? null;
            if ($carrera!=null) {
                $queryRC = "SELECT dficha.alufic, alunom, aluapp, aluapm, grupos.letraGrupo FROM dficha, grupos WHERE dficha.alufic = grupos.alufic AND carcve1= {$carrera};";                       
                $resultadoRC =mysqli_query($db, $queryRC);
                echo ('<div class="table__header">Solicitud</div>');
                echo ('<div class="table__header">Nombre</div>');
                echo ('<div class="table__header">Apellido Paterno</div>');
                echo ('<div class="table__header">Apellido Materno</div>');
                echo ('<div class="table__header">Grupo</div>');
                while($row = mysqli_fetch_assoc($resultadoRC)): ?>
                <div class="table__item"><?php echo $row['alufic'];?></div>
                <div class="table__item"><?php echo $row['alunom'];?></div>
                <div class="table__item"><?php echo $row['aluapp'];?></div>
                <div class="table__item"><?php echo $row['aluapm'];?></div>
                <div class="table__item"><?php echo $row['letraGrupo'];?></div>
                <?php endwhile;
            }    
        }?>
        </div> 
    </div>
</main>
<?php 
    inlcuirTemplate('footer');
?>