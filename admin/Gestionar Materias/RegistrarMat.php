<?php
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    require "../../includes/config/database.php";
    if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');
    $db= conectarDB();

    if ($_SERVER['REQUEST_METHOD']==="POST") {
        $materia = $_POST['materia'];
        
        $query ="INSERT INTO materias(nombreMateria) VALUES('{$materia}');";
        $resultado = mysqli_query($db, $query);
        if ($resultado) {
            header('location: /admin/Gestionar Materias/GestionarMat.php'); 
            die();
        }
    }
?>
<main class="registrarMat">
    <section class="w80">
        <h1>Registrar Materia</h1>
        <form method="POST">
            <div class="Materia">
                <label for="materia">Nombre de Materia</label>
                <input type="text" name="materia" id="materia" onkeypress="return checkLetters(event);">
            </div>
            <div class="button">
                <input type="submit" value="Registrar">
            </div>
        </form>
    </section>
</main>

<?php 
    inlcuirTemplate('footer');
?>