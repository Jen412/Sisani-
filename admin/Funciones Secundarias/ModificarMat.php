<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    require "../../includes/config/database.php";
    if (!$auth) {
       header('location: /'); die();
    }
    if ($_SESSION['role']!="admin") {
        header('location: /admin/index.php'); 
        die();
    }
    inlcuirTemplate('header');
    $db = conectarDB();
    $query = "SELECT * FROM materias";
    $resultado= mysqli_query($db, $query);
    if ($_SERVER['REQUEST_METHOD']==="POST") {
        $idMat = $_POST['materia'];
        $nomMat= $_POST['materiaN'];
        $query="UPDATE materias SET nombre_Mat='{$nomMat}' WHERE idMateria = {$idMat};";
        $resultado = mysqli_query($db, $query);
        if ($resultado) {
            header('location: /admin/Gestionar Materias/GestionarMat.php'); 
            die();
        }
    }
?>
<main class="ModificarMat">
    <section class="w80">
        <h1>Modificar Materia</h1>
        <form method="POST">
            <div class="Materia">
                <label for="materia">Seleccionar Matera</label>
                <select name="materia" id="materia">
                    <option value="" disabled selected>--Selecione Materia--</option>
                    <?php while ($materia = mysqli_fetch_array($resultado)): ?>
                        <option value="<?php echo $materia['idMateria'];?>"><?php echo $materia['nombreMateria'];?></option>
                    <?php endwhile;?>
                </select>
            </div>
            <div class="MateriaN">
                <label for="materia">Nuevo Nombre</label>
                <input type="text" name="materiaN" id="materiaN" onkeypress="return checkLetters(event);">
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