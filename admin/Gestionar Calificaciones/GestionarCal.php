<?php  
    require "../../includes/funciones.php";  
    $auth = estaAutenticado();
    if (!$auth) {
       header('location: /'); 
       die();
    }
    inlcuirTemplate('header');
    $alerta= $_GET['alerta']??null;
?>
<main class="gestionarCal">
    <section class="w80">
        <h1>Gestionar Calificaciones Menu</h1>
        <div class="buttGes">
            <a href="/admin/Gestionar Calificaciones/RegistrarCal.php">
                <ion-icon name="document"></ion-icon>
                Registrar Calificaciones
            </a>
            <a href="/admin/Gestionar Calificaciones/RegistrarCalPorArchivo.php">
                <ion-icon name="document-attach"></ion-icon>
                Registrar Calificaciones Por Archivo
            </a>
            <a href="/admin/Gestionar Calificaciones/VerCalificaciones.php">
                <ion-icon name="reader"></ion-icon>
                Ver Calificaciones
            </a>
            <a href="/admin/Gestionar Calificaciones/ModificarCal.php">
                <ion-icon name="document-text"></ion-icon>
                Modificar Calificaciones
            </a>
        </div>
    </section>
</main>
<?php 
    inlcuirTemplate('footer');
    if ($alerta == 1) {
        echo "<script>exito('Calificaciones Registradas Correctamente');</script>";
    }else if($alerta==2){
        echo "<script>exito('Calificaciones Modificadas Correctamente');</script>";
    }
?>