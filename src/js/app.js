let archivo = document.querySelector('#importA');
	archivo.addEventListener('change', () => {
	document.querySelector('#nombre').innerText =
	archivo.files[0].name;});

function mostrarContenido(){
	document.getElementById('ocultar').style.display ='flex';
}

function mostrarContenido2(){
	document.getElementById('especifica').style.display ='flex';
}