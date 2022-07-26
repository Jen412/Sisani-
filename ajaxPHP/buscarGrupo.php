<?php 
    require "../includes/config/database.php";
    $rfc = $_POST['rfc'];
    $idCarrera = $_POST['idCarrera'];
    $db = conectarDB();
    if (!empty($rfc)) {
        $query = "SELECT DISTINCT grupos.idGrupo, grupos.letraGrupo FROM materias, maestros, materiagrupo, alumnos, grupos WHERE materiagrupo.idMateria = materias.idMateria AND alumnos.solicitud = grupos.solicitud  AND grupos.idGrupo = materiagrupo.idGrupo AND alumnos.idCarrera = '{$idCarrera}' AND maestros.rfc = '{$rfc}';;";
        $resultado = mysqli_query($db, $query);
        if (!$resultado) {
            die("Query ERROR". mysqli_error($db));
        }
        $json = array();
        while($alumno = mysqli_fetch_array($resultado)){
            $json[] =array(
                'letraGrupo' => $alumno['letraGrupo'], 
                'idGrupo' => $alumno['idGrupo']
            );
        }
        $jsonString = json_encode($json);
        echo $jsonString;
    }
?>