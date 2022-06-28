<?php

require 'app.php';

function inlcuirTemplate (string $nombre, bool $inicio=false){
    include TEMPLATES_URL . "/${nombre}.php"; 
}

function estaAutenticado() : bool{
    session_start();
    $auth = $_SESSION['login'];
    if ($auth) {
        return true;
    }
    return false;
}

function getNomCarrera(int $idcar){
    switch ($idcar) {
        case 4:
            $nomCarr = 'INGENIERÍA ELECTRÓNICA';
            break;
        case 5:
            $nomCarr = 'INGENIERÍA MECÁNICA';
            break;
        case 6:
            $nomCarr = 'INGENIERÍA ELÉCTRICA';
            break;
        case 15:
            $nomCarr = 'INGENIERÍA EN SISTEMAS COMPUTACIONALES';
            break;
        case 16:
            $nomCarr = 'INGENIERÍA INDUSTRIAL';
            break;
        case 18:
            $nomCarr = 'MAESTRÍA EN INGENIERÍA ELECTRÓNICA';
            break;
        case 20:
            $nomCarr = 'INGENIERÍA AMBIENTAL';
            break;
        case 21:
            $nomCarr = 'ARQUITECTURA';
            break;
        case 22:
            $nomCarr = 'CONTADOR PÚBLICO';
            break;
        case 23:
            $nomCarr = 'INGENIERÍA EN GESTIÓN EMPRESARIAL';
            break;
        case 24:
            $nomCarr = 'INGENIERÍA INFORMÁTICA';
            break;
        case 25:
            $nomCarr = 'MAESTRÍA EN CIENCIAS DE LA COMPUTACIÓN';
            break;
    }
}