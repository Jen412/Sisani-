<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Importar datos sustentables registrados</title>	
	<link rel="stylesheet" href="/build/css/importar.css">
</head>
<body>
    <?php //Metódo de header  
        require "../includes/funciones.php";
        inlcuirTemplate('header');
    ?>
	<div class="contenedor">
		<div class="enunciado">
            <p>Importar datos sustentables registrados</p>
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
	<script type="text/javascript">
		let archivo = document.querySelector('#importA');
		archivo.addEventListener('change', () => {
			document.querySelector('#nombre').innerText =
				archivo.files[0].name;
		});
	</script>
    <?php //Metódo de footer
        inlcuirTemplate('footer');
    ?>
</body>
</html>