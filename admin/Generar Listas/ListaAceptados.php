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
    $db = conectarDB();
    $query = "SELECT * FROM carreras";
    $resultado = mysqli_query($db, $query);

    function excel($nom,$db, $id){
        $borderArray =[
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000'],
                ],
            ],
        ];
        $materia1 =1;
        $query ="SELECT * FROM materias WHERE idMateria = {$materia1}";
        $resultMat1 = mysqli_query($db, $query);
        $nombreMat1=mysqli_fetch_assoc($resultMat1)['nombre_Mat'];
        $materia2 =2;
        $query ="SELECT * FROM materias WHERE idMateria = {$materia2}";
        $resultMat2 = mysqli_query($db, $query);
        $nombreMat2=mysqli_fetch_assoc($resultMat2)['nombre_Mat'];
        $materia3 =3;
        $query ="SELECT * FROM materias WHERE idMateria = {$materia3}";
        $resultMat3 = mysqli_query($db, $query);
        $nombreMat3=mysqli_fetch_assoc($resultMat3)['nombre_Mat'];

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
        $hoja->setCellValue("A2","Lista Aceptados");
        $hoja->getStyle("A2:E2")->applyFromArray($borderArray);
        $hoja->getStyle("A3")->applyFromArray($borderArray);
        $hoja->getStyle("B3")->applyFromArray($borderArray);
        $hoja->getStyle("C3")->applyFromArray($borderArray);
        $hoja->getStyle("D3")->applyFromArray($borderArray);
        $hoja->getStyle("E3")->applyFromArray($borderArray);
        $hoja->getStyle("F3")->applyFromArray($borderArray);
        $hoja->getStyle("G3")->applyFromArray($borderArray);
        $hoja->getStyle("H3")->applyFromArray($borderArray);
        $hoja->getStyle("I3")->applyFromArray($borderArray);
        $hoja->getStyle("J3")->applyFromArray($borderArray);
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
        $hoja->setCellValue('E3', "PromB");
        $hoja->getColumnDimension('E')->setWidth(15);
        $hoja->setCellValue('F3', $nombreMat1);
        $hoja->getColumnDimension('F')->setWidth(20);
        $hoja->setCellValue('G3', $nombreMat2);
        $hoja->getColumnDimension('G')->setWidth(25);
        $hoja->setCellValue('H3', $nombreMat3);
        $hoja->getColumnDimension('H')->setWidth(25);
        $hoja->setCellValue('I3', "Prom Ceneval");
        $hoja->getColumnDimension('I')->setWidth(15); 
        $hoja->setCellValue('J3', "Prom Final");
        $hoja->getColumnDimension('J')->setWidth(15);
    
        $queryAlu = "SELECT alufic, aluapp, aluapm, alunom, alupro, calificacionCeneval from dficha WHERE carcve1 = {$id};";
        $resultadoAlu = mysqli_query($db, $queryAlu);
        $fila = 4;
        while($alumno = mysqli_fetch_assoc($resultadoAlu)){
            $hoja->setCellValue('A'.$fila, $alumno['alufic']);
            $hoja->getStyle('A'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $hoja->getStyle("A".$fila)->applyFromArray($borderArray);
            $hoja->setCellValue('B'.$fila, $alumno['alunom']);
            $hoja->getStyle("B".$fila)->applyFromArray($borderArray);
            $hoja->getStyle('B'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $hoja->setCellValue('C'.$fila, $alumno['aluapp']);
            $hoja->getStyle("C".$fila)->applyFromArray($borderArray);
            $hoja->getStyle('C'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $hoja->setCellValue('D'.$fila, $alumno['aluapm']);
            $hoja->getStyle("D".$fila)->applyFromArray($borderArray);
            $hoja->getStyle('D'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $hoja->setCellValue('E'.$fila, $alumno['alupro']);
            $hoja->getStyle('E'.$fila)->applyFromArray($borderArray);
            $hoja->getStyle('E'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

            $queryMatG = "SELECT id_MateriaG, calif FROM calificaciones WHERE alufic = '{$alumno['alufic']}'";
            $resultadoMatG = mysqli_query($db, $queryMatG);
            while ($matG=mysqli_fetch_assoc($resultadoMatG)) {
                $queryMat = "SELECT * FROM materia_grupo WHERE id_MateriaG = {$matG['id_MateriaG']}";
                $resulMat = mysqli_query($db, $queryMat);
                $mateG = mysqli_fetch_assoc($resulMat);
                $calif = $matG['calif'];
                if ($materia1 == $mateG['idMateria']) {
                    $hoja->setCellValue('F'.$fila, $calif);
                    $hoja->getStyle('F'.$fila)->applyFromArray($borderArray);
                    $hoja->getStyle('F'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                }
                else if($materia2 == $mateG['idMateria']){
                    $hoja->setCellValue('G'.$fila, $calif);
                    $hoja->getStyle('G'.$fila)->applyFromArray($borderArray);
                    $hoja->getStyle('G'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                }
                else if($materia3 == $mateG['idMateria']){
                    $hoja->setCellValue('H'.$fila, $calif);
                    $hoja->getStyle('H'.$fila)->applyFromArray($borderArray);
                    $hoja->getStyle('H'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                }
            }
            $hoja->setCellValue('I'.$fila, $alumno['calificacionCeneval']);
            $hoja->getStyle('I'.$fila)->applyFromArray($borderArray);
            $hoja->getStyle('I'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $formula = "=(((E{$fila}*30%)+(F{$fila}*10%)+(G{$fila}*10%)+(H{$fila}*10%)+(((I{$fila}-700)/6)*40%))*6)+700";
            $hoja->setCellValue('J'.$fila, $formula);
            $hoja->getStyle('J'.$fila)->applyFromArray($borderArray);
            $hoja->getStyle('J'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $fila++;
        }
        $writer = new Xlsx($spreadsheet);        
        $writer =IOFactory::createWriter($spreadsheet, 'Xlsx');
        // $writer->save('php://output');
        $writer->save("../../Excel/ListasAceptados/".$nom.'.xlsx');
    }

    if ($_SERVER['REQUEST_METHOD']==="POST") {
        $carrera=$_POST['carrera'];
        $queryCar = "SELECT nombcar FROM carreras WHERE idCar = {$carrera};";
        $resultado = mysqli_query($db, $queryCar);
        $nomCarrera= mysqli_fetch_assoc($resultado)['nombcar'];
        excel($nomCarrera, $db, $carrera);$zip = new ZipArchive();
        $archivo ='../../Excel/'.$nomCarrera."Aceptados".'.zip';
        if ($zip->open($archivo, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            $zip->addFile('../../Excel/ListasAceptados/'.$nomCarrera.".xlsx");
            $zip->close();
        }
        $fileName = basename($nomCarrera."Aceptados".".zip");
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
        $dir =scandir('../../Excel/ListasAceptados/',1);
        foreach($dir as $arc){
            if ('../../Excel/ListasAceptados/'.$arc != "../../Excel/ListasAceptados/.." && '../../Excel/ListasAceptados/'.$arc != "../../Excel/ListasAceptados/.") {
                echo ('../../Excel/ListasAceptados/'.$arc. "<br>");
                unlink('../../Excel/ListasAceptados/'.$arc);
            }
        }
    }
?>
<main class="g_listas">
    <h1>Listas de Aceptados</h1>
    <form method="POST">
        <div class="carreras">
            <label>Selecciona una carrera</label>
            <select name="carrera" id="carrera">
                <option value="" disabled selected>--Seleccione Carrera--</option>
                <?php while($carrera = mysqli_fetch_assoc($resultado)):?>
                    <option value="<?php echo $carrera['idCar']?>"><?php echo $carrera['nombcar']?></option>    
                <?php endwhile;?>
            </select>
        </div>
        <div class="modal">
            <input type="submit" value="Generar Lista">
        </div>
    </form>
</main>
<?php 
    inlcuirTemplate('footer');
?>
