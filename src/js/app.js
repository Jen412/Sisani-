const { task } = require("gulp");
const { Value } = require("sass");

let archivo = document.querySelector('#importA');
	archivo.addEventListener('change', () => {
	document.querySelector('#nombre').innerText =
	archivo.files[0].name;});

function mostrarContenido(){
	document.getElementById('todas').style.display ='flex';
	document.getElementById('especifica').style.display ='none';
}

function mostrarContenido2(){
	document.getElementById('especifica').style.display ='flex';
	document.getElementById('todas').style.display ='none';
}

function checkLetters(e) {
    tecla = (document.all) ? e.keyCode : e.which;
	//Tecla de retroceso para borrar, siempre la permite
    if (tecla == 8) {
        return true;
    }
	// Patron de entrada, en este caso solo acepta numeros y letras
    patron = /[A-Za-z\s]+/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

function checkNumber(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    //Tecla de retroceso para borrar, siempre la permite
    if (tecla == 8) {
        return true;
    }
    // Patron de entrada, en este caso solo acepta numeros y letras
    patron = /^\d/;
    tecla_final = String.fromCharCode(tecla);
    console.log(patron.test(tecla_final));
    return patron.test(tecla_final); 
}

function buscarAlumno(e){
    let solicitud = document.querySelector("#numFicha").value;
    $.ajax({
        url: '../../ajaxPHP/buscar.php',
        type: 'POST',
        data: {solicitud},
        success: function(respuesta){
            let datos = JSON.parse(respuesta);
            let nombre ="";
            let prom = "";
            const promedio = document.querySelector("#prom");
            const nombreAlumno = document.querySelector("#nomAlumno");
            datos.forEach(alumno => {
                nombre =alumno['nom'];
                prom = alumno['prom'];
            });
            promedio.value = prom;
            nombreAlumno.value= nombre;
        }
    });
}

function buscarAlumno2(e){
    let solicitud = document.querySelector("#numFicha").value; 
    $("#numFicha").autocomplete({
        source : items, 
        select : function (event, item){
            let params = {
                alumno: item.item.value
            };
            $.post('../../ajaxPHP/buscar.php', params, function (respuesta) {
                console.log(respuesta);
            });
        }
    })
    
    $.ajax({
        url: '../../ajaxPHP/buscar.php',
        type: 'POST',
        data: {solicitud},
        success: function(respuesta){
            let datos = JSON.parse(respuesta);
            let nombre ="";
            let prom = "";
            const promedio = document.querySelector("#prom");
            const nombreAlumno = document.querySelector("#nomAlumno");
            datos.forEach(alumno => {
                nombre =alumno['nom'];
                prom = alumno['prom'];
            });
            promedio.value = prom;
            nombreAlumno.value= nombre;
        }
    });
}

function buscarAlumnoProm(e){
    let solicitud = document.querySelector("#numFicha").value;
    $.ajax({
        url: '../../ajaxPHP/buscarProm.php',
        type: 'POST',
        data: {solicitud},
        success: function(respuesta){
            let datos = JSON.parse(respuesta);
            let nombre ="";
            let prom = "";
            const promedio = document.querySelector("#prom");
            const nombreAlumno = document.querySelector("#nomAlumno");
            datos.forEach(alumno => {
                nombre =alumno['nom'];
                prom = alumno['prom'];
            });
            promedio.value = prom;
            nombreAlumno.value= nombre;
        }
    });
}


