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
    <form method="POST"class="tabla">
        <table >
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Detalles</th>
                </tr> 
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($resultadoCon)){?>
                    <tr>
                        <td><?php echo $row['idConfig']?></td>
                        <td><?php echo $row['nombre']?></td>
                        <td><?php echo $row['descripcion']?></td>
                        <td><input type="radio"  name="idConfig"  id="idConfig" value="<?php echo $row['idConfig']?>"></td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
        <input class="boton" type="submit" value="Visualizar">
    </form> 

    <div class="Con">
        <div class = "container-table-con">
            <?php  
                if ($_SERVER['REQUEST_METHOD']=="POST") {//Se reciben los datos del formulario con el imput hidden seleccion
                    $idConfig=$_POST["idConfig"];
                    foreach($_POST as $key => $value){
                        
                        
                        $queryCon ="SELECT nombre FROM config WHERE idConfig = $key AND idConfig= ${idConfig}";
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
                    //foreach($_POST as $key => $value){//Obtengo el id de la configuración que se selecciono en un foreach de una vuelta 
                    
                    $queryConfig = ("SELECT c.nomCarrera, dc.cantidadGrupos, dc.num_Alumnos FROM detalles_config as dc INNER JOIN carreras as c ON dc.idCarrera = c.idCarrera WHERE dc.idConfig = ${idConfig} AND dc.num_Alumnos !=0;");                       
                    $resultadoCon =mysqli_query($db, $queryConfig);
                    while($row = mysqli_fetch_assoc($resultadoCon)): 
            ?>
                        <div class="table__item"><?php echo $row["nomCarrera"] ;?></div>
                        <div class="table__item"><?php echo $row["cantidadGrupos"];?></div>
                        <div class="table__item"><?php echo $row["num_Alumnos"] ;?></div>
                <?php endwhile; }?>
        </div> 
    </div>
</main>
<?php 
    inlcuirTemplate('footer');
?>

