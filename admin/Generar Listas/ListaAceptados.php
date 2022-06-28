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
        $hoja->setCellValue('E3', "Carrera");
        $hoja->setCellValue('F3', "PromB");
        $hoja->getColumnDimension('F')->setWidth(15);
        $hoja->setCellValue('G3', "Mat");
        $hoja->getColumnDimension('G')->setWidth(15);
        $hoja->setCellValue('H3', "DH");
        $hoja->getColumnDimension('H')->setWidth(15);
        $hoja->setCellValue('I3', "ICarrera");
        $hoja->getColumnDimension('I')->setWidth(15);
        $hoja->setCellValue('J3', "Prom Ceneval");
        $hoja->getColumnDimension('J')->setWidth(15); 
        $hoja->setCellValue('K3', "Prom Final");
        $hoja->getColumnDimension('J')->setWidth(15);
    
        $queryAlu = "SELECT alufic, aluapp, aluapm, alunom, aluprom, calificacionCeneval from dficha WHERE carcve1 = {$id};";
        $resultadoAlu = mysqli_query($db, $queryAlu);
        $queryMatG = "SELECT idMateriaG, calif FROM calificaciones WHERE alufic = '{}' ";
        $queryMat  = "SELECT nombre_Mat FROM materias WHERE idMateria";

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
            
            $hoja->setCellValue('E'.$fila, $alumno['calificacionCeneval']);
            $hoja->getStyle("E".$fila)->applyFromArray($borderArray);
            $hoja->getStyle('E'.$fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $fila++;
        }
        $writer = new Xlsx($spreadsheet);        
        $writer =IOFactory::createWriter($spreadsheet, 'Xlsx');
        // $writer->save('php://output');
        $writer->save("../../Excel/ListasCeneval/".$nom.'.xlsx');
    }

    if ($_SERVER['REQUEST_METHOD']==="POST") {
        $carrera=$_POST['carrera'];


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
