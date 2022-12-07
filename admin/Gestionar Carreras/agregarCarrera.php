<?php  
    require "../../includes/funciones.php";
    require "../../includes/config/database.php";
    inlcuirTemplate('header');
    $db = conectarDB();

    $nombreCarrera = "";
    $idCarrera= "";
    $errores = [];
    $ban= false;
    if ($_SERVER['REQUEST_METHOD']==="POST"){
        $idCarrera= $_POST['id'];
        $nombreCarrera = $_POST['nombre'];
        $query = "SELECT nomCarrera FROM carreras WHERE idCarrera = ${idCarrera}";
        $nombre = mysqli_fetch_assoc(mysqli_query($db, $query))['nomCarrera'] ?? null;
        if ($nombre) {
            $errores[]= "Id invalido pertenece a la carrera ${nombre}";
        }
        if (!$nombreCarrera) {
            $errores [] = "El nombre de la Carrera es Obligatoria";
        }
        if (!$idCarrera) {
            $errores [] = "El id de la Carrera es Obligatoria";
        }

        if (empty($errores)) {
            $nombreCarrera=mb_strtoupper($nombreCarrera);
            $query ="INSERT INTO `carreras`(`idCarrera`, `nomCarrera`) VALUES ('${idCarrera}','${nombreCarrera}')";
            $resultado = mysqli_query($db, $query);
            if ($resultado) {
                $ban=true;
            }
        }
    }
?>
<main class="agregarCarrera">
    <section class="w80">
        <h1>Agregar Carrera</h1>
        <form class="formAC" method="post">
            <?php foreach($errores as $error): ?>
                <div class="alerta error">
                    <?php  echo $error; ?>
                </div>
            <?php    endforeach;?>

            <div class="id">
                <label for="id">Id Carrera</label>
                <input type="number" onblur="buscarCarrera(event)" name="id" id="id" min="1"  required placeholder="Id Carrera Ej: 4">
            </div>                
            <div class="nombreCarrera">
                <label for="nombre">Nombre Carrera</label>
                <input type="text" name="nombre" id="nombre" required placeholder="Nombre de la Carrera">
            </div>                
            <input class="agregar" type="submit" value="Agregar">
        </form>
    </section>
</main>
<?php 
    inlcuirTemplate('footer');
    if ($ban && $_SERVER['REQUEST_METHOD']==="POST") {
        echo "<script>mostrarAlerta('Carrera Agregada Correctamente', '/admin/Gestionar%20Carreras/GestionarCarreras.php');</script>";
    }
?>