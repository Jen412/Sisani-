<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    if (!$auth) {
       header('location: /'); die();
    }
    if ($_SESSION['role']!="admin") {
        header('location: /admin/index.php'); 
        die();
    }
    if ($_SERVER['REQUEST_METHOD']==="POST") {
        
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