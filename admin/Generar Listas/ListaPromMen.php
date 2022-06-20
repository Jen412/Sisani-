<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    require "../../includes/config/database.php";
    require "../../vendor/autoload.php";

    use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');

    function excel($nom,$db){
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setTitle($nom);
        $hoja = $spreadsheet->getActiveSheet();
        $hoja->getColumnDimension('A')->setWidth(15);
        $hoja->setCellValue('A2', "Ficha");
        $hoja->getColumnDimension('B')->setWidth(20);
        $hoja->setCellValue('B2', "Nombre");
        $hoja->getColumnDimension('C')->setWidth(25);
        $hoja->setCellValue('C2', "Apellido Paterno");
        $hoja->getColumnDimension('D')->setWidth(25);
        $hoja->setCellValue('D2', "Apellido Materno");
        $hoja->getColumnDimension('E')->setWidth(25);
        $hoja->setCellValue('E2', "Promedio Bachillerato");
    
        $queryAlu = "SELECT alufic , alunom, aluapp, aluapm, alupro FROM dficha WHERE alupro <=60;";
        $resultado = mysqli_query($db, $queryAlu);
        $fila = 3;
        while($alumno = mysqli_fetch_assoc($resultado)){
            $hoja->setCellValue('A'.$fila, $alumno['alufic']);
            $hoja->setCellValue('B'.$fila, $alumno['alunom']);
            $hoja->setCellValue('C'.$fila, $alumno['aluapp']);
            $hoja->setCellValue('D'.$fila, $alumno['aluapm']);
            $hoja->setCellValue('E'.$fila, $alumno['alupro']);
            $fila++;
        }
        $writer = new Xlsx($spreadsheet);        
        $writer =IOFactory::createWriter($spreadsheet, 'Xlsx');
        // $writer->save('php://output');
        $writer->save("../../Excel/ListasAlumnos60/".$nom.'.xlsx');
    }

    if ($_SERVER['REQUEST_METHOD']==="POST") {
        $db = conectarDB();
        $nom ="ListaAlumnosMenos60";
        excel($nom,$db);
        $zip = new ZipArchive();
        $archivo ='../../Excel/ListasAlumnos60.zip';
        if ($zip->open($archivo, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            $zip->addFile("../../Excel/ListasAlumnos60/".$nom.".xlsx");
        }
        $zip->close();
        $fileName = basename('ListasAlumnos60.zip');
        $filePath = '../../Excel/'.$fileName;
        if(!empty($fileName) && file_exists($filePath)){
            // Define headers
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$fileName");
            header("Content-Type: application/zip");
            header("Content-Transfer-Encoding: binary");
            // Read the file
            readfile($filePath);
            exit;
        }else{
            echo 'No existe el archivo';
        }
    }
?>
<main class="g_listas">
    <h1>Lista de Aspirantes cuyo promedio de Bachillerato es menor a 60</h1>
    <form method="POST" class="todas">
        <div class="btnsLista">
            <input type="submit" value="Generar" class="btnAllV">
        </div>
    </form>
</main>
<?php 
    inlcuirTemplate('footer');
?>



