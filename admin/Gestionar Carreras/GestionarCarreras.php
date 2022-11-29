<?php  
    require "../../includes/funciones.php";
    $auth = estaAutenticado();
    if (!$auth) {
       header('location: /'); die();
    }
    if ($_SESSION['role']!="admin") {
       header('location: /admin/index.php'); 
       die();
    }
    $alerta= $_GET['alerta']??null;
    
    inlcuirTemplate('header');
?>
<main class="gestionarCarreras">
    <section class="w80">
        <h1>Gestionar Carreras Menu</h1>
        <div class="buttGes">
            <a href="/admin/Gestionar Carreras/agregarCarrera.php">
                <ion-icon name="add-circle-outline"></ion-icon>
                Agregar Carrera
            </a>
            <a href="/admin/Gestionar Carreras/modificarCarrera.php">
                <ion-icon name="pencil-sharp"></ion-icon>
                Modificar Carrera
            </a>
            <a href="/admin/Gestionar Carreras/eliminarCarrera.php">
                <ion-icon name="trash-outline"></ion-icon>
                Eliminar Carrera
            </a>
        </div>
    </section>
</main>
<?php 
    inlcuirTemplate('footer');
    if ($alerta == 1) {
        echo "<script>exito('Carrera Modificada Correctamente');</script>";
    }else if($alerta==2){
        echo "<script>exito('Carrera Eliminada Correctamente');</script>";
    }
?>