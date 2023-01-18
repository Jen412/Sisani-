<?php //Metódo de header  
    require "../../includes/funciones.php";  
	require "../../vendor/autoload.php";
	require "../../includes/config/database.php";
	use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory, Style\Alignment};

    $auth = estaAutenticado();
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
			// Se busca la id de la materia mediante el nombre de la materia
			$query ="SELECT idMateria FROM materias WHERE nombreMateria LIKE '${valorB}'";
			$resultadoMat = mysqli_query($db, $query);
			$idMat= mysqli_fetch_assoc($resultadoMat)['idMateria'];

			$queryidGrupo = "SELECT grupos.idGrupo FROM grupos JOIN materiagrupo ON grupos.idGrupo=materiagrupo.idGrupo JOIN alumnos ON alumnos.solicitud = grupos.solicitud  JOIN materias ON materias.idMateria= materiagrupo.idMateria JOIN carreras ON carreras.idCarrera = alumnos.idCarrera WHERE materias.idMateria = ${idMat} AND grupos.letraGrupo = '${valorC}' AND alumnos.solicitud = ${valorA};";
			$resultadoIG = mysqli_query($db, $queryidGrupo);
			$idGrup = mysqli_fetch_assoc($resultadoIG)['idGrupo'];
			//se busca el id de materiaGrupo mediante el id de materia y el id del grupo sacado del documento excel
			$query = "SELECT idMateriaGrupo FROM materiagrupo WHERE idMateria = '${idMat}' && idGrupo = '${idGrup}';";
			$resultadoMG = mysqli_query($db, $query);
			$idMatG = mysqli_fetch_assoc($resultadoMG)['idMateriaGrupo'];
			//Se insertan los datos de las calificaciones
			$query= "INSERT INTO `calificaciones`(`idMateriaGrupo`, `solicitud`, `calif`) VALUES (${idMatG},'${valorA}',${valorD});";
			//echo "<br>". $query;
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
			header("location: /admin/Gestionar Calificaciones/GestionarCal.php?alerta=1");
			die();
		}
	}
?>
<div class="contenedor">
	<div class="enunciado">
	    <p>Registrar Calificaciones por Archivo</p>
   	</div>
	<br>
	<p>Importar Archivo: </p>
	<form method="POST" class="funciones" enctype="multipart/form-data">
		<label for="importA">Seleccionar archivo</label>
		<input  type="file" class ="archivo" name="importA" id="importA"  accept=".xlsx"  >
		<h4 id="nombre"></h4>
		<div>
			<button>Registrar Calificaciones
				<input type="submit" value="" id="btnImport">
			</button>
		</div>
	</form>
</div>

<?php //Metódo de footer
    inlcuirTemplate('footer');
?>