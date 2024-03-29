<?php  
    require "../../includes/funciones.php";  
    $auth = estaAutenticado();
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
    if($_SESSION['role']!="admin"){
        $rfc = $_SESSION['rfc'];
        $queryMaes = "SELECT idMaestro FROM maestros WHERE rfc = '{$rfc}'";
        $resultadoMaes = mysqli_query($db, $queryMaes);
        $idMaestro = mysqli_fetch_assoc($resultadoMaes)["idMaestro"];
    }
    $queryCarr = "SELECT * FROM carreras";
    $queryMat ="SELECT * FROM materias";
    $queryMat2 = "SELECT * FROM materias;";
    $queryGrupo ="SELECT DISTINCT letraGrupo FROM grupos";
    if ($_SESSION['role'] !="admin") {
        $queryCarr ="SELECT DISTINCT carreras.idCarrera, carreras.nomCarrera FROM carreras, maestros, alumnos,grupos, materiagrupo where alumnos.idCarrera =carreras.idCarrera AND grupos.solicitud = alumnos.solicitud && grupos.idGrupo = materiagrupo.idGrupo AND maestros.rfc = '{$rfc}' AND maestros.idMaestro = '{$idMaestro}';";//Las variables empiezan por $ y pueden almacenar instrucciones SQL
        $queryMat2 = "SELECT DISTINCT materias.idMateria, materias.nombreMateria FROM materias, maestros, materiagrupo, alumnos, grupos WHERE materiagrupo.idMateria = materias.idMateria AND alumnos.solicitud = grupos.solicitud AND grupos.idGrupo = materiagrupo.idGrupo AND maestros.rfc = '{$rfc}';";
    }
    
    $resulCarr = mysqli_query($db, $queryCarr);//mysqli_query necesita de un parametro para establecer la conexion y de otro ;
    $resulMatGen = mysqli_query($db, $queryMat2);
    if($_SESSION['role'] === "admin"){
        $resultadoMat = mysqli_query($db, $queryMat);
        $resulGrup = mysqli_query($db, $queryGrupo);
    }
    
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
    
        $queryGrupEs = "SELECT alumnos.solicitud, alu_nombre, alu_apeP, alu_apeM FROM alumnos, grupos, materiagrupo 
        WHERE alumnos.solicitud = grupos.solicitud AND grupos.letraGrupo = '{$grupo}' 
        AND idCarrera = {$carrera} AND grupos.idGrupo=materiagrupo.idGrupo 
        AND materiagrupo.idMateria = {$materia};";
        // echo "<pre>";
        // var_dump($queryGrupEs);
        // echo "</pre>";

        $resultado = mysqli_query($db, $queryGrupEs);
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
    
        $queryGrupEs = "SELECT alumnos.solicitud, alu_nombre, alu_apeP, alu_apeM, grupos.letraGrupo FROM alumnos, grupos,materiagrupo WHERE alumnos.solicitud = grupos.solicitud AND idCarrera = {$carrera} AND grupos.idGrupo=materiagrupo.idGrupo AND materiagrupo.idMateria = {$materia};";
        $resultado = mysqli_query($db, $queryGrupEs);
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
    $ban = true;
    if ($_SERVER['REQUEST_METHOD']==="POST" && $_POST['tipoLista']=="Especifica") {
        $carrera = $_POST['carreraS'];
        $materia = $_POST['materiaS'];
        $grupo = $_POST['grupoS'];
        $queryMate = "SELECT nombreMateria FROM materias WHERE idMateria = {$materia};";
        $resultado = mysqli_query($db, $queryMate);
        $nomMat= mysqli_fetch_assoc($resultado)['nombreMateria'];
        $queryCar = "SELECT nomCarrera FROM carreras WHERE idCarrera = {$carrera};";
        $resultado = mysqli_query($db, $queryCar);
        $nomCarrera= mysqli_fetch_assoc($resultado)['nomCarrera'];
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
            $ban = false;
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
        $queryMate = "SELECT nombreMateria FROM materias WHERE idMateria = {$materia};";
        $resultado = mysqli_query($db, $queryMate);
        $nomMat= mysqli_fetch_assoc($resultado)['nombreMateria'];
        $queryCars = "SELECT * FROM carreras";
        $resultado = mysqli_query($db, $queryCars);
        while ($carrera = mysqli_fetch_assoc($resultado)) {
            $nomArc = $nomMat."_".$carrera['nomCarrera'];
            excel2($nomArc,$db, $carrera['idCarrera'], $materia, $carrera['nomCarrera']);
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
            // echo "<pre>";
            // var_dump($zip);
            // echo "</pre>";
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
                $ban = false;
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
    <h1>Listas del Curso de Inducción</h1>
        <div class="btnsLista">
            <input type="button" onclick="mostrarContenido();" value="Generar todas las listas por materia" class="btnChoseV">
            <input type="button" onclick="mostrarContenido2();" value="Especificar lista" class="btnChoseA" >
        </div>
        <div >
            <form method="POST" id="todas">
                <label>Selecciona una Materia</label>
                <input type="hidden" name="tipoLista" id="tipoLista" value="Todas">
                <select name="materia" id="materia" required>
                    <option value="" disabled selected>--Seleccione Materia--</option>
                    <?php while($materia = mysqli_fetch_assoc($resulMatGen)):?>
                    <option value="<?php echo $materia['idMateria'];?>"><?php echo $materia['nombreMateria'];?></option>
                    <?php endwhile;?>
                </select>
                <input type="submit" value="Generar" class="btnGenerar">
            </form>
        </div>

        <div >
            <form method="POST" id="especifica">
                <input type="hidden" name="tipoLista" id="tipoLista" value="Especifica">
                <div class="carrera">
                    <label for="">Selecciona Carrera</label>
                    <select name="carreraS" id="carreraS"onchange="buscarMaterias('<?php echo $rfc;?>', event);" required>
                        <option value=""disabled selected>--Seleccione Carrera--</option>  
                        <?php while($carrera = mysqli_fetch_assoc($resulCarr)):?><!--como es son varias carreras se guarda la seleccionada en una variable -->
                            <option value="<?php echo $carrera['idCarrera'];?>"><!--la variable contiene referenciando a la db y el query que se esta realizando-->
                                <?php echo $carrera['nomCarrera'];?><!---para mostrar el resultado en pantalla se muestra en una etiqueta del mismo tipo-->
                            </option><!--con la impresion de la variable de la carrera dentro del while mediante el nombre del campo de la tabla-->
                            <!--cuando se selecciona una opcion se presenta el nombre en base a su id, primero se acede al id y despues al nombre-->
                        <?php endwhile;?>  
                    </select>
                </div>
                <div class="materia">
                    <label for="">Selecciona Materia</label>
                    <select name="materiaS" id="materiaS"onchange="buscarGrupo('<?php echo $rfc;?>', event);" required>
                        <option value=""disabled selected>--Seleccione Materia--</option>    
                        <?php 
                        if ($_SESSION['role']==="admin") {
                            while ($materia = mysqli_fetch_assoc($resultadoMat)): ?>
                                <option value="<?php echo $materia['idMateria'];?>"><?php echo $materia['nombreMateria'];?></option> 
                            <?php endwhile;
                        }
                        ?>
                    </select><!--Se envia el id del select desde el formulario conteniendo el valor del id de la materia seleccionada--->
                </div>
                <div class="grupo">
                    <label for="">Grupo</label>
                    <select name="grupoS" id="grupoS" required>
                        <option value="" disabled selected>--Seleccione Grupo--</option>    
                        <?php 
                        if ($_SESSION['role']==="admin") {
                            while($grupo = mysqli_fetch_assoc($resulGrup)){
                                if ($grupo['letraGrupo']=='A') {
                                    echo '<option value="A">A</option>';
                                }
                                if ($grupo['letraGrupo']=='B') {
                                    echo '<option value="B">B</option>';
                                }
                                if ($grupo['letraGrupo']=='C') {
                                    echo '<option value="C">C</option>';
                                }
                                if ($grupo['letraGrupo']=='D') {
                                    echo '<option value="D">D</option>';
                                }
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
    if ($ban && $_SERVER['REQUEST_METHOD']==="POST") {
        echo "<script>exito('Lista del Curso de Introducción Generada');</script>";
    }
?>
