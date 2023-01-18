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
    $query = "SELECT * FROM carreras";
    $resultado = mysqli_query($db, $query);
?>
<main class="c_grupos">
    <h1>Grupos</h1>
    <form method="GET">
        <div class="buscarC">
            <label for="sel">Seleccionar Carrera</label>
            <select required name="carrera" id="carrera" required>
                <option value="" disabled selected>--Seleccione Carrera--</option>
                <?php while($carreras = mysqli_fetch_assoc($resultado)):?>
                <option value="<?php echo $carreras['idCarrera']?>"><?php echo $carreras['nomCarrera']?></option>
                <?php endwhile;?>
            </select>
            <input type="submit" value="Buscar">
        </div>
    </form>
    <div class="tabla-grupos">
        <div class = "container-table">
        <?php  
        if ($_SERVER['REQUEST_METHOD']=="GET") {//se reciben los datos del formulario con el imput hidden seleccion 
            $carrera=$_GET['carrera'] ?? null;
            if ($carrera!=null) {

                $queryCar ="SELECT nomCarrera FROM carreras WHERE idCarrera = $carrera";
                $resultadoCar = mysqli_query($db, $queryCar);

                while($row = mysqli_fetch_assoc($resultadoCar)){
                    echo ('<div class="table__title">');
                    echo ($row ["nomCarrera"]);
                    echo ('</div>');
                }
                        
                $queryRC = "SELECT alumnos.solicitud, alu_nombre, alu_apeP, alu_apeM, grupos.letraGrupo FROM alumnos, grupos WHERE alumnos.solicitud = grupos.solicitud AND idCarrera= {$carrera};";                       
                $resultadoRC =mysqli_query($db, $queryRC);
                echo ('<div class="table__header">Solicitud</div>');
                echo ('<div class="table__header">Nombre</div>');
                echo ('<div class="table__header">Apellido Paterno</div>');
                echo ('<div class="table__header">Apellido Materno</div>');
                echo ('<div class="table__header">Grupo</div>');
                while($row = mysqli_fetch_assoc($resultadoRC)): ?>
                    <div class="table__item"><?php echo $row['solicitud'];?></div>
                    <div class="table__item"><?php echo $row['alu_nombre'];?></div>
                    <div class="table__item"><?php echo $row['alu_apeP'];?></div>
                    <div class="table__item"><?php echo $row['alu_apeM'];?></div>
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