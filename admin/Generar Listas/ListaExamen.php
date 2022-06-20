<?php  
    require "../../includes/funciones.php";  
    $auth = estaAutenticado();
    require "../../includes/config/database.php";
    require "../../vendor/autoload.php";

    use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

     if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');
    $db = conectarDB();
    $queryMat ="SELECT * FROM carreras";
    $resultadoMat =mysqli_query($db, $queryMat);
    function excel($nom,$db, $id){
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
        $hoja->setCellValue('E2', "Promedio Examen");
    
        $queryAlu = "SELECT alufic, aluapp, aluapm, alunom, calificacionCeneval from dficha WHERE carcve1 = {$id};";
        $resultado = mysqli_query($db, $queryAlu);
        $fila = 3;
        while($alumno = mysqli_fetch_assoc($resultado)){
            $hoja->setCellValue('A'.$fila, $alumno['alufic']);
            $hoja->setCellValue('B'.$fila, $alumno['alunom']);
            $hoja->setCellValue('C'.$fila, $alumno['aluapp']);
            $hoja->setCellValue('D'.$fila, $alumno['aluapm']);
            $hoja->setCellValue('E'.$fila, $alumno['calificacionCeneval']);
            $fila++;
        }
        $writer = new Xlsx($spreadsheet);        
        $writer =IOFactory::createWriter($spreadsheet, 'Xlsx');
        // $writer->save('php://output');
        $writer->save("../../Excel/ListasCeneval/".$nom.'.xlsx');
    }

    
    if ($_SERVER['REQUEST_METHOD']==="POST" && $_POST['tipoLista']=="General") {
        $queryCars = "SELECT * FROM carreras";
        $resultado = mysqli_query($db, $queryCars);
        while ($carrera = mysqli_fetch_assoc($resultado)) {
            excel($carrera['nombcar'],$db, $carrera['idCar']);
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
                exit;
            }else{
                echo 'No existe el archivo';
            }
        }
    }
    if ($_SERVER['REQUEST_METHOD']==="POST" && $_POST['tipoLista']=="Especifica") {
        $idCarrera = $_POST['carrera'];
        $queryCar = "SELECT nombcar FROM carreras WHERE idCar = {$idCarrera};";
        $resultado = mysqli_query($db, $queryCar);
        $nomCarrera= mysqli_fetch_assoc($resultado)['nombcar'];
        excel($nomCarrera, $db, $idCarrera);
        $fileName = basename($nomCarrera.".xlsx");
        $filePath = '../../Excel/ListasCeneval/'.$fileName;
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
    }
?>
<main class="g_listas">
    <h1>Listas Examen Ceneval</h1>
        <div class="btnsLista">
            <form method="POST">
                <input type="hidden" name="tipoLista" value="General">
                <input type="submit" clas value="Generar todas las listas" class="btnAllV" >
            </form>
            <input type="button" onclick="mostrarContenido();" value="Especificar lista" class="btnChoseA" >
            <form method="POST" id="todas">
                <label>Seleccionar una Carrera</label>
                <input type="hidden" name="tipoLista" value="Especifica">
                <select name="carrera" id="carrera">
                    <option value="" disabled selected>--Seleccione Carrera--</option>
                    <?php while($materia = mysqli_fetch_assoc($resultadoMat)):?>
                        <option value="<?php echo $materia['idCar'];?>"><?php echo $materia['nombcar'];?></option>
                    <?php endwhile;?>
                </select>
                <input type="submit" value="Generar Lista">
            </form>
        </div>
</main>
<?php 
    inlcuirTemplate('footer');
?>



