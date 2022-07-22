<?php //Metódo de header  
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
<div class="contenedor">
	<div class="enunciado">
        <p>Importar datos sustentables registrados</p>
    </div>
	<br>
	<p>Importar Archivo: </p>
	<form class="funciones">
		<label for="importA">Seleccionar archivo</label>
		<input class = "archivo" type="file" name = "importA" id="importA">
		<h4 id="nombre"></h4>
		<div>
			<button>Importar datos
				<input type="submit" value="" id="btnImport">
			</button>
		</div>
	</form>
</div>

<?php //Metódo de footer
    inlcuirTemplate('footer');
?>
