/* LIMITA O TECLADO PARA NÚMEROS APENAS */
// Ex: onKeyPress="return soNumeros(event)"
function soNumeros(e){
	if (document.all) // Internet Explorer
		var tecla = e.keyCode;
	else
		var tecla = e.which;

	if (tecla > 47 && tecla < 58){ // numeros de 0 a 9
			return true;
	} else{
		if (tecla == 8 || tecla == 0) // backspace
			return true;
		else
			return false;
	}
};

// Ex: onKeyPress="return soNumerosComPonto(event)"
function soNumerosComPonto(e){
	if (document.all) // Internet Explorer
		var tecla = e.keyCode;
	else
		var tecla = e.which;

	if (tecla > 47 && tecla < 58){ // numeros de 0 a 9
			return true;
	} else{
		if (tecla == 8 || tecla == 0 || tecla == 46) // backspace
			return true;
		else
			return false;
	}
};

function soNumerosComNegativo(e){
	if (document.all) // Internet Explorer
		var tecla = e.keyCode;
	else
		var tecla = e.which;

	if (tecla > 47 && tecla < 58){ // numeros de 0 a 9
			return true;
	} else{
		if (tecla == 8 || tecla == 0 || tecla == 45) // backspace
			return true;
		else
			return false;
	}
}

function soNumerosComNegativoComPonto(e){
	if (document.all) // Internet Explorer
		var tecla = e.keyCode;
	else
		var tecla = e.which;

	if (tecla > 47 && tecla < 58){ // numeros de 0 a 9
			return true;
	} else{
		if (tecla == 8 || tecla == 0 || tecla == 45 || tecla == 46) // backspace
			return true;
		else
			return false;
	}
}

//Formata o valor para Moeda com duas casas decimais depois da vírgula(centavos)
//Ex: onKeyDown="FormataMoeda('nomedocampo', 14, event)"
function FormataMoeda(campo,tammax,teclapres) {

	var tecla = teclapres.keyCode;
	vr = document.form[campo].value;
	vr = vr.replace( "/", "" );
	vr = vr.replace( "/", "" );
	vr = vr.replace( ",", "" );
	vr = vr.replace( ".", "" );
	vr = vr.replace( ".", "" );
	vr = vr.replace( ".", "" );
	vr = vr.replace( ".", "" );
	tam = vr.length;

	if (tam < tammax && tecla != 8){ tam = vr.length + 1 ; }

	if (tecla == 8 ){	tam = tam - 1 ; }

	if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){
		if ( tam <= 2 ){
			document.form[campo].value = vr ; }
			if ( (tam > 2) && (tam <= 5) ){
				document.form[campo].value = vr.substr( 0, tam - 2 ) + ',' + vr.substr( tam - 2, tam ) ; }
				if ( (tam >= 6) && (tam <= 8) ){
					document.form[campo].value = vr.substr( 0, tam - 5 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }
					if ( (tam >= 9) && (tam <= 11) ){
						document.form[campo].value = vr.substr( 0, tam - 8 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }
						if ( (tam >= 12) && (tam <= 14) ){
							document.form[campo].value = vr.substr( 0, tam - 11 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }
							if ( (tam >= 15) && (tam <= 17) ){
								document.form[campo].value = vr.substr( 0, tam - 14 ) + '.' + vr.substr( tam - 14, 3 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ;}
	}

	for (var ct = 0; ct < document.form.elements.length; ct++) {
		if (document.form.elements[ct].name == document.form.elements[campo].name) {
			if ( !teclapres.shiftKey && tecla == 9 && document.form.elements[ct+1] && document.form.elements[ct+1].name == "senhaConta" && document.applets['tclJava'] ){
				document.applets['tclJava'].setFocus();
			}
		}
	}
}