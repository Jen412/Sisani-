<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    require "../../includes/config/database.php";
    require "../../helpers/helpers.php";
    if (!$auth) {
       header('location: /'); die();
    }
    if ($_SESSION['role']!="admin") {
        header('location: /admin/index.php'); 
        die();
    }
    $ban = true;
    if($_SERVER['REQUEST_METHOD']==="POST"){
        $db = conectarDB();
        $query = "SELECT * FROM carreras";
        $resultado=mysqli_query($db, $query);
        $resultados =[];
        while ($carr=mysqli_fetch_assoc($resultado)) {
            $query ="SELECT * FROM alumnos WHERE idCarrera = {$carr['idCarrera']}";
            $result= mysqli_query($db, $query);    
            $numAlumnos= mysqli_num_rows($result);
            $query = "SELECT cantidadGrupos, num_Alumnos FROM detalles_config WHERE idCarrera = {$carr['idCarrera']} AND idConfig =2;";
            $res = mysqli_query($db, $query);
            $detalles = mysqli_fetch_assoc($res);
            $cantGrupos = $detalles['cantidadGrupos'];
            $cantXGrupo = ($numAlumnos/$cantGrupos);
            $cantXGrupo = round($cantXGrupo);
            $residuo = $numAlumnos%$cantGrupos;
            $grupos=generarGrupos($cantGrupos, null);
            $cont =0;
            $anio=date("y");
            $alumnos = [];
            $carrera = 0;
            while ($alumno = mysqli_fetch_assoc($result)) {
                array_push($alumnos,$alumno['solicitud']);
                $carrera=$alumno['idCarrera'];
                // if ($cont < $cantXGrupo) {
                //     $idgrup=$anio."-".$alumno['idCarrera'].$grupos[0];
                //     $query ="INSERT INTO grupos(idGrupo, solicitud, letraGrupo) VALUES ('{$idgrup}',{$alumno['solicitud']},'{$grupos[0]}')";
                //     echo $idgrup." ". $alumno['solicitud']."<br>";
                //     $res = mysqli_query($db, $query);
                //     array_push($resultados, $res);
                //     $cont++;
                // }
                // else{
                //     $query ="INSERT INTO grupos(idGrupo, solicitud, letraGrupo) VALUES ('{$idgrup}',{$alumno['solicitud']},'{$grupos[1]}')";
                //     echo $idgrup." ". $alumno['solicitud']."<br>";
                //     $res = mysqli_query($db, $query);
                //     array_push($resultados, $res);
                //     $cont =1;
                //     array_shift($grupos); 
                // }
            }
            for ($i=0; $i < count($alumnos); $i++) { 
                if ($cont < $cantXGrupo) {
                    $idgrup=$anio."-".$carrera.$grupos[0];
                    $query ="INSERT INTO grupos(idGrupo, solicitud, letraGrupo) VALUES ('{$idgrup}',{$alumnos[$i]},'{$grupos[0]}')";
                    //echo $query . "<br>";
                    $res = mysqli_query($db, $query);
                    array_push($resultados, $res);
                    $cont++;
                }else{
                    $i--;
                    $cont =0;
                    array_shift($grupos);
                }
            }
        }
		foreach($resultados as $resultado){ 
			if (!$resultado) {
				$ban = false;
                break;
			}
		}
    }
    inlcuirTemplate('header');
?>
<main class="c_grupos">
    <h1>Crear Grupos</h1>
    <form id="x" method="post">
        <input type="submit" value="Automáticamente">
    </form>
</main>
<?php 
    inlcuirTemplate('footer');
    if ($ban && $_SERVER['REQUEST_METHOD']==="POST") {
        echo "<script>mostrarAlerta('Grupos Generados', '/admin/Gestionar%20Grupos/GestionarGrupos.php');</script>";
    }
?>