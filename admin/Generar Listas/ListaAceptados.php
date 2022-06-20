<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');
?>
<main class="g_listas">
    <h1>Listas de Aceptados</h1>
    <form action="">
        <div class="carreras">
            <label>Selecciona una carrera</label>
            <select name="" id="">
                <option value="" disabled selected>--INGENIERÍA ELECTRÓNICA--</option>
            </select>
        </div>
        <div class="modal">
            <input type="button" value="Generar Lista">
        </div>
    </form>
</main>
<?php 
    inlcuirTemplate('footer');
?>
