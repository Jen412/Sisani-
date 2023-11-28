<?php //Metódo de header  
    require "../../includes/funciones.php";  
	$auth = estaAutenticado();
	require "../../vendor/autoload.php";
	require "../../includes/config/database.php";
	use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory, Style\Alignment};

use function PHPSTORM_META\map;
use function PHPSTORM_META\type;

    if (!$auth) {
       header('location: /'); die();
    }
	if ($_SESSION['role']!="admin") {
        header('location: /admin/index.php'); 
        die();
    }
    inlcuirTemplate('header');
	$doc ="";
	if ($_SERVER['REQUEST_METHOD']==="POST") {
		$doc =$_FILES['importA'];
		// echo "<pre>";
		// var_dump($_FILES);
		// echo "</pre>";
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
			$valorC = $hoja->getCellByColumnAndRow(3,$i);
			$valorD = $hoja->getCellByColumnAndRow(4,$i);
			$valorE = $hoja->getCellByColumnAndRow(5,$i);
			$valorF = $hoja->getCellByColumnAndRow(6,$i);
			if ($valorF->getValue() <=10) {
				$valorF = $valorF->getValue()*10;
			}
			

			$query ="SELECT idCarrera FROM carreras WHERE nomCarrera = '$valorE';";
			$resultado = mysqli_query($db, $query);
			$carrera = mysqli_fetch_assoc($resultado);
			// echo "<pre>";
			// var_dump($carrera);
			// echo "</pre>";
			$valorE = $carrera['idCarrera'];
			$query ="INSERT INTO alumnos(solicitud, alu_nombre, alu_prom, alu_apeP, alu_apeM, idCarrera) VALUES ('{$valorA}','{$valorB}',{$valorF},'{$valorC}','{$valorD}',{$valorE});";
			//echo $query . "<br>";
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