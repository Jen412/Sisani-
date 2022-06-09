let archivo = document.querySelector('#importA');
	archivo.addEventListener('change', () => {
	document.querySelector('#nombre').innerText =
	archivo.files[0].name;});