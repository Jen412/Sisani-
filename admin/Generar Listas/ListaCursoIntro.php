<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    require '../../includes/config/database.php';
    require "../../vendor/autoload.php";

    use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory, Style\Alignment};
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Style\Style;
    if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');

    $db = conectarDB();
    $queryMat = "SELECT * FROM materias;";
    $resulMat = mysqli_query($db, $queryMat);

    $queryCarr= "SELECT * FROM carreras;";
    $resulCarr = mysqli_query($db, $queryCarr);

    $queryGrupo = "SELECT * FROM grupos";
    $resulGrup = mysqli_query($db, $queryGrupo); 

    function excel($nom,$db, $carrera, $materia, $grupo, $nomCarrera){
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
        $hoja->setCellValue("A1","INSTITUTO TECNOLOGICO DE CIUDAD GUZMAN  ".$nomCarrera);
        $hoja->mergeCells("A2:B2");
        $hoja->getStyle("A2")->getFont()->setSize(13);
        $hoja->getStyle("A2:B2")->getFont()->setBold(true);
        $hoja->setCellValue("A2","Lista Curso Inducción Grupo " . $grupo);
        $hoja->getStyle("A2:D2")->applyFromArray($borderArray);
        $hoja->getStyle("A3")->applyFromArray($borderArray);
        $hoja->getStyle("B3")->applyFromArray($borderArray);
        $hoja->getStyle("C3")->applyFromArray($borderArray);
        $hoja->getStyle("D3")->applyFromArray($borderArray);
        $hoja->getColumnDimension('A')->setWidth(15);
        $hoja->setCellValue('A3', "Ficha");
        $hoja->getColumnDimension('B')->setWidth(20);
        $hoja->setCellValue('B3', "Nombre");
        $hoja->getColumnDimension('C')->setWidth(25);
        $hoja->setCellValue('C3', "Apellido Paterno");
        $hoja->getColumnDimension('D')->setWidth(25);
        $hoja->setCellValue('D3', "Apellido Materno");
        
        $hoja->getStyle("A3:D3")->getFont()->setBold(true);
        $hoja->getStyle("A2:D2")->getFont()->setSize(12);
    
        $queryGrupEs = "SELECT dficha.alufic, alunom, aluapp, aluapm FROM dficha, grupos,materia_grupo WHERE dficha.alufic = grupos.alufic AND grupos.letraGrupo = '{$grupo}' AND carcve1 = {$carrera} AND grupos.idGrupo=materia_grupo.idGrupo AND materia_grupo.idMateria = {$materia};";
        $resultado = mysqli_query($db, $queryGrupEs);
        $fila = 4;
        while($alumno = mysqli_fetch_assoc($resultado)){
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
            $fila++;
        }
        $writer = new Xlsx($spreadsheet);        
        $writer =IOFactory::createWriter($spreadsheet, 'Xlsx');
        // $writer->save('php://output');
        $writer->save("../../Excel/ListasCursosInduccion/".$nom.'.xlsx');
    }

    function excel2($nom,$db, $carrera, $materia, $nomCarrera){
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
        $hoja = $spreadsheet->getActiveSheet();
        $hoja->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $hoja->mergeCells("A1:G1");
        $hoja->getStyle("A1")->getFont()->setSize(15);
        $hoja->getStyle("A1")->getFont()->setBold(true);
        $hoja->setCellValue("A1","INSTITUTO TECNOLOGICO DE CIUDAD GUZMAN  ". $nomCarrera);
        $hoja->mergeCells("A2:B2");
        $hoja->getStyle("A2")->getFont()->setSize(13);
        $hoja->getStyle("A2:B2")->getFont()->setBold(true);
        $hoja->setCellValue("A2","Lista Curso Inducción");
        $hoja->getColumnDimension('A')->setWidth(15);
        $hoja->setCellValue('A3', "Ficha");
        $hoja->getColumnDimension('B')->setWidth(20);
        $hoja->setCellValue('B3', "Nombre");
        $hoja->getColumnDimension('C')->setWidth(25);
        $hoja->setCellValue('C3', "Apellido Paterno");
        $hoja->getColumnDimension('D')->setWidth(25);
        $hoja->setCellValue('D3', "Apellido Materno");
        $hoja->getColumnDimension('E')->setWidth(10);
        $hoja->setCellValue('E3', "Grupo");
        $hoja->getStyle("A2:E2")->applyFromArray($borderArray);
        $hoja->getStyle("A3")->applyFromArray($borderArray);
        $hoja->getStyle("B3")->applyFromArray($borderArray);
        $hoja->getStyle("C3")->applyFromArray($borderArray);
        $hoja->getStyle("D3")->applyFromArray($borderArray);
        $hoja->getStyle("E3")->applyFromArray($borderArray);
        $hoja->getStyle("A3:E3")->getFont()->setBold(true);
        $hoja->getStyle("A2:E2")->getFont()->setSize(12);
    
        $queryGrupEs = "SELECT dficha.alufic, alunom, aluapp, aluapm, grupos.letraGrupo FROM dficha, grupos,materia_grupo WHERE dficha.alufic = grupos.alufic AND carcve1 = {$carrera} AND grupos.idGrupo=materia_grupo.idGrupo AND materia_grupo.idMateria = {$materia};";
        $resultado = mysqli_query($db, $queryGrupEs);
        $fila = 4;
        while($alumno = mysqli_fetch_assoc($resultado)){
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
            $hoja->setCellValue('E'.$fila, $alumno['letraGrupo']);
            $hoja->getStyle("E".$fila)->applyFromArray($borderArray);
            $hoja->getStyle('E'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $fila++;
        }
        $writer = new Xlsx($spreadsheet);        
        $writer =IOFactory::createWriter($spreadsheet, 'Xlsx');
        // $writer->save('php://output');
        $writer->save("../../Excel/ListasCursosInduccionGeneral/".$nom.'.xlsx');
    }

    if ($_SERVER['REQUEST_METHOD']==="POST" && $_POST['tipoLista']=="Especifica") {
        $carrera = $_POST['carrera'];
        $materia = $_POST['materia'];
        $grupo = $_POST['grupo'];
        $queryMate = "SELECT nombre_Mat FROM materias WHERE idMateria = {$materia};";
        $resultado = mysqli_query($db, $queryMate);
        $nomMat= mysqli_fetch_assoc($resultado)['nombre_Mat'];
        $queryCar = "SELECT nombcar FROM carreras WHERE idCar = {$carrera};";
        $resultado = mysqli_query($db, $queryCar);
        $nomCarrera= mysqli_fetch_assoc($resultado)['nombcar'];
        $nomArc = $nomMat."_".$nomCarrera."_Grupo".$grupo;
        excel($nomArc,$db,$carrera, $materia, $grupo, $nomCarrera);
        $zip = new ZipArchive();
        $archivo ='../../Excel/'.$nomArc.'.zip';
        if ($zip->open($archivo, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            $zip->addFile("../../Excel/ListasCursosInduccion/".$nomArc.".xlsx");
            $zip->close();
        }
        $fileName = basename($nomArc.".zip");
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
        $dir =scandir('../../Excel/ListasCursosInduccion/',1);
        foreach($dir as $arc){
            if ('../../Excel/ListasCursosInduccion/'.$arc != "../../Excel/ListasCursosInduccion/.." && '../../Excel/ListasCursosInduccion/'.$arc != "../../Excel/ListasCursosInduccion/.") {
                echo ('../../Excel/ListasCursosInduccion/'.$arc. "<br>");
                unlink('../../Excel/ListasCursosInduccion/'.$arc);
            }
        }
    }
    if ($_SERVER['REQUEST_METHOD']==="POST" && $_POST['tipoLista']=="Todas") {
        $materia = $_POST['materia'];
        $queryMate = "SELECT nombre_Mat FROM materias WHERE idMateria = {$materia};";
        $resultado = mysqli_query($db, $queryMate);
        $nomMat= mysqli_fetch_assoc($resultado)['nombre_Mat'];
        $queryCars = "SELECT * FROM carreras";
        $resultado = mysqli_query($db, $queryCars);
        while ($carrera = mysqli_fetch_assoc($resultado)) {
            $nomArc = $nomMat."_".$carrera['nombcar'];
            excel2($nomArc,$db, $carrera['idCar'], $materia, $carrera['nombcar']);
        }
        $zip = new ZipArchive();
        $archivo = '../../Excel/ListasCursos.zip';
        if ($zip->open($archivo, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            $dir =scandir('../../Excel/ListasCursosInduccionGeneral/',1);
            foreach($dir as $arc){
                if ('../../Excel/ListasCursosInduccionGeneral/'.$arc != "../../Excel/ListasCursosInduccionGeneral/.." && '../../Excel/ListasCursosInduccionGeneral/'.$arc != "../../Excel/ListasCursosInduccionGeneral/.") {
                    $zip->addFile('../../Excel/ListasCursosInduccionGeneral/'.$arc);
                }
            }
            echo "<pre>";
            var_dump($zip);
            echo "</pre>";
            $zip->close();
            $fileName = basename('ListasCursos.zip');
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
            $dir =scandir('../../Excel/ListasCursosInduccionGeneral/',1);
            foreach($dir as $arc){
                if ('../../Excel/ListasCursosInduccionGeneral/'.$arc != "../../Excel/ListasCursosInduccionGeneral/.." && '../../Excel/ListasCursosInduccionGeneral/'.$arc != "../../Excel/ListasCursosInduccionGeneral/.") {
                    echo ('../../Excel/ListasCursosInduccionGeneral/'.$arc. "<br>");
                    unlink('../../Excel/ListasCursosInduccionGeneral/'.$arc);
                }
            }
        }
    }
?>
<main class="g_listas">
    <h1>Listas del Curso de Introducción</h1>
        <div class="btnsLista">
            <input type="button" onclick="mostrarContenido();" value="Generar todas las listas por materia" class="btnChoseV">
            <input type="button" onclick="mostrarContenido2();" value="Especificar lista" class="btnChoseA" >
        </div>
        <div >
            <form method="POST" id="todas">
                <label>Selecciona una Materia</label>
                <input type="hidden" name="tipoLista" id="tipoLista" value="Todas">
                <select name="materia" id="materia">
                    <option value="" disabled selected>--Seleccione Materia--</option>
                    <?php while($materia = mysqli_fetch_assoc($resulMat)):?>
                    <option value="<?php echo $materia['idMateria'];?>"><?php echo $materia['nombre_Mat'];?></option>
                    <?php endwhile;?>
                </select>
                <input type="submit" value="Generar" class="btnGenerar">
            </form>
        </div>

        <div >
            <form method="POST" id="especifica">
                <input type="hidden" name="tipoLista" id="tipoLista" value="Especifica">
                <div class="carrera">
                    <label for="">Selecciona una Carrera</label>
                    <select class="carrera" id="carrera" name="carrera">
                        <option value="" disabled selected>--Seleccione Carrera--</option>
                        <?php while($carrera = mysqli_fetch_assoc($resulCarr)):?>
                        <option value="<?php echo $carrera['idCar'];?>"><?php echo $carrera['nombcar'];?></option>
                        <?php endwhile;?>    
                    </select>
                </div>
                <div class="materia">
                    <label for="">Selecciona una Materia</label>
                    <select name="materia" id="materia">
                        <option value="" disabled selected>--Seleccione Materia--</option>
                        <?php
                        $resulMat = mysqli_query($db, $queryMat);
                        while($materia = mysqli_fetch_assoc($resulMat)):?>
                        <option value="<?php echo $materia['idMateria'];?>"><?php echo $materia['nombre_Mat'];?></option>
                        <?php endwhile;?>
                    </select>
                </div>
                <div class="grupo">
                    <label for="">Letra Grupo</label>
                    <select name="grupo" id="grupo">
                        <option value="" disabled selected>--Seleccione Grupo--</option>    
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <?php 
                            while($grupo = mysqli_fetch_assoc($resulGrup)){
                                if ($grupo['letraGrupo']=='C') {
                                    echo '<option value="C">C</option>';
                                }
                                if ($grupo['letraGrupo']=='D') {
                                    echo '<option value="D">D</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
                <input type="submit" value="Generar">
            </form>
        </div>
</main>
<?php 
    inlcuirTemplate('footer');
?>



