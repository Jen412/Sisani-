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
    return $nomCarr;
}

function getIdCarr(string $nomCarr){
    $id = 0;
    switch ($nomCarr) {
        case 'INGENIERÍA ELECTRÓNICA':
            $id = 4;
            break;
        case 'INGENIERÍA MECÁNICA':
            $id = 5;
            break;
        case 'INGENIERÍA ELÉCTRICA':
            $id = 6;
            break;
        case 'INGENIERÍA EN SISTEMAS COMPUTACIONALES':
            $id = 15;
            break;
        case 'INGENIERÍA INDUSTRIAL':
            $id = 16;
            break;
        case 'MAESTRÍA EN INGENIERÍA ELECTRÓNICA':
            $id = 18;
            break;
        case'INGENIERÍA AMBIENTAL':
            $id = 20;
            break;
        case 'ARQUITECTURA':
            $id = 21;
            break;
        case 'CONTADOR PÚBLICO':
            $id = 23;
            break;
        case 'INGENIERÍA EN GESTIÓN EMPRESARIAL':
            $id = 23;
            break;
        case 'INGENIERÍA INFORMÁTICA':
            $id = 24;
            break;
        case 'MAESTRÍA EN CIENCIAS DE LA COMPUTACIÓN':
            $id = 25;
            break;
    }
    return $id;
}