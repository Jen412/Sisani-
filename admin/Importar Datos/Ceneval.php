<?php //Metódo de header  
    require "../../includes/funciones.php";
    inlcuirTemplate('header');
?>
<div class="contenedor">
	<div class="enunciado">
        <p>Importar datos del Ceneval</p>
    </div>
	<br>
	<p>Importar Archivo: </p>
	<div class="funciones">
		
		<label for="importA">Selecciona el archivo</label>
		<input class = "archivo" type="file" name = "importA" id="importA">
		<h4 id="nombre"></h4>
		<br>
		<br>
		<button id="btnImport">Importar datos</button>
	</div>
	
</div>
<?php //Metódo de footer
    inlcuirTemplate('footer');
?>
