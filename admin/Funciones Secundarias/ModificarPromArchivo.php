<?php //Metódo de header  
    require "../../includes/funciones.php";
    inlcuirTemplate('header');
?>
<div class="contenedor">
	<div class="enunciado">
        <p>Modificar Promedio Por Archivo</p>
    </div>
	<br>
	<p>Importar Archivo: </p>
	<div class="funciones">
		<label for="importA">Seleccionar archivo</label>
		<input class = "archivo" type="file" name = "importA" id="importA">
		<h4 id="nombre"></h4>

		<div>
			<button id="btnImport">Importar datos</button>
		</div>
	</div>
</div>

<?php //Metódo de footer
    inlcuirTemplate('footer');
?>