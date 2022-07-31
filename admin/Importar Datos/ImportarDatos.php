<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    if (!$auth) {
       header('location: /'); die();
    }
    if ($_SESSION['role']!="admin") {
        header('location: /admin/index.php'); 
        die();
    }
    inlcuirTemplate('header');
?>
<main class="import">
    <section class="w80">
        <h1>Importar Datos Menu</h1>
        <div class="buttImport">
            <a href="/admin/Importar Datos/Aspirantes.php">
                <ion-icon name="cloud-upload-outline"></ion-icon>
                Importar Datos Aspirantes
            </a>
            <a href="/admin/Importar Datos/Ceneval.php">
                <ion-icon name="cloud-upload-outline"></ion-icon>
                Importar Datos Ceneval
            </a>
        </div>
    </section>
</main>
<?php 
    inlcuirTemplate('footer');
?>