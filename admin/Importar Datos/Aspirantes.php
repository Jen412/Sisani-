<?php //Metódo de header  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
	require "../../vendor/autoload.php";
	require "../../includes/config/database.php";
	use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory, Style\Alignment};

    if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');
	$doc ="";
	if ($_SERVER['REQUEST_METHOD']==="POST") {
		$doc =$_FILES['importA'];
		// echo "<pre>";
		// var_dump($_FILES);
		// echo "</pre>";
		$db = new mysqli("localhost","root", "", "siseni");
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
			$valorC = $hoja->getCellByColumnAndRow(3,$i);
			$valorD = $hoja->getCellByColumnAndRow(4,$i);
			$valorE = $hoja->getCellByColumnAndRow(5,$i);
			$valorF = $hoja->getCellByColumnAndRow(6,$i);
			switch ($valorE) {
				case 'INGENIERÍA ELECTRÓNICA':
					$valorE = 4;
					break;
				case 'INGENIERÍA MECÁNICA':
					$valorE = 5;
					break;
				case 'INGENIERÍA ELÉCTRICA':
					$valorE = 5;
					break;
				case 'INGENIERÍA EN SISTEMAS COMPUTACIONALES':
					$valorE = 15;
					break;
				case 'INGENIERÍA INDUSTRIAL':
					$valorE = 16;
					break;
				case 'MAESTRÍA EN INGENIERÍA ELECTRÓNICA':
					$valorE = 18;
					break;
				case 'INGENIERÍA AMBIENTAL':
					$valorE = 20;
					break;
				case 'ARQUITECTURA':
					$valorE = 21;
					break;
				case 'CONTADOR PÚBLICO':
					$valorE = 22;
					break;
				case 'INGENIERÍA EN GESTIÓN EMPRESARIAL':
					$valorE = 23;
					break;
				case 'INGENIERÍA INFORMÁTICA':
					$valorE = 24;
					break;
				case 'MAESTRÍA EN CIENCIAS DE LA COMPUTACIÓN':
					$valorE = 25;
					break;
			}
			$query ="INSERT INTO alumnos(solicitud, alu_nombre, alu_prom, alu_apeP, alu_apeM, idCarrera) VALUES ('{$valorA}','{$valorB}',{$valorF},'{$valorC}','{$valorD}',{$valorE});";
			//echo $query . "<br>";
			$resultado =$db->query($query);
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
	    <p>Importar datos de los Aspirantes del ITCG</p>
   	</div>
	<br>
	<p>Importar Archivo: </p>
	<form method="POST" class="funciones" enctype="multipart/form-data">
		<label for="importA">Seleccionar archivo</label>
		<input  type="file" class ="archivo" name="importA" id="importA"  accept=".xlsx"  >
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