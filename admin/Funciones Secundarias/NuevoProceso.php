<?php

use function PHPSTORM_META\map;

    require "../../includes/funciones.php";  $auth = estaAutenticado();
    require "../../includes/config/database.php";
    if (!$auth) {
       header('location: /'); die();
    }
    if ($_SESSION['role']!="admin") {
        header('location: /admin/index.php'); 
        die();
    }
    if ($_SERVER['REQUEST_METHOD']==="POST") {
        $db = conectarDB();
        $queryCal = "DELETE FROM calificaciones;";
        $queryMatG = "DELETE FROM materiagrupo;";
        $queryGrup = "DELETE FROM grupos;";
        $queryAlu ="DELETE FROM alumnos;";
        $resultadoCal = mysqli_query($db,$queryCal); 
        $resultadoMat = mysqli_query($db,$queryMatG); 
        $resultadoGrup = mysqli_query($db, $queryGrup); 
        $resultadoAlu = mysqli_query($db,$queryAlu); 
        if ($resultadoAlu && $resultadoCal && $resultadoGrup && $resultadoMat) {
            header('location: /admin/index.php'); 
            die();
        }
    }
    inlcuirTemplate('header');
?>
<main class="nuevoProceso">
    <section class="w80">
        <form method="post" id="nuevoProc">
            <h1>Nuevo Proceso</h1>
            <input type="button" value="Iniciar Nuevo Proceso" onclick="borrarDatos('#nuevoProc');">
        </form>
    </section>
</main>
<?php 
    inlcuirTemplate('footer');
?>