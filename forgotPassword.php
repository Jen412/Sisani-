<?php 
    require 'includes/config/database.php';
    $db = conectarDB();
    $errores =[]; 
    if ($_SERVER['REQUEST_METHOD']==="POST") {
        $email = mysqli_real_escape_string($db,filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
        $password = mysqli_real_escape_string($db,$_POST['pass']);
        $rfc = mysqli_real_escape_string($db,$_POST['rfc']);
        
        $queryBusqueda= "SELECT idUser WHERE email = '{$email}' AND rfc = '{$rfc}';";
        $result = mysqli_query($db, $queryBusqueda);
        $idUser = mysqli_fetch_assoc($result)['idUser'];
        if ($idUser) {
            $passwordhash = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE `users` SET `password`='{$passwordhash}' WHERE idUser = '{$idUser}';";
            $result = mysqli_query($db, $query);
            if ($result) {
                header("location: /index.php");
            }
        }
        else{
            $errores [] = "Usuario inexistente" ;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/build/css/app.css">
    <title>Siseni</title>
</head>
<body class="bg-Azul">
    <main>
        <section>
            <h1>Restablecer Contraseña</h1>
                <?php foreach($errores as $error): ?>
                <div class="alerta error">
                    <?php  echo $error; ?>
                </div>
                <?php    endforeach;?>
            <form method="POST">
                <div class="email">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Email">
                </div>
                <div class="RFC">
                    <label for="RFC">RFC</label>
                    <input type="text" name="RFC" id="RFC" placeholder="RFC" minlength="13">
                </div>
                <div class="pass">
                    <label for="pass">Nueva Contraseña</label>
                    <input type="password" name="pass" id="pass" placeholder="Contraseña">
                </div>
                <div class="iniciar">
                    <button>
                        <ion-icon name="enter-outline" class="size3"></ion-icon>
                        <input type="submit" value="Restablecer">
                    </button>
                </div>
            </form>
        </section>
    </main>
</body>