<?php  
    require "../../includes/funciones.php";  $auth = estaAutenticado();
    require "../../includes/config/database.php";
    if (!$auth) {
       header('location: /'); die();
    }
    inlcuirTemplate('header');
    $ban = true;
    if ($_SERVER['REQUEST_METHOD']=="POST"){
        
        include('RespaldoTools.php');
 
        $arrayDbConf['host'] = 'localhost:3308';
        $arrayDbConf['user'] = 'root';
        $arrayDbConf['pass'] = '';
        $arrayDbConf['name'] = 'siseni';
        try {
            $bck = new MySqlBackupLite($arrayDbConf);
            $bck->backUp();
            $bck->downloadFile();            
        }
        catch(Exception $e) {
            echo $e; 
            $ban = false;
        }
        
    }
?>
<main class="RespaldoBDD">
    <section class="w80">
        <h1>Respaldar Base de Datos</h1>
        <form method="POST">
            <button><input type="submit" >Crear Respaldo</button>
        </form>
    </section>
    
</main>
<?php 
    inlcuirTemplate('footer');
    if ($ban && $_SERVER['REQUEST_METHOD']==="POST") {
        echo "<script>exito('Respaldo Realizado');</script>";
    }
?>