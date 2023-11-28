<?php

function conectarDB() : mysqli{
    // $db = mysqli_connect('localhost', 'root', '', 'sigacitc_siseni');

    $db = mysqli_connect("localhost","root", "", "siseni");
    if (!$db) {
        echo "Error no se pudo conectar";
        exit;
    }
    return $db;
}