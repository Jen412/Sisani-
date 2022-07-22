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
    $db =conectarDB();

    $queryConfig = "SELECT * FROM config";
    $resultadoCon = mysqli_query($db, $queryConfig);
?>

<main class="g_config">
    <h1>Ver Configuración</h1>
    <table class="tabla">
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Detalles</th>
        </tr> 
        <?php while ($row = mysqli_fetch_array($resultadoCon)){?>
            <form method="POST">
                <?php echo ('<input name = "'.$row['idConfig'].'" type="hidden"')?><!--Aquí me tiene que guardar el id--->
                <tr>
                    <th><?php echo $row['idConfig']?></th>
                    <th><?php echo $row['nombre']?></th>
                    <th><?php echo $row['descripcion']?></th>
                    <th><input type="submit" value="Visualizar"></th>
                </tr>
            </form>
        <?php }?>
    </table>

    <div class="Con">
        <div class = "container-table-con">
            <?php  
                if ($_SERVER['REQUEST_METHOD']=="POST") {//Se reciben los datos del formulario con el imput hidden seleccion
                    
                    foreach($_POST as $key => $value){
                        $queryCon ="SELECT nombre FROM config WHERE idConfig = $key";
                        $resultadoCon = mysqli_query($db, $queryCon);
                        while($row = mysqli_fetch_assoc($resultadoCon)){
                            echo ('<div class="table__title">');
                            echo ($row ["nombre"]);
                            echo ('</div>');
                        }
                    }
                    echo ('<div class="table__header">Carrera</div>');
                    echo ('<div class="table__header">Cantidad de grupos</div>');
                    echo ('<div class="table__header">Cantidad por Grupo</div>');
                    foreach($_POST as $key => $value){//Obtengo el id de la configuración que se selecciono en un foreach de una vuelta 
                    $queryConfig = ("SELECT c.nomCarrera, dc.cantidadGrupos, dc.num_Alumnos FROM detalles_config as dc INNER JOIN carreras as c ON dc.idCarrera = c.idCarrera WHERE dc.idConfig = $key");                       
                    $resultadoCon =mysqli_query($db, $queryConfig);
                    while($row = mysqli_fetch_assoc($resultadoCon)): 
            ?>
                        <div class="table__item"><?php echo $row["nomCarrera"] ;?></div>
                        <div class="table__item"><?php echo $row["cantidadGrupos"];?></div>
                        <div class="table__item"><?php echo $row["num_Alumnos"] ;?></div>
                <?php endwhile; }}?>
        </div> 
    </div>
</main>
<?php 
    inlcuirTemplate('footer');
?>

