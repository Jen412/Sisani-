<?php //Metódo de header  
    require "includes/funciones.php";  $auth = estaAutenticado();
	require "vendor/autoload.php";
	require "includes/config/database.php";
	use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory, Style\Alignment};

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
		move_uploaded_file($doc['tmp_name'],"Excel/importar/".$doc['name']);
		$archivo = "Excel/importar/".$doc['name'];
		$document = IOFactory::load($archivo);

		$hoja = $document->getSheet(0);
		$numFilas= $hoja->getHighestDataRow();
		$letra = $hoja->getHighestColumn();
		$resultados = [];
		for ($i=2; $i <= $numFilas; $i++) { 
			if ($hoja->getCellByColumnAndRow(1,$i) != "") {
				$valorA = $hoja->getCellByColumnAndRow(1,$i); //email
				$valorB = $hoja->getCellByColumnAndRow(2,$i); //password    
				$valorC = $hoja->getCellByColumnAndRow(3,$i); //nombre
				$valorD = $hoja->getCellByColumnAndRow(4,$i); //apellido P
				$valorE = $hoja->getCellByColumnAndRow(5,$i); //apellio M
				$valorF = $hoja->getCellByColumnAndRow(6,$i); // RFC
				$nombre = $valorC ." ". $valorD. " ". $valorE;
				$passwordhash = password_hash($valorB, PASSWORD_DEFAULT);
				$fecha = date('Y-m-d');
				$query ="INSERT INTO `users`(`email`, `password`, `create`, `role`, `rfc`) VALUES ('{$valorA}','{$passwordhash}','{$fecha}','maestro','{$valorF}')";
				$query2 = "INSERT INTO `maestros`(`nombreMaestro`, `rfc`) VALUES ('{$nombre}','{$valorF}')";
				
				$resultado =mysqli_query($db,$query);
				$resultado2 =mysqli_query($db,$query2);
				$resultados []= $resultado;
				$resultados []= $resultado2;
			}
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
	    <p>Importar datos de usuarios</p>
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