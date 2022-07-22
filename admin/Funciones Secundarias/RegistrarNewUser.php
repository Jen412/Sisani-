<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    require "../../includes/config/database.php";
    if (!$auth) {
       header('location: /'); die();
    }
    if ($_SESSION['role']!="admin") {
        header('location: /admin/index.php'); 
        die();
    }
    inlcuirTemplate('header');
    $db = conectarDB();

    $email ="";
    $nombre="";
    $apellido="";
    $tipoUser="";
    $password ="";
    $passwordCon="";
    $errores =[];
    $ban = true;
    if ($_SERVER['REQUEST_METHOD']==="POST") {
        $email =mysqli_real_escape_string($db, $_POST['email']);
        $nombre=mysqli_real_escape_string($db, $_POST['nombre']);
        $apellido=mysqli_real_escape_string($db, $_POST['apellido']);
        $tipoUser=$_POST['tipoUsuario'];
        $password =mysqli_real_escape_string($db, $_POST['password']);
        $passwordCon=mysqli_real_escape_string($db, $_POST['passwordCon']);
        if ($password != $passwordCon) {
            $errores[] ="No Coincidaden las contraseñas";
        }
        if (empty($errores)) {
            $fecha = date('Y-m-d');
            $password = password_hash($password, PASSWORD_DEFAULT);
            $query ="INSERT INTO users(`email`, `password`, `nomUsuario`, `apellidoUsuario`, `create`, `role`) VALUES ('{$email}','{$password}','{$nombre}','{$apellido}','{$fecha}','$tipoUser')";
            // $resultado = mysqli_query($db, $query);
        }else{
            $ban = false;
        }
    }
?>
<main class="RegistroNuevoUsuario">
    <section class="w80">
        <h1>Registrar Nuevo Usuario</h1>
        <form method="POST">
            <div class="email">
                <label for="email">Email</label>
                <input type="email" name="email" id="email">           
            </div>
            <div class="nombreUser">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre"onkeypress="return checkLetters(event);">           
            </div>
            <div class="apellido">
                <label for="apellido">Apellido</label>
                <input type="text" name="apellido" id="apellido"onkeypress="return checkLetters(event);">           
            </div>
            <div class="tipoUsuario">
                <label for="tipoUsuario">Tipo de Usuario</label>
                <select name="tipoUsuario" id="tipoUsuario">
                    <option disabled selected>--Seleccione--</option>
                    <option value="admin">Administrador</option>
                    <option value="maestro">Docente</option>
                </select>           
            </div>
            <div class="password">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password">           
            </div>
            <div class="passwordCon">
                <label for="passwordCon">Confirmar Contraseña</label>
                <input type="password" name="passwordCon" id="passwordCon">           
            </div>
            <div class="but">
                <input type="submit" value="Registrar">
            </div>
        </form>
    </section>
</main>
<?php 
    inlcuirTemplate('footer');
    if ($ban && $_SERVER['REQUEST_METHOD']==="POST") {
        echo "<script>exito('Usuario Registrado');</script>";
    }
?>