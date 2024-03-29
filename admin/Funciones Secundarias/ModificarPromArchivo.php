<?php //Metódo de header  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
	require "../../vendor/autoload.php";
	require "../../includes/config/database.php";
	use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory, Style\Alignment};

	if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');
	if ($_SESSION['role']!="admin") {
        header('location: /admin/index.php'); 
        die();
    }
	if($_SERVER['REQUEST_METHOD']==="POST"){
		$doc =$_FILES['importA'];
		$db = conectarDB();
		move_uploaded_file($doc['tmp_name'],"../../Excel/importar/".$doc['name']);
		$archivo = "../../Excel/importar/".$doc['name'];
		$document = IOFactory::load($archivo);

		$hoja = $document->getSheet(0);
		$numFilas= $hoja->getHighestDataRow();
		$letra = $hoja->getHighestColumn();
		$resultados = [];
		for ($i=2; $i <= $numFilas; $i++) {
			$valorA = $hoja->getCellByColumnAndRow(1,$i);
			$valorB = $hoja->getCellByColumnAndRow(2,$i);
			$query = "UPDATE `alumnos` SET `alu_prom`='{$valorB}' WHERE solicitud = '{$valorA}';";
			$resultado =mysqli_query($db,$query);
			$resultados []= $resultado;
		}
		$ban = false;
		foreach($resultados as $resultado){ 
			if ($resultado) {
				$ban = true;
			}
		}
		if ($ban) {
			unlink("../../Excel/importar/".$doc['name']);
			header("location: /admin/Importar Datos/importarDatos.php");
			die();
		}
	}
?>
<div class="contenedor">
	<div class="enunciado">
        <p>Modificar Promedio Por Archivo</p>
    </div>
	<br>
	<p>Importar Archivo: </p>
	<form class="funciones" method="POST" enctype="multipart/form-data">
		<label for="importA">Seleccionar archivo</label>
		<input class = "archivo" type="file" name = "importA" id="importA"  accept=".xlsx">
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