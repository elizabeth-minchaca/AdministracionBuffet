function validarConfiguracion(f) {
	if (f.titulo.value == '') {
		alert('El titulo está vacío');
		f.titulo.focus();
		return false;
	}
	if (f.descripcion.value == '') {
		alert('La descripcion está vacía');
		f.descripcion.focus();
		return false;
	}
	if (f.email.value == '') {
		alert('El e-mail está vacío');
		f.email.focus();
		return false;
	}

	if (!this.validarEmail(f.email.value)) {
		alert("Error: La dirección de correo " + f.email.value
				+ " es incorrecta.");
		f.email.focus();
		return false;
	}

	if (f.paginadoNumero.value < 1 || f.paginadoNumero.value > 21) {
		alert('El número está fuera del rango [1-20]')
		return false;
	}
	if (f.sitioHabilitadoMsj.value == '') {
		alert('El mensaje de deshabilitación está vacío');
		f.titulo.focus();
		return false;
	}
	return true;
}

function validarEmail(email) {
	expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (!expr.test(email))
		return false;
	else
		return true;
}

// Se utiliza para que el campo de texto solo acepte letras
function soloLetras(e) {
	key = e.keyCode || e.which;
	tecla = String.fromCharCode(key).toString();
	//Se define todo el abecedario que se quiere que se muestre
	letras = " áéíóúabcdefghijklmnñopqrstuvwxyzÁÉÍÓÚABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
	especiales = [ 8, 37, 39, 46, 6 ]; // Es la validación del KeyCodes, que teclas recibe el campo de texto
	tecla_especial = false;
	for ( var i in especiales) {
		if (key == especiales[i]) {
			tecla_especial = true;
			break;
		}
	}
	if (letras.indexOf(tecla) == -1 && !tecla_especial) {
		alert('Tecla no aceptada');
		return false;
	}
}

/**
 * Se utiliza para que el campo de texto solo acepte numeros
 */ 
function SoloNumeros(evt) {
	if (window.event) {// asignamos el valor de la tecla a keynum
		keynum = evt.keyCode; // IE
	} else {
		keynum = evt.which; // FF
	}
	// comprobamos si se encuentra en el rango numérico y que teclas no recibirá
	if ((keynum > 47 && keynum < 58) || keynum == 8 || keynum == 13
			|| keynum == 6) {
		return true;
	} else {
		return false;
	}
}
