<?php  
    require "../../includes/funciones.php";
    inlcuirTemplate('header');
?>
<main class="lista">
    <section class="w80">
        <h1>Generar Listas Menu</h1>
        <div class="buttLista">
            <a href="/admin/Generar Listas/ListaExamen.php">
                <ion-icon name="document-text-outline"></ion-icon>
                Listas Examen Ceneval
            </a>
            <a href="/admin/Generar Listas/ListaCursoIntro.php">
                <ion-icon name="document-text-outline"></ion-icon>
                Listas del Curso de Introducción
            </a>
        </div>
    </section>
</main>
<?php 
    inlcuirTemplate('footer');
?>