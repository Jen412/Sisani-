<?php
require "../includes/config/database.php";
$solicitud = $_POST['solicitud'];
$db = conectarDB();
if (!empty($solicitud)) {
    $query = "SELECT solicitud,alu_nombre, alu_apeP, alu_apeM, cal_ceneval FROM alumnos WHERE solicitud = {$solicitud}";
    $resultado = mysqli_query($db, $query);
    if (!$resultado) {
        die("Query ERROR". mysqli_error($db));
    }
    $json = array();
    while($alumno = mysqli_fetch_array($resultado)){
        $json[] =array(
            'ficha' => $alumno['solicitud'], 
            'nom' => $alumno['alu_nombre']. " ". $alumno['alu_apeP']. " ". $alumno['alu_apeM'],
            'prom' => $alumno['cal_ceneval']
        );
    }
    $jsonString = json_encode($json);
    echo $jsonString;
}