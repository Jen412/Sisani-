<?php 
    require "../includes/config/database.php";
    $db = conectarDB();
    $idCarrera= $_POST['idCarr'];
    $query = "SELECT * FROM `carreras` WHERE idCarrera =${idCarrera}";
    $resultado = mysqli_query($db, $query);
    $carrera= mysqli_fetch_assoc($resultado) ??null;
    $idCar = $carrera['idCarrera'];
    $nomCar=$carrera['nomCarrera'];
    $json = array();
    if ($resultado) {
        $json =[
            "idCarrera"=> $idCar,
            "nomCarrera"=> $nomCar
        ];
        $jsonString =json_encode($json);
        echo $jsonString;
    }
?>