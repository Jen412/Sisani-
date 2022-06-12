<?php  
    require "../../includes/funciones.php";
    inlcuirTemplate('header');
?>
<main class="g_listas">
    <h1>Listas Examen Ceneval</h1>
    <form action="">
        <div class="tipoLista">
            <input type="button" value="Generar todas las listas" id="btnAll">
            <input type="button" onclick="mostrarContenido();" value="Especificar lista" id="btnChose" >
        </div>
        <div id="ocultar">
            <label>Seleccionar Materia</label>
            <select name="" id="">
                <option value="" disabled selected>--Seleccione Materia--</option>
            </select>
            <input type="button" value="Especificar lista">
        </div>
    </form>
</main>
<?php 
    inlcuirTemplate('footer');
?>



