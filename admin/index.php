<?php  
    require "../includes/funciones.php";
    inlcuirTemplate('header');
?>
<main class="admin">
    
    <section class="w80">
        <h1>Bienvenido a Admin</h1>
        <div class="botones">
            <a href="">
                <ion-icon name="cloud-upload-outline"></ion-icon>
                Importar Datos
            </a>
            <a href="">
                <ion-icon name="people-outline"></ion-icon>
                Gestionar Grupos
            </a>
            <a href="">
                <ion-icon name="document-text-outline"></ion-icon>
                Gestionar Listas
            </a>
            <a href="">
                <ion-icon name="clipboard-outline"></ion-icon>
                Gestionar Calificaciones
            </a>
            <a href="">
                <ion-icon name="cog"></ion-icon>
                Gestionar Configuraciones
            </a>
            <a href="">
                <ion-icon name="layers"></ion-icon>
                Funciones Secundarias
            </a>
        </div>
    </section>
</main>
<?php 
    inlcuirTemplate('footer');
?>