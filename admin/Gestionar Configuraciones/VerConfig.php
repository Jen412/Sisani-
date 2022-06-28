<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');
    require "../../includes/config/database.php";
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
        <?php while ($row = mysqli_fetch_array($resultadoCon)){ ?>
            <form method="POST">
                <?php echo ('<input name = "'.$row['idConfig'].'" type="hidden"')?>
                <tr>
                    <th><?php echo $row['idConfig']?></th>
                    <th><?php echo $row['nombre']?></th>
                    <th><?php echo $row['descripcion']?></th>
                    <th><input type="submit" value="Vizualizar"></th><!--Aqui me tiene que mandar el id--->
                </tr>
            </form>
        <?php }?>
    </table>

    <div class="Con">
        <div class = "container-table-con">
            <div class="table__header">Carrera</div>
            <div class="table__header">Cantidad de grupos</div>
            <div class="table__header">Cantidad por Grupo</div>
            <?php  
                if ($_SERVER['REQUEST_METHOD']=="POST") {//se reciben los datos del formulario con el imput hidden seleccion


                    foreach($_POST as $key => $value){//obtengo el id de la configuración que se selecciono en un foreach de una vuelta 
                    $queryBtn = ("SELECT c.nombcar, dc.cant_Grupos, dc.cant_Elem_Grupo FROM detalles_config as dc INNER JOIN carreras as c ON dc.idCar = c.idCar WHERE dc.idConfig = $key AND c.idCar != 18 AND c.idCar !=25");                       
                    $resultadoBtn =mysqli_query($db, $queryBtn);
                    while($btnRC = mysqli_fetch_assoc($resultadoBtn)): 
            ?>
                        <div class="table__item"><?php echo $btnRC["nombcar"] ;?></div>
                        <div class="table__item"><?php echo $btnRC["cant_Grupos"];?></div>
                        <div class="table__item"><?php echo $btnRC["cant_Elem_Grupo"] ;?></div>
                <?php endwhile; }}?>
        </div> 
    </div>
</main>
<?php 
    inlcuirTemplate('footer');
?>

