<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    require "../../includes/config/database.php";
    require "../../vendor/autoload.php";

    use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory, Style\Alignment};
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');

    function excel($nom,$db){
        $borderArray =[
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000'],
                ],
            ],
        ];
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setTitle($nom);
        $hoja = $spreadsheet->getActiveSheet();
        $hoja->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $hoja->mergeCells("A1:G1");
        $hoja->getStyle("A1")->getFont()->setSize(15);
        $hoja->getStyle("A1")->getFont()->setBold(true);
        $hoja->setCellValue("A1","INSTITUTO TECNOLOGICO DE CIUDAD GUZMAN  ");
        $hoja->mergeCells("A2:B2");
        $hoja->getStyle("A2")->getFont()->setSize(13);
        $hoja->getStyle("A2:B2")->getFont()->setBold(true);
        $hoja->setCellValue("A2","Lista Promedio Menor 60");
        $hoja->getStyle("A2:E2")->applyFromArray($borderArray);
        $hoja->getStyle("A3")->applyFromArray($borderArray);
        $hoja->getStyle("B3")->applyFromArray($borderArray);
        $hoja->getStyle("C3")->applyFromArray($borderArray);
        $hoja->getStyle("D3")->applyFromArray($borderArray);
        $hoja->getStyle("E3")->applyFromArray($borderArray);
        $hoja->getStyle("A3:E3")->getFont()->setBold(true);
        $hoja->getStyle("A2:E2")->getFont()->setSize(12);

        $hoja->getColumnDimension('A')->setWidth(15);
        $hoja->setCellValue('A3', "Ficha");
        $hoja->getColumnDimension('B')->setWidth(20);
        $hoja->setCellValue('B3', "Nombre");
        $hoja->getColumnDimension('C')->setWidth(20);
        $hoja->setCellValue('C3', "Apellido Paterno");
        $hoja->getColumnDimension('D')->setWidth(20);
        $hoja->setCellValue('D3', "Apellido Materno");
        $hoja->getColumnDimension('E')->setWidth(20);
        $hoja->setCellValue('E3', "Promedio Bachillerato");

        $queryAlu = "SELECT solicitud , alu_nombre, alu_apeP, alu_apeM, alu_prom FROM alumnos WHERE alu_prom <=60;";
        $resultado = mysqli_query($db, $queryAlu);
        $fila = 4;
        while($alumno = mysqli_fetch_assoc($resultado)){
            $hoja->setCellValue('A'.$fila, $alumno['solicitud']);
            $hoja->getStyle('A'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $hoja->getStyle("A".$fila)->applyFromArray($borderArray);
            $hoja->setCellValue('B'.$fila, $alumno['alu_nombre']);
            $hoja->getStyle("B".$fila)->applyFromArray($borderArray);
            $hoja->getStyle('B'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $hoja->setCellValue('C'.$fila, $alumno['alu_apeP']);
            $hoja->getStyle("C".$fila)->applyFromArray($borderArray);
            $hoja->getStyle('C'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $hoja->setCellValue('D'.$fila, $alumno['alu_apeM']);
            $hoja->getStyle("D".$fila)->applyFromArray($borderArray);
            $hoja->getStyle('D'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $hoja->setCellValue('E'.$fila, $alumno['alu_prom']);
            $hoja->getStyle("E".$fila)->applyFromArray($borderArray);
            $hoja->getStyle('E'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
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
            
        }else{
            echo 'No existe el archivo';
        }
        $dir =scandir('../../Excel/ListasAlumnos60/',1);
        foreach($dir as $arc){
            if ('../../Excel/ListasAlumnos60/'.$arc != "../../Excel/ListasAlumnos60/.." && '../../Excel/ListasAlumnos60/'.$arc != "../../Excel/ListasAlumnos60/.") {
                echo ('../../Excel/ListasAlumnos60/'.$arc. "<br>");
                unlink('../../Excel/ListasAlumnos60/'.$arc);
            }
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



