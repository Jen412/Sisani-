let archivo = document.querySelector('#importA');//Se utiliza en tiempo real a la hora de seleccionar el archivo aparace su nombre
	archivo.addEventListener('change', () => {
	document.querySelector('#nombre').innerText =
	archivo.files[0].name;});

const { task, tree } = require("gulp");
const { Value, renderSync } = require("sass");

function mostrarContenido(){//Muestra el menú de selección para generar todas las listas 
	document.getElementById('todas').style.display ='flex';
	document.getElementById('especifica').style.display ='none';
}

function mostrarContenido2(){//Muestra el menú de selección para generar las listas especificas
	document.getElementById('especifica').style.display ='flex';
	document.getElementById('todas').style.display ='none';
}

function confirmarEliminacion (formulario){
    const result = Swal.fire({
        title: 'Esta seguro de Eliminar',
        text: "No se puede revertir esta accion",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'Si Eliminar'
    }).then((result) => {
        if(result.isConfirmed){
            const form = document.querySelector(formulario);
            console.log(form);
            form.submit();
        }});
}
function borrarDatos (formulario){
    const result = Swal.fire({
        title: '¿Esta seguro de Eliminar?',
        text: "Se Eliminaran todos los datos de los estudiantes",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'Si Eliminar'
    }).then((result) => {
        if(result.isConfirmed){
            const form = document.querySelector(formulario);
            console.log(form);
            form.submit();
        }});
}

function mostrarAlerta(mensaje, url){
    exito(mensaje);
    setTimeout(() => {
        window.location.href = url;
    }, 1500);
}

function exito(mensaje) {
    Swal.fire({
        position: 'center',
        icon: 'success',
        title: mensaje,
        showConfirmButton: false,
        timer: 2000
    });
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

function buscarMaterias(rfc, e) {
    $.ajax({
        url: '../../ajaxPHP/buscarMat.php',
        type: "POST",
        data:{rfc},
        success: respuesta =>{
            let datos = JSON.parse(respuesta);
            let select = document.querySelector("#materiaS");
            datos.forEach(materia =>{
                let option = document.createElement("option");
                option.text = materia['nombreMateria'];
                option.value= materia['idMateria'];
                select.appendChild(option);
            })
        }
    });
}

function buscarGrupo(rfc, e) {
    const idCarrera = document.querySelector("#carreraS").value;
    console.log(idCarrera);
    $.ajax({
        url: '../../ajaxPHP/buscarGrupo.php',
        type: "POST",
        data:{rfc, idCarrera},
        success: respuesta =>{
            let datos = JSON.parse(respuesta);
            let select = document.querySelector("#GrupoS");
            datos.forEach(materia =>{
                let option = document.createElement("option");
                option.text = materia['letraGrupo'];
                option.value= materia['letraGrupo'];
                select.appendChild(option);
            })
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

function buscarCarrera(e) {
    const idCarr = document.querySelector("#id").value;
    $.ajax({
        url: '../../ajaxPHP/buscarCarrera.php',
        type: 'POST',
        data: {idCarr},
        success: function(response){
            let data = JSON.parse(response);
            if (data.idCarrera) {
                const div = document.createElement("div");
                const form = document.querySelector(".formAC");
                const text= document.createTextNode(`ID INVALIDO PERTENECE A LA Carrera  ${data.nomCarrera}`);
                div.classList.add("alerta");
                div.classList.add("error");
                div.appendChild(text);
                if (!form.querySelector(".alerta")) {
                    form.insertAdjacentElement("afterbegin", div)
                }
                setTimeout(() => {
                    form.removeChild(div);
                }, 4500);
            }
        }
    });
}

function visibilidad(e) {
    const seleccion = document.querySelector(".vanish");
    seleccion.classList.add("Con");
    seleccion.classList.remove("vanish");
}