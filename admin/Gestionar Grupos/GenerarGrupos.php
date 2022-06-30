<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    require "../../includes/config/database.php";
    if (!$auth) {
       header('location: /'); die();
    }
    if($_SERVER['REQUEST_METHOD']==="POST"){
        $db = conectarDB();
        $query = "SELECT * FROM carreras";
        $resultado=mysqli_query($db, $query);
        while ($carr=mysqli_fetch_assoc($resultado)) {
            $query ="SELECT * FROM dficha WHERE carcve1 = {$carr['idCar']}";
            $result= mysqli_query($db, $query);
            $numAlumnos= mysqli_num_rows($result);
            $query = "SELECT cant_Grupos, cant_Elem_Grupo FROM detalles_config WHERE idCar = {$carr['idCar']} AND idConfig =2;";
            $res = mysqli_query($db, $query);
            $detalles = mysqli_fetch_assoc($res);
            $cantGrupos = $detalles['cant_Grupos'];
            $cantXGrupo = $detalles['cant_Elem_Grupo'];
            $cont =0;
            while ($alumno = mysqli_fetch_assoc($result)) {
                
            }
        }
    }
    inlcuirTemplate('header');
?>
<main class="c_grupos">
    <h1>Crear Grupos</h1>
    <form id="x" method="post">
        <input type="button" value="Automáticamente" onclick="confirmarEliminacion('#x')">
    </form>
</main>
<?php 
    inlcuirTemplate('footer');
?>