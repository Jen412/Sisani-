<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    require "../../includes/config/database.php";
    require "../../vendor/autoload.php";

    use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory, Style\Alignment};
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter;


    if (!$auth) {
       header('location: /'); die();
    }
    if ($_SESSION['role']!="admin") {
        header('location: /admin/index.php'); 
        die();
    }
    inlcuirTemplate('header');
    $db = conectarDB();
    $query = "SELECT * FROM carreras";
    $resultado = mysqli_query($db, $query);

    function comparar($a, $b){
        if ((int) $a[9] == (int) $b[9]) {
            return 0;
        }
        return ((int) $a[9] > (int) $b[9]) ? -1 : 1;
    }
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
        $nombreMat1=mysqli_fetch_assoc($resultMat1)['nombreMateria'];
        $materia2 =2;
        $query ="SELECT * FROM materias WHERE idMateria = {$materia2}";
        $resultMat2 = mysqli_query($db, $query);
        $nombreMat2=mysqli_fetch_assoc($resultMat2)['nombreMateria'];
        $materia3 =3;
        $query ="SELECT * FROM materias WHERE idMateria = {$materia3}";
        $resultMat3 = mysqli_query($db, $query);
        $nombreMat3=mysqli_fetch_assoc($resultMat3)['nombreMateria'];
        $calMat1=0;
        $calMat2=0;
        $calMat3=0;

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
        $hoja->getStyle("K3")->applyFromArray($borderArray);
        $hoja->getStyle("A3:K3")->getFont()->setBold(true);
        $hoja->getStyle("A3:K3")->getFont()->setSize(12);

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
        $hoja->getColumnDimension('H')->setWidth(30);
        $hoja->setCellValue('I3', "Prom Ceneval");
        $hoja->getColumnDimension('I')->setWidth(15); 
        $hoja->setCellValue('J3', "Prom Final");
        $hoja->getColumnDimension('J')->setWidth(15);
        $hoja->setCellValue('K3', "Grupo");
        $hoja->getColumnDimension('K')->setWidth(15);
    
        $queryAlu = "SELECT solicitud, alu_apeP, alu_apeM, alu_nombre, alu_prom, cal_ceneval from alumnos WHERE idCarrera = {$id};";
        $resultadoAlu = mysqli_query($db, $queryAlu);
        $fila = 4;
        $array = Array();
        while($alumno = mysqli_fetch_assoc($resultadoAlu)){
            $queryMatG = "SELECT idMateriaGrupo, calif FROM calificaciones WHERE solicitud = '{$alumno['solicitud']}'";
            $resultadoMatG = mysqli_query($db, $queryMatG);
            while ($matG=mysqli_fetch_assoc($resultadoMatG)) {
                $queryMat = "SELECT * FROM materiagrupo WHERE idMateriaGrupo = {$matG['idMateriaGrupo']}";
                $resulMat = mysqli_query($db, $queryMat);
                $mateG = mysqli_fetch_assoc($resulMat);
                $calif = $matG['calif'];
                if ($materia1 == $mateG['idMateria']) {
                    $calMat1 =$calif;
                }
                else if($materia2 == $mateG['idMateria']){
                    $calMat2 =$calif;
                }
                else if($materia3 == $mateG['idMateria']){
                    $calMat3 =$calif;
                }
            }
            #$formula = "=(((E{$fila}*30%)+(F{$fila}*10%)+(G{$fila}*10%)+(H{$fila}*10%)+(((I{$fila}-700)/6)*40%))*6)+700";
            $promFin = ((($alumno['alu_prom']*0.30)+($calMat1*0.10)+($calMat2*0.10)+($calMat3*0.10)+((($alumno['cal_ceneval']-700)/6)*0.40))*6)+700;
            $arr =[$alumno['solicitud'], $alumno['alu_nombre'], $alumno['alu_apeP'], $alumno['alu_apeM'], $alumno['alu_prom'], $calMat1, $calMat2, $calMat3, $alumno['cal_ceneval'], $promFin];
            array_push($array,$arr);
        }
        // echo "<pre>";
        // var_dump($array);
        // echo "</pre>";
        usort($array, 'comparar');
        $queryCanG ="SELECT * FROM detalles_config WHERE idCarrera={$id} AND idConfig=1";
        $resultadoG = mysqli_query($db, $queryCanG);
        $detalles = mysqli_fetch_assoc($resultadoG);
        $cantGrup = $detalles['cantidadGrupos'];
        $cantXGrup = $detalles['num_Alumnos'];
        $grupos=[];
        $cantAcep = $cantGrup*$cantXGrup;
        switch ($cantGrup) {
            case 1:
                array_push($grupos, "A");
                break;
            case 2:
                array_push($grupos, "A");
                array_push($grupos, "B");
                break;
            case 3:
                array_push($grupos, "A");  
                array_push($grupos, "B");  
                array_push($grupos, "C");
                break;
            case 4:
                array_push($grupos, "A");
                array_push($grupos, "B");
                array_push($grupos, "C");
                array_push($grupos, "D");
                break;
            case 5:
                array_push($grupos, "A");
                array_push($grupos, "B");
                array_push($grupos, "C");
                array_push($grupos, "D");
                array_push($grupos, "E");
                break;
            case 6:
                array_push($grupos, "A");
                array_push($grupos, "B");
                array_push($grupos, "C");
                array_push($grupos, "D");
                array_push($grupos, "E");
                array_push($grupos, "F");
                break;
            case 7:
                array_push($grupos, "A");
                array_push($grupos, "B");
                array_push($grupos, "C");
                array_push($grupos, "D");
                array_push($grupos, "E");
                array_push($grupos, "F");
                array_push($grupos, "G");
                break;
            case 8:
                array_push($grupos, "A");
                array_push($grupos, "B");
                array_push($grupos, "C");
                array_push($grupos, "D");
                array_push($grupos, "E");
                array_push($grupos, "F");
                array_push($grupos, "G");
                array_push($grupos, "H");
                break;
            case 9:
                array_push($grupos, "A");
                array_push($grupos, "B");
                array_push($grupos, "C");
                array_push($grupos, "D");
                array_push($grupos, "E");
                array_push($grupos, "F");
                array_push($grupos, "G");
                array_push($grupos, "H");
                array_push($grupos, "I");
                break;
            case 10:
                array_push($grupos, "A");
                array_push($grupos, "B");
                array_push($grupos, "C");
                array_push($grupos, "D");
                array_push($grupos, "E");
                array_push($grupos, "F");
                array_push($grupos, "G");
                array_push($grupos, "H");
                array_push($grupos, "I");
                array_push($grupos, "J");
                break;
        }
        $banCambio =true;
        $contGrup=0;
        for ($i=0; $i <count($array); $i++) { 
            if ($i<= $cantAcep) {
                $grup="";
                if($contGrup >-1 && $contGrup < $cantGrup){
                    $grup= $grupos[$contGrup];
                    $hoja->setCellValue('K'.$fila, $grup);
                    $hoja->getStyle('K'.$fila)->applyFromArray($borderArray);
                    $hoja->getStyle('K'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $hoja->setCellValue('A'.$fila, $array[$i][0]);
                    $hoja->getStyle('A'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $hoja->getStyle("A".$fila)->applyFromArray($borderArray);
                    $hoja->setCellValue('B'.$fila, $array[$i][1]);
                    $hoja->getStyle("B".$fila)->applyFromArray($borderArray);
                    $hoja->getStyle('B'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $hoja->setCellValue('C'.$fila, $array[$i][2]);
                    $hoja->getStyle("C".$fila)->applyFromArray($borderArray);
                    $hoja->getStyle('C'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $hoja->setCellValue('D'.$fila, $array[$i][3]);
                    $hoja->getStyle("D".$fila)->applyFromArray($borderArray);
                    $hoja->getStyle('D'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $hoja->setCellValue('E'.$fila, $array[$i][4]);
                    $hoja->getStyle('E'.$fila)->applyFromArray($borderArray);
                    $hoja->getStyle('E'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $hoja->setCellValue('F'.$fila, $array[$i][5]);
                    $hoja->getStyle('F'.$fila)->applyFromArray($borderArray);
                    $hoja->getStyle('F'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $hoja->setCellValue('G'.$fila, $array[$i][6]);
                    $hoja->getStyle('G'.$fila)->applyFromArray($borderArray);
                    $hoja->getStyle('G'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $hoja->setCellValue('H'.$fila, $array[$i][7]);
                    $hoja->getStyle('H'.$fila)->applyFromArray($borderArray);
                    $hoja->getStyle('H'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $hoja->setCellValue('I'.$fila, $array[$i][8]);
                    $hoja->getStyle('I'.$fila)->applyFromArray($borderArray);
                    $hoja->getStyle('I'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $hoja->setCellValue('J'.$fila, $array[$i][9]);
                    $hoja->getStyle('J'.$fila)->applyFromArray($borderArray);
                    $hoja->getStyle('J'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                }
                else{
                    $i--;
                    $grup = "X";
                    $fila--;
                }
                if ($contGrup==$cantGrup) {
                    $banCambio=false;
                }
                if ($contGrup<0) {
                    $banCambio=true;
                }
                if ($banCambio) {
                    $contGrup++;
                }
                else{
                    $contGrup--;
                }
                // echo $fila." ". $grup. "<br>";
                $fila++;
            }
        }
        $writer = new Xlsx($spreadsheet);        
        $writer =IOFactory::createWriter($spreadsheet, 'Xlsx');
        // $writer->save('php://output');
        $writer->save("../../Excel/ListasAceptados/".$nom.'.xlsx');
    }
    $ban = true;
    if ($_SERVER['REQUEST_METHOD']==="POST") {
        $carrera=$_POST['carrera'];
        $queryCar = "SELECT nomCarrera FROM carreras WHERE idCarrera = {$carrera};";
        $resultado = mysqli_query($db, $queryCar);
        $nomCarrera= mysqli_fetch_assoc($resultado)['nomCarrera'];
        excel($nomCarrera, $db, $carrera);
        $zip = new ZipArchive();
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
            $ban = false;
        }
        $dir =scandir('../../Excel/ListasAceptados/',1);
        foreach($dir as $arc){
            if ('../../Excel/ListasAceptados/'.$arc != "../../Excel/ListasAceptados/.." && '../../Excel/ListasAceptados/'.$arc != "../../Excel/ListasAceptados/.") {
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
            <select name="carrera" id="carrera" required>
                <option value="" disabled selected>--Seleccione Carrera--</option>
                <?php while($carrera = mysqli_fetch_assoc($resultado)):?>
                    <option value="<?php echo $carrera['idCarrera']?>"><?php echo $carrera['nomCarrera']?></option>    
                <?php endwhile;?>
            </select>
            <input type="submit" value="Generar Lista">
        </div>
    </form>
</main>
<?php 
    inlcuirTemplate('footer');
    if ($ban && $_SERVER['REQUEST_METHOD']==="POST") {
        echo "<script>exito('Lista de Aceptados Generada');</script>";
    }
?>
