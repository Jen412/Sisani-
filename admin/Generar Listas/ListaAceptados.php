<?php  
    require "../../includes/funciones.php";  
    $auth = estaAutenticado();
    require "../../vendor/autoload.php";
    require "../../includes/config/database.php";
    require "../../helpers/helpers.php";

    use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory, Style\Alignment};
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    
    if (!$auth) {
       header('location: /'); die();
    }
    if ($_SESSION['role']!="admin") {
        header('location: /admin/index.php'); 
        die();
    }
    inlcuirTemplate('header');
    $db = conectarDB();
    $query = "SELECT c.idCarrera, c.nomCarrera FROM carreras AS c JOIN detalles_config as dc WHERE c.idCarrera=dc.idCarrera AND dc.cantidadGrupos >0 AND dc.idConfig =1;";
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
        $grupEsp = $_POST['lista_especial'] ?? null;
        
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setTitle($nom);
        //Lista general de estudiantes
        $hoja = $spreadsheet->getActiveSheet();
        $hoja->setTitle("Lista General");
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
        $hoja->getStyle("A3:J3")->getFont()->setBold(true);
        $hoja->getStyle("A3:J3")->getFont()->setSize(12);

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
        $hoja->getColumnDimension('G')->setWidth(40);
        $hoja->setCellValue('H3', "Prom Ceneval");
        $hoja->getColumnDimension('H')->setWidth(15); 
        $hoja->setCellValue('I3', "Prom Final");
        $hoja->getColumnDimension('I')->setWidth(15);
        $hoja->setCellValue('J3', "Grupo");
        $hoja->getColumnDimension('J')->setWidth(15);
        
        //Lista de alumnos rechazados
        $hoja2 = $spreadsheet->createSheet();
        $hoja2->setTitle("Lista Rechazados");
        $hoja2->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $hoja2->mergeCells("A1:G1");
        $hoja2->getStyle("A1")->getFont()->setSize(15);
        $hoja2->getStyle("A1")->getFont()->setBold(true);
        $hoja2->setCellValue("A1","INSTITUTO TECNOLOGICO DE CIUDAD GUZMAN  ");
        $hoja2->mergeCells("A2:B2");
        $hoja2->getStyle("A2")->getFont()->setSize(13);
        $hoja2->getStyle("A2:B2")->getFont()->setBold(true);
        $hoja2->setCellValue("A2","Lista Rechazados");
        $hoja2->getStyle("A2:E2")->applyFromArray($borderArray);
        $hoja2->getStyle("A3")->applyFromArray($borderArray);
        $hoja2->getStyle("B3")->applyFromArray($borderArray);
        $hoja2->getStyle("C3")->applyFromArray($borderArray);
        $hoja2->getStyle("D3")->applyFromArray($borderArray);
        $hoja2->getStyle("E3")->applyFromArray($borderArray);
        $hoja2->getStyle("F3")->applyFromArray($borderArray);
        $hoja2->getStyle("G3")->applyFromArray($borderArray);
        $hoja2->getStyle("H3")->applyFromArray($borderArray);
        $hoja2->getStyle("I3")->applyFromArray($borderArray);
        $hoja2->getStyle("A3:H3")->getFont()->setBold(true);
        $hoja2->getStyle("A3:H3")->getFont()->setSize(12);

        $hoja2->getColumnDimension('A')->setWidth(15);
        $hoja2->setCellValue('A3', "Ficha");
        $hoja2->getColumnDimension('B')->setWidth(20);
        $hoja2->setCellValue('B3', "Nombre");
        $hoja2->getColumnDimension('C')->setWidth(20);
        $hoja2->setCellValue('C3', "Apellido Paterno");
        $hoja2->getColumnDimension('D')->setWidth(20);
        $hoja2->setCellValue('D3', "Apellido Materno");
        $hoja2->setCellValue('E3', "PromB");
        $hoja2->getColumnDimension('E')->setWidth(15);
        $hoja2->setCellValue('F3', $nombreMat1);
        $hoja2->getColumnDimension('F')->setWidth(20);
        $hoja2->setCellValue('G3', $nombreMat2);
        $hoja2->getColumnDimension('G')->setWidth(40);
        $hoja2->setCellValue('H3', "Prom Ceneval");
        $hoja2->getColumnDimension('H')->setWidth(15);
        $hoja2->setCellValue('I3', "Prom Final");
        $hoja2->getColumnDimension('I')->setWidth(15); 
        
        $queryAlu = "SELECT solicitud, alu_apeP, alu_apeM, alu_nombre, alu_prom, cal_ceneval from alumnos WHERE idCarrera = {$id} && alu_prom !=0 && cal_ceneval !=0";
        $resultadoAlu = mysqli_query($db, $queryAlu);
        $queryAluInv = "SELECT solicitud, alu_apeP, alu_apeM, alu_nombre, alu_prom, cal_ceneval from alumnos WHERE idCarrera = {$id} && (alu_prom =0 || cal_ceneval =0)";
        $resultadoAluInv = mysqli_query($db, $queryAluInv);
        $fila = 4;
        $array = Array();
        $array2 = Array();
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
            # $promFin = ((($alumno['alu_prom']*0.30)+($calMat1*0.10)+($calMat2*0.10)+($calMat3*0.10)+((($alumno['cal_ceneval']-700)/6)*0.40))*6)+700;
           
            $promFin = (($alumno['alu_prom']*30)+($calMat1*15)+($calMat3*15)+($alumno['cal_ceneval']*40))/10;
            //Se sacan los datos de la BDD y se genera calificacion promedio final 
            $arr =[$alumno['solicitud'], $alumno['alu_nombre'], $alumno['alu_apeP'], $alumno['alu_apeM'], $alumno['alu_prom'], $calMat1, $calMat2, $calMat3, $alumno['cal_ceneval'], $promFin];
            if ($calMat1 ==0 && $calMat2 ==0) {
                array_push($array2,$arr);
            }else
                array_push($array,$arr);
        }
        
        while ($alumno = mysqli_fetch_assoc($resultadoAluInv)) {
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
            # $promFin = ((($alumno['alu_prom']*0.30)+($calMat1*0.10)+($calMat2*0.10)+($calMat3*0.10)+((($alumno['cal_ceneval']-700)/6)*0.40))*6)+700;
            $promFin = (($alumno['alu_prom']*30)+($calMat1*15)+($calMat3*15)+($alumno['cal_ceneval']*40))/10;
            //Se sacan los datos de la BDD y se genera calificacion promedio final 
            $arr =[$alumno['solicitud'], $alumno['alu_nombre'], $alumno['alu_apeP'], $alumno['alu_apeM'], $alumno['alu_prom'], $calMat1, $calMat2, $calMat3, $alumno['cal_ceneval'], $promFin];
            array_push($array2,$arr);
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
        $cantAcep =0; 
        $grupos=generarGrupos($cantGrup, $grupEsp);
        if ($grupEsp !=null) {
            $cantGrup--;
            $cantAcep = ($cantGrup)*$cantXGrup;
        }
        else{
            $cantAcep= $cantGrup*$cantXGrup;
        }
        $banCambio =true;
        $contGrup=0;
        $lista = Array();
        $listaRechazados = Array();
        $alumCont=0;
        if ($grupEsp !=null) {
            for ($i=0; $i < 40; $i++) { 
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
                $hoja->setCellValue('K'.$fila, "A");
                $hoja->getStyle('K'.$fila)->applyFromArray($borderArray);
                $hoja->getStyle('K'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $solicitud = $array[$i][0];
                $nombre = $array[$i][1];
                $apellidoP = $array[$i][2];
                $apellidoM= $array[$i][3];
                $prom= $array[$i][4];
                $cal1 = $array[$i][5];
                $cal2 = $array[$i][6];
                $cal3 = $array[$i][7];
                $calCeneval =$array[$i][8];
                $promfin =$array[$i][9];
                
                $alumno = [$solicitud, $nombre, $apellidoP,  $apellidoM, $prom, $cal1, $cal2, $cal3, $calCeneval, $promfin, "A"];
                array_push($lista, $alumno);
                $alumCont++;
                $fila++;
            }
        }   
        

        // foreach ($array as $alumnoArray) {
        //     $prom = intval($alumnoArray[5]) + intval($alumnoArray[6])/2;
        //     if (($alumnoArray[4] =="0" || $alumnoArray[8] =="0" || $prom ==0)) {
        //         array_push($listaRechazados, $alumnoArray);
        //     }
        // }

        foreach ($array2 as $alumnoArray) {
            array_push($listaRechazados, $alumnoArray);
        }
        
        // $array = array_filter($array, function ($alumnoArray) {

        // return$alumnoArray[5] != '0' && $alumnoArray[6] !='0';
        // });

        //echo "<pre>";
        //var_dump($array);
        //echo "</pre>";
        
        echo count($array);

        $contArray = 0;
        if ($grupEsp != null) {
            $contArray = $alumCont;
        }
        else{
            $contArray= count($array);
        }
        for ($i=0; $i <$contArray; $i++) { 
            if ($i< $cantAcep) {
                $grup="";
                if($contGrup >-1 && $contGrup < $cantGrup){
                    $grup= $grupos[$contGrup];
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
                    // $hoja->setCellValue('H'.$fila, $array[$i][7]);
                    // $hoja->getStyle('H'.$fila)->applyFromArray($borderArray);
                    // $hoja->getStyle('H'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $hoja->setCellValue('H'.$fila, $array[$i][8]);
                    $hoja->getStyle('H'.$fila)->applyFromArray($borderArray);
                    $hoja->getStyle('H'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $hoja->setCellValue('I'.$fila, $array[$i][9]);
                    $hoja->getStyle('I'.$fila)->applyFromArray($borderArray);
                    $hoja->getStyle('I'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $hoja->setCellValue('J'.$fila, $grup);
                    $hoja->getStyle('J'.$fila)->applyFromArray($borderArray);
                    $hoja->getStyle('J'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    // se les asigna grupo a cada alumno para despues guardarse en un array para formatearlos 
                    $solicitud = $array[$i][0];
                    $nombre = $array[$i][1];
                    $apellidoP = $array[$i][2];
                    $apellidoM= $array[$i][3];
                    $prom= $array[$i][4];
                    $cal1 = $array[$i][5];
                    $cal2 = $array[$i][6];
                    $cal3 = $array[$i][7];
                    $calCeneval =$array[$i][8];
                    $promfin =$array[$i][9];
                    
                    $alumno = [$solicitud, $nombre, $apellidoP,  $apellidoM, $prom, $cal1, $cal2, $cal3, $calCeneval, $promfin, $grup];
                    array_push($lista, $alumno);
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
            else{
                //Lista de rechazados
                $solicitud = $array[$i][0];
                $nombre = $array[$i][1];
                $apellidoP = $array[$i][2];
                $apellidoM= $array[$i][3];
                $prom= $array[$i][4];
                $cal1 = $array[$i][5];
                $cal2 = $array[$i][6];
                $cal3 = $array[$i][7];
                $calCeneval =$array[$i][8];
                $promfin =$array[$i][9];
                $alumno = [$solicitud, $nombre, $apellidoP, $apellidoM, $prom, $cal1, $cal2, $cal3, $calCeneval, $promfin];
                array_push($listaRechazados, $alumno);
            }
        }
        $fila =4;
        for ($i=0; $i < count($listaRechazados); $i++) { 
            $hoja2->setCellValue('A'.$fila, $listaRechazados[$i][0]);
            $hoja2->getStyle('A'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $hoja2->getStyle("A".$fila)->applyFromArray($borderArray);
            $hoja2->setCellValue('B'.$fila, $listaRechazados[$i][1]);
            $hoja2->getStyle("B".$fila)->applyFromArray($borderArray);
            $hoja2->getStyle('B'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $hoja2->setCellValue('C'.$fila, $listaRechazados[$i][2]);
            $hoja2->getStyle("C".$fila)->applyFromArray($borderArray);
            $hoja2->getStyle('C'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $hoja2->setCellValue('D'.$fila, $listaRechazados[$i][3]);
            $hoja2->getStyle("D".$fila)->applyFromArray($borderArray);
            $hoja2->getStyle('D'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $hoja2->setCellValue('E'.$fila, $listaRechazados[$i][4]);
            $hoja2->getStyle('E'.$fila)->applyFromArray($borderArray);
            $hoja2->getStyle('E'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $hoja2->setCellValue('F'.$fila, $listaRechazados[$i][5]);
            $hoja2->getStyle('F'.$fila)->applyFromArray($borderArray);
            $hoja2->getStyle('F'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $hoja2->setCellValue('G'.$fila, $listaRechazados[$i][6]);
            $hoja2->getStyle('G'.$fila)->applyFromArray($borderArray);
            $hoja2->getStyle('G'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            // $hoja2->setCellValue('H'.$fila, $listaRechazados[$i][7]);
            // $hoja2->getStyle('H'.$fila)->applyFromArray($borderArray);
            // $hoja2->getStyle('H'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $hoja2->setCellValue('H'.$fila, $listaRechazados[$i][8]);
            $hoja2->getStyle('H'.$fila)->applyFromArray($borderArray);
            $hoja2->getStyle('H'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $hoja2->setCellValue('I'.$fila, $listaRechazados[$i][9]);
            $hoja2->getStyle('I'.$fila)->applyFromArray($borderArray);
            $hoja2->getStyle('I'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);            
            $fila++;
        }
        if ($grupEsp!=null) {
            agregarGrupoEsp($spreadsheet, $lista, $borderArray);
        }
        // echo "<pre>";
        // var_dump($lista);
        // echo "</pre>";
        // exit;
        crearGruposExcel($spreadsheet, $grupos, $lista, $borderArray);
        $writer = new Xlsx($spreadsheet);        
        $writer =IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save("../../Excel/ListasAceptados/".$nom.'.xlsx');
    }
    function crearGruposExcel($spreadsheet, $grupos, $lista, $borderArray){
        for ($i=0; $i <count($grupos); $i++) { 
            $letraGrupo = $grupos[$i];
            $grupo = $spreadsheet->createSheet();
            $grupo->setTitle("Grupo ". $grupos[$i]);
            $grupo->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $grupo->mergeCells("A1:G1");
            $grupo->getStyle("A1")->getFont()->setSize(15);
            $grupo->getStyle("A1")->getFont()->setBold(true);
            $grupo->setCellValue("A1","INSTITUTO TECNOLOGICO DE CIUDAD GUZMAN ");
            $grupo->mergeCells("A2:B2");
            $grupo->getStyle("A2")->getFont()->setSize(13);
            $grupo->getStyle("A2:B2")->getFont()->setBold(true);
            $grupo->setCellValue("A2","Grupo ". $grupos[$i]);
            $grupo->getStyle("A2:D2")->applyFromArray($borderArray);
            $grupo->getStyle("A3")->applyFromArray($borderArray);
            $grupo->getStyle("B3")->applyFromArray($borderArray);
            $grupo->getStyle("C3")->applyFromArray($borderArray);
            $grupo->getStyle("D3")->applyFromArray($borderArray);
            $grupo->getStyle("E3")->applyFromArray($borderArray);
            $grupo->getStyle("A3:E3")->getFont()->setBold(true);
            $grupo->getStyle("A3:E3")->getFont()->setSize(12);

            $grupo->getColumnDimension('A')->setWidth(15);
            $grupo->setCellValue('A3', "Ficha");
            $grupo->getColumnDimension('B')->setWidth(20);
            $grupo->setCellValue('B3', "Nombre");
            $grupo->getColumnDimension('C')->setWidth(20);
            $grupo->setCellValue('C3', "Apellido Paterno");
            $grupo->getColumnDimension('D')->setWidth(20);
            $grupo->setCellValue('D3', "Apellido Materno");
            $grupo->getColumnDimension('E')->setWidth(20);
            $grupo->setCellValue('E3', "Prom Final");
            $fila=4;
            for ($j=0; $j <count($lista); $j++) { 
                if ($lista[$j][10] === $letraGrupo) {
                    $grupo->setCellValue('A'.$fila, $lista[$j][0]);
                    $grupo->getStyle('A'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $grupo->getStyle("A".$fila)->applyFromArray($borderArray);
                    $grupo->setCellValue('B'.$fila, $lista[$j][1]);
                    $grupo->getStyle("B".$fila)->applyFromArray($borderArray);
                    $grupo->getStyle('B'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $grupo->setCellValue('C'.$fila, $lista[$j][2]);
                    $grupo->getStyle("C".$fila)->applyFromArray($borderArray);
                    $grupo->getStyle('C'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $grupo->setCellValue('D'.$fila, $lista[$j][3]);
                    $grupo->getStyle("D".$fila)->applyFromArray($borderArray);
                    $grupo->getStyle('D'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $grupo->setCellValue('E'.$fila, $lista[$j][9]);
                    $grupo->getStyle("E".$fila)->applyFromArray($borderArray);
                    $grupo->getStyle('E'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $fila++;
                }
            }
        }
    }
    function agregarGrupoEsp($spreadsheet,$lista, $borderArray){
        $letraGrupo = "A";
        $grupo = $spreadsheet->createSheet();
        $grupo->setTitle("Grupo ". "A");
        $grupo->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $grupo->mergeCells("A1:G1");
        $grupo->getStyle("A1")->getFont()->setSize(15);
        $grupo->getStyle("A1")->getFont()->setBold(true);
        $grupo->setCellValue("A1","INSTITUTO TECNOLOGICO DE CIUDAD GUZMAN ");
        $grupo->mergeCells("A2:B2");
        $grupo->getStyle("A2")->getFont()->setSize(13);
        $grupo->getStyle("A2:B2")->getFont()->setBold(true);
        $grupo->setCellValue("A2","Grupo ". "A");
        $grupo->getStyle("A2:D2")->applyFromArray($borderArray);
        $grupo->getStyle("A3")->applyFromArray($borderArray);
        $grupo->getStyle("B3")->applyFromArray($borderArray);
        $grupo->getStyle("C3")->applyFromArray($borderArray);
        $grupo->getStyle("D3")->applyFromArray($borderArray);
        $grupo->getStyle("A3:D3")->getFont()->setBold(true);
        $grupo->getStyle("A3:D3")->getFont()->setSize(12);

        $grupo->getColumnDimension('A')->setWidth(15);
        $grupo->setCellValue('A3', "Ficha");
        $grupo->getColumnDimension('B')->setWidth(20);
        $grupo->setCellValue('B3', "Nombre");
        $grupo->getColumnDimension('C')->setWidth(20);
        $grupo->setCellValue('C3', "Apellido Paterno");
        $grupo->getColumnDimension('D')->setWidth(20);
        $grupo->setCellValue('D3', "Apellido Materno");
        $fila=4;
        for ($j=0; $j <count($lista); $j++) { 
            if ($lista[$j][10] === $letraGrupo) {
                $grupo->setCellValue('A'.$fila, $lista[$j][0]);
                $grupo->getStyle('A'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $grupo->getStyle("A".$fila)->applyFromArray($borderArray);
                $grupo->setCellValue('B'.$fila, $lista[$j][1]);
                $grupo->getStyle("B".$fila)->applyFromArray($borderArray);
                $grupo->getStyle('B'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $grupo->setCellValue('C'.$fila, $lista[$j][2]);
                $grupo->getStyle("C".$fila)->applyFromArray($borderArray);
                $grupo->getStyle('C'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $grupo->setCellValue('D'.$fila, $lista[$j][3]);
                $grupo->getStyle("D".$fila)->applyFromArray($borderArray);
                $grupo->getStyle('D'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $fila++;
            }
        }
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
        //Borra los documentos creados de excel
        $dir =scandir('../../Excel/ListasAceptados/',1);
        foreach($dir as $arc){
            if ('../../Excel/ListasAceptados/'.$arc != "../../Excel/ListasAceptados/.." && '../../Excel/ListasAceptados/'.$arc != "../../Excel/ListasAceptados/.") {
                unlink('../../Excel/ListasAceptados/'.$arc);
            }
        }
        //Borra los Zip creados
        $dir =scandir('../../Excel/',1);
        foreach($dir as $arc){
            if ('../../Excel/'.$arc != "../../Excel/.." && '../../Excel/'.$arc != "../../Excel/.") {
                unlink('../../Excel/'.$arc);
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
            <!-- <div class="check">
                <input type="checkbox" name="lista_especial" id="lista_especial">
                <label for="lista_especial">Primeros 40</label>
            </div> -->
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
