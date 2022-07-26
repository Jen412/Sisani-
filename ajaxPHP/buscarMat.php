<?php 
    require "../includes/config/database.php";
    $rfc = $_POST['rfc'];
    $db = conectarDB();
    if (!empty($rfc)) {
        $query = "SELECT DISTINCT materias.idMateria, materias.nombreMateria FROM materias, maestros, materiagrupo, alumnos, grupos WHERE materiagrupo.idMateria = materias.idMateria AND alumnos.solicitud = grupos.solicitud AND grupos.idGrupo = materiagrupo.idGrupo AND maestros.rfc = '{$rfc}';";
        $resultado = mysqli_query($db, $query);
        if (!$resultado) {
            die("Query ERROR". mysqli_error($db));
        }
        $json = array();
        while($alumno = mysqli_fetch_array($resultado)){
            $json[] =array(
                'idMateria' => $alumno['idMateria'], 
                'nombreMateria' => $alumno['nombreMateria']
            );
        }
        $jsonString = json_encode($json);
        echo $jsonString;
    }
?>