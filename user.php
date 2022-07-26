<?php
//importar la conexion
require 'includes/config/database.php';
$db = conectarDB();

//crear un email y password
$email ="fer-410@live.com.mx";
$password = "43254325";

$passwordhash = password_hash($password, PASSWORD_DEFAULT);
//Query para crear el usuario 
$date = date('d-m-Y');
// $query ="INSERT INTO users(email, role, password, created_at) VALUES ('{$email}','maestro','{$passwordhash}','{$date}')";
$query ="INSERT INTO `users`(`email`, `password`, `nomUsuario`, `apellidoUsuario`, `create`, `role`,'rfc') VALUES ('{$email}','{$passwordhash}','Juan Fernando','Brambila Rivera','{$date}','maestro', 'XAXX010101000')";
echo $query;

//exit;
//agregar a base de datos 
$resultado =mysqli_query($db, $query);
var_dump($resultado);
