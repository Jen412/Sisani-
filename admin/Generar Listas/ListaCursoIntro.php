<?php  
    require "../../includes/funciones.php";
    inlcuirTemplate('header');
?>
<main class="g_listas">
    <h1>Listas Examen Ceneval</h1>
    <form action="">
        <div class="tipoLista">
            <input type="button" onclick="mostrarContenido();" value="Generar todas las listas por materia" id="btnMateria">
            <input type="button" onclick="mostrarContenido2();" value="Especificar lista" id="btnChose" >
        </div>
        <div id="ocultar">
            <label>Seleccione una Materia</label>
            <select name="" id="">
                <option value="" disabled selected>--Seleccione Materia--</option>
            </select>
            <input type="button" value="Generar" id="btnGenerar">
        </div>


        <div id="especifica">
                <div class="carrera">
                    <label for="">Selecciona Carrera</label>
                    <select>
                        <option value="" disabled selected>--Seleccione Carrera--</option>    
                    </select>
                </div>
                <div class="materia">
                    <label for="">Selecciona Materia</label>
                    <select>
                        <option value="" disabled selected>--Seleccione Materia--</option>    
                    </select>
                </div>
                <div class="grupo">
                    <label for="">Grupo</label>
                    <select>
                        <option value="" disabled selected>--Seleccione Grupo--</option>    
                    </select>
                </div>
                <input type="button" value="Generar">
            </div>
    </form>
</main>
<?php 
    inlcuirTemplate('footer');
?>



