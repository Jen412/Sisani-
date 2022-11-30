<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    if (!$auth) {
       header('location: /'); die();
    }
    if ($_SESSION['role']!="admin") {
        header('location: /admin/index.php'); 
        die();
    }
    $alerta = $_GET['alerta']??null;
    inlcuirTemplate('header');
?>
<main class="gestionar">
    <section class="w80">
        <h1>Gestionar Grupos Menu</h1>
        <div class="buttGes">
            <a href="/admin/Gestionar Grupos/GenerarGrupos.php">
                <ion-icon name="people-circle"></ion-icon>
                Generar Grupos Automáticamente
            </a>
            <a href="/admin/Gestionar Grupos/AsignarMaestros.php">
                <ion-icon name="person-add"></ion-icon>
                Asignar Maestros
            </a>
            <a href="/admin/Gestionar Grupos/ModificarMaestros.php">
                <ion-icon name="person-remove"></ion-icon>
                Modificar Asignacion
            </a>
            <a href="/admin//Gestionar Grupos/VerGrupos.php">
                <ion-icon name="reader"></ion-icon>
                Ver Grupos
            </a>
        </div>
    </section>
</main>
<?php 
    inlcuirTemplate('footer');
    if ($alerta ==1) {
        echo "<script>exito('Maestros Asignados Correctamente');</script>";
    }
    else if($alerta==2){
        echo "<script>exito('Modificación Exitosa');</script>";
    }
?>