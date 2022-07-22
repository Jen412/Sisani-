<?php  
    require "../../includes/funciones.php";  
    $auth = estaAutenticado();
    require "../../includes/config/database.php";
    require "../../vendor/autoload.php";

    use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory, Style\Alignment};
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

     if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');
    $db = conectarDB();
    $queryMat ="SELECT * FROM carreras";
    $resultadoMat =mysqli_query($db, $queryMat);
    function excel($nom,$db, $id){

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
        $hoja->setCellValue("A1","INSTITUTO TECNOLOGICO DE CIUDAD GUZMAN  ". $nom);
        $hoja->mergeCells("A2:B2");
        $hoja->getStyle("A2")->getFont()->setSize(13);
        $hoja->getStyle("A2:B2")->getFont()->setBold(true);
        $hoja->setCellValue("A2","Lista Examen Ceneval");
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
        $hoja->setCellValue('E3', "Promedio Examen");
    
        $queryAlu = "SELECT solicitud, alu_apeP, alu_apeM, alu_nombre, cal_ceneval from alumnos WHERE idCarrera = {$id};";
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
            $hoja->setCellValue('E'.$fila, $alumno['cal_ceneval']);
            $hoja->getStyle("E".$fila)->applyFromArray($borderArray);
            $hoja->getStyle('E'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $fila++;
        }
        $writer = new Xlsx($spreadsheet);        
        $writer =IOFactory::createWriter($spreadsheet, 'Xlsx');
        // $writer->save('php://output');
        $writer->save("../../Excel/ListasCeneval/".$nom.'.xlsx');
    }

    $ban = true;
    if ($_SERVER['REQUEST_METHOD']==="POST" && $_POST['tipoLista']=="General") {
        $queryCars = "SELECT * FROM carreras";
        $resultado = mysqli_query($db, $queryCars);
        while ($carrera = mysqli_fetch_assoc($resultado)) {
            excel($carrera['nomCarrera'],$db, $carrera['idCarrera']);
        }
        $zip = new ZipArchive();
        $archivo ='../../Excel/ListasExamenCeneval.zip';
        if ($zip->open($archivo, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            $dir =scandir('../../Excel/ListasCeneval/',1);
            foreach($dir as $arc){
                if ('../../Excel/ListasCeneval/'.$arc != "../../Excel/ListasCeneval/.." && '../../Excel/ListasCeneval/'.$arc != "../../Excel/ListasCeneval/.") {
                    $zip->addFile('../../Excel/ListasCeneval/'.$arc);
                }
            }
            $zip->close();
            $fileName = basename('ListasExamenCeneval.zip');
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
            $dir =scandir('../../Excel/ListasCeneval/',1);
            foreach($dir as $arc){
                if ('../../Excel/ListasCeneval/'.$arc != "../../Excel/ListasCeneval/.." && '../../Excel/ListasCeneval/'.$arc != "../../Excel/ListasCeneval/.") {
                    echo ('../../Excel/ListasCeneval/'.$arc. "<br>");
                    unlink('../../Excel/ListasCeneval/'.$arc);
                }else{
                    $ban = false;
                }
            }
        }
    }
    if ($_SERVER['REQUEST_METHOD']==="POST" && $_POST['tipoLista']=="Especifica") {
        $idCarrera = $_POST['carrera'];
        $queryCar = "SELECT nomCarrera FROM carreras WHERE idCarrera = {$idCarrera};";
        $resultado = mysqli_query($db, $queryCar);
        $nomCarrera= mysqli_fetch_assoc($resultado)['nomCarrera'];
        excel($nomCarrera, $db, $idCarrera);
        $zip = new ZipArchive();
        $archivo ='../../Excel/'.$nomCarrera.'.zip';
        if ($zip->open($archivo, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            $zip->addFile('../../Excel/ListasCeneval/'.$nomCarrera.".xlsx");
            $zip->close();
        }
        $fileName = basename($nomCarrera.".zip");
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
            echo 'The file does not exist.';
        }
        $dir =scandir('../../Excel/ListasCeneval/',1);
        foreach($dir as $arc){
            if ('../../Excel/ListasCeneval/'.$arc != "../../Excel/ListasCeneval/.." && '../../Excel/ListasCeneval/'.$arc != "../../Excel/ListasCeneval/.") {
                echo ('../../Excel/ListasCeneval/'.$arc. "<br>");
                unlink('../../Excel/ListasCeneval/'.$arc);
            }
        }
    }
?>
<main class="g_listas">
    <h1>Listas Examen Ceneval</h1>

            <form method="POST" class="btnsLista">
                <input type="hidden" name="tipoLista" value="General">
                <input type="submit" clas value="Generar todas las listas" class="btnAllVL" >
                <input type="button" onclick="mostrarContenido();" value="Especificar lista" class="btnChoseA" >
            </form>
            

            <form method="POST" id="todas">
                <label>Seleccionar una Carrera</label>
                <input type="hidden" name="tipoLista" value="Especifica">
                <select name="carrera" id="carrera" >
                    <option value="" disabled selected>--Seleccione Carrera--</option>
                    <?php while($materia = mysqli_fetch_assoc($resultadoMat)):?>
                        <option value="<?php echo $materia['idCarrera'];?>"><?php echo $materia['nomCarrera'];?></option>
                    <?php endwhile;?>
                </select>
                <input type="submit" value="Generar Lista">
            </form>
</main>
<?php 
    inlcuirTemplate('footer');
    if ($ban && $_SERVER['REQUEST_METHOD']==="POST") {
        echo "<script>exito('Lista de Examen Cenaval Generada');</script>";
    }
?>



