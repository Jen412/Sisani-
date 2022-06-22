<?php


require "../includes/config/database.php";
$solicitud = $_POST['solicitud'];
$db = conectarDB();
if (!empty($solicitud)) {
    $query = "SELECT alufic,alunom, aluapp, aluapm, alupro FROM dficha WHERE alufic = {$solicitud}";
    $resultado = mysqli_query($db, $query);
    if (!$resultado) {
        die("Query ERROR". mysqli_error($db));
    }
    $json = array();
    while($alumno = mysqli_fetch_array($resultado)){
        $json[] =array(
            'ficha' => $alumno['alufic'], 
            'nom' => $alumno['alunom']. " ". $alumno['aluapp']. " ". $alumno['aluapm'],
            'prom' => $alumno['alupro']
        );
    }
    $jsonString = json_encode($json);
    echo $jsonString;
}