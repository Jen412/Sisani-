<?php //Metódo de header  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
	require "../../vendor/autoload.php";
	require "../../includes/config/database.php";
	use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory, Style\Alignment};
    if (!$auth) {
       header('location: /'); die();
    }
	$db = conectarDB();
    inlcuirTemplate('header');
	$doc ="";
	$ban = false;
	if ($_SERVER['REQUEST_METHOD']==="POST") {
		$doc =$_FILES['importA'];
		// echo "<pre>";
		// var_dump($_FILES);
		// echo "</pre>";
		
		move_uploaded_file($doc['tmp_name'],"../../Excel/importar/".$doc['name']);
		$archivo = "../../Excel/importar/".$doc['name'];
		$document = IOFactory::load($archivo);
		
		$hoja = $document->getSheet(0);
		$numFilas= $hoja->getHighestDataRow();
		$letra = $hoja->getHighestColumn();
		$resultados = [];
		for ($i=2; $i <= $numFilas; $i++) { 
			$valorA= $hoja->getCellByColumnAndRow(1,$i);
			$valorB= $hoja->getCellByColumnAndRow(2,$i);
			$query ="UPDATE alumnos SET cal_ceneval= {$valorB} WHERE solicitud = '{$valorA}'";
			$resultado =mysqli_query($db,$query);
			$resultados []= $resultado;
		}
		
		
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
        <p>Importar datos del Ceneval</p>
    </div>
	<br>
	<p>Importar Archivo: </p>
	<form class="funciones" method="POST"  enctype="multipart/form-data">
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
	if ($ban && $_SERVER['REQUEST_METHOD']==="POST") {
        echo "<script>exito('Datos de Ceneval Importados');</script>";
    }
	$queryVal = "SELECT * FROM alumnos";
	$resultadoVal = mysqli_query($db, $queryVal);
	if (mysqli_num_rows($resultadoVal)===0) {
		echo "<script> alert('No se han ingresado Alumnos');</scritp>";
		header("location: /admin/Importar Datos/Aspirantes.php");
	}
?>
