function dateMask(obj){
	var data = obj.value;
    if (data.length == 2){
    	data = data + '/';
        obj.value = data;
      	return true;              
    }
    
    if (data.length == 5){
        data = data + '/';
        obj.value = data;
        return true;
    }
}

function dateValidade(obj) {
	if (obj.val() != "") {
		dia = (obj.val().substring(0,2));
        mes = (obj.val().substring(3,5));
        ano = (obj.val().substring(6,10));
        situacao = "";
        // verifica se e ano bissexto
        if (mes == 2 && ( dia < 1 || dia > 29 || ( dia > 28 && (parseInt(ano / 4) != ano / 4)))) {
            situacao = "falsa";
        // verifica o dia valido para cada mes
        } else if ((dia < 1)||(dia < 1 || dia > 30) && (  mes == 4 || mes == 6 || mes == 9 || mes == 11 ) || dia > 31) {
            situacao = "falsa";
        }
        // verifica se o mes e valido
        if (mes < 1 || mes > 12 ) {
            situacao = "falsa";
        }
        if ((situacao == "falsa") || (obj.val().length <10)) {
            alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_DATA_INVALIDA, new Array(obj.val())));
            obj.val("");
            return false;
        }else{
            return true;
        }
	}else{
	   return true;
	}
}

/******************************Validacao da data e da hora******************************/
function dateTimeValidade(dateTime){
	dateValidade(dateTime);
	valida_hora(dateTime);
}

function valida_hora(time){
    var situacao = "";
     
	if(time.val() != ""){
		hrs = (time.val().substring(11,13)); 
		min  = (time.val().substring(14,16));
		seg  = (time.val().substring(17,19));
	 
	    // valida hora 
		if((hrs < 00 ) || (hrs > 23))
		    situacao = "falsa"; 
			
	    // valida Minuto
	    if (( min < 00) ||( min > 59)) 
		    situacao = "falsa"; 
		    
	    // valida Segundo
	    if (( seg < 00) ||( seg > 59)) 
		    situacao = "falsa"; 
  } 
  if (situacao == "falsa") { 
	  alertMsg(i18n.L_VIEW_SCRIPT_HORA_INVALIDA);
	  time.focus(); 
  }
}

function timeValidate(time){
    var situacao = "";

	if(time.val() != ""){
		hrs  = (time.val().substr(0,2));
		min  = (time.val().substr(3,2));
		seg  = (time.val().substr(6,2));

	    // valida hora
		if((hrs < 00 ) || (hrs > 23))
		    situacao = "falsa";

	    // valida Minuto
	    if (( min < 00) ||( min > 59))
		    situacao = "falsa";

	    // valida Segundo
	    if (( seg < 00) ||( seg > 59))
		    situacao = "falsa";
  }
  if (situacao == "falsa") {
	  alertMsg(i18n.L_VIEW_SCRIPT_HORA_INVALIDA);
	  time.focus();
  }
}

function comparaDataHoraInicioFim(dt_inicio, dt_fim, msgData, msgHora){						
	var dataHoraInicio = eval("$('#"+dt_inicio+"').val()");
	var dataHoraFim    = eval("$('#"+dt_fim+"').val()");

	if(dataHoraInicio != '' && dataHoraFim != ''){
		var dataInicio     = dataHoraInicio.split( " " )[0].toString();
		var horaInicio     = dataHoraInicio.split( " " )[1].toString();
		var dataFim        = dataHoraFim.split( " " )[0].toString();
		var horaFim        = dataHoraFim.split( " " )[1].toString();
		var dataInicioInt  = parseInt( dataInicio.split( "/" )[2].toString()+dataInicio.split( "/" )[1].toString()+dataInicio.split( "/" )[0].toString()); 
		var dataFimInt     = parseInt( dataFim.split( "/" )[2].toString()+dataFim.split( "/" )[1].toString()+dataFim.split( "/" )[0].toString());
		
		var horaInicial    = horaInicio.split( ":" )[0];
		var minutoInicio   = horaInicio.split( ":" )[1];
		var segundoInicio  = horaInicio.split( ":" )[2];
		var horaMinuto     = parseInt(((horaInicial*60)*60));
		var minutoSegundo  = parseInt(minutoInicio*60);
		var segundoI       = parseInt(segundoInicio);
        var segundoI       = horaMinuto + minutoSegundo + segundoI;

		var horaFinal      = horaFim.split( ":" )[0];
		var minutoFim      = horaFim.split( ":" )[1];
		var segundoFim     = horaFim.split( ":" )[2];
		var horaMinuto     = parseInt(((horaFinal*60)*60));
		var minutoSegundo  = parseInt(minutoFim*60);
		var segundoF       = parseInt(segundoFim);
		var segundoF       = horaMinuto + minutoSegundo + segundoF;

		if(dataFimInt < dataInicioInt){
			showToolTip(msgData,eval("$('#"+dt_fim+"')"));
			var t = setTimeout('removeTollTip()',10000);
			return false;
		}
		if((dataFimInt <= dataInicioInt) && (segundoF < segundoI)){
			showToolTip(msgHora,eval("$('#"+dt_fim+"')"));
			var t = setTimeout('removeTollTip()',10000);
			return false;
		}
	}
	return true;
}

function comparaHoraInicioFim(hora_inicio, hora_fim, msgHora){
	var horaInicio     = eval("$('#"+hora_inicio+"').val()");
	var horaFim        = eval("$('#"+hora_fim+"').val()");

	if(horaInicio != '' && horaFim != ''){
		var horaInicial    = horaInicio.split( ":" )[0];
		var minutoInicio   = horaInicio.split( ":" )[1];
		var segundoInicio  = horaInicio.split( ":" )[2];
		var horaMinuto     = parseInt(((horaInicial*60)*60));
		var minutoSegundo  = parseInt(minutoInicio*60);
		var segundoI       = parseInt(segundoInicio);
		var segundoI       = horaMinuto + minutoSegundo + segundoI;

		var horaFinal      = horaFim.split( ":" )[0];
		var minutoFim      = horaFim.split( ":" )[1];
		var segundoFim     = horaFim.split( ":" )[2];
		var horaMinuto     = parseInt(((horaFinal*60)*60));
		var minutoSegundo  = parseInt(minutoFim*60);
		var segundoF       = parseInt(segundoFim);
		var segundoF       = horaMinuto + minutoSegundo + segundoF;

		if(segundoF < segundoI){
			showToolTip(msgHora,eval("$('#"+hora_fim+"')"));
			var t = setTimeout('removeTollTip()',10000);
			return false;
		}
	}
	return true;
}

/**
 * Função para validação de período
 *
 * @param obj jQuery DataInicial
 * @param obj jQuery DataFinal
 * @return boolean
 */
function validaPeriodo(objDataInicial, objDataFinal){

	var dtInicio   = objDataInicial.val();
    var dtFim      = objDataFinal.val();

    var objDtAtual = new Date();

    var dtAtual  = '';
        dtAtual += ((objDtAtual.getDate() ) < 10)?'0'+( objDtAtual.getDate() ):(objDtAtual.getDate() );
        dtAtual += '/'
        dtAtual += ((objDtAtual.getMonth() + 1) < 10)?'0'+( objDtAtual.getMonth() + 1):(objDtAtual.getMonth() + 1);
        dtAtual += '/'
        dtAtual += objDtAtual.getFullYear();

	if( verificaData(dtInicio,dtAtual,'>') ){
		showToolTip(i18n.L_VIEW_SCRIPT_DT_INICIAL_MAIOR_DT_ATUAL, objDataInicial);
		return false;
	}

	if( verificaData(dtFim,dtInicio,'<') ){
		showToolTip(i18n.L_VIEW_SCRIPT_DT_FINAL_MENOR_DT_INICIAL, objDataFinal);
		return false;
	}

	if( verificaData(dtFim,dtAtual,'>') ){
		showToolTip(i18n.L_VIEW_SCRIPT_DT_FINAL_MAIOR_DT_ATUAL, objDataFinal);
		return false;
	}
	return true;

}

/**
 * Função verifica se data_1  {operador}  data_2
 * {operador} = '>','<','=','!='
 * se sim: return true
 * se não: return false
 */
function verificaData( data_1 , data_2 , operador ){
    var dia_data_1 = substr( data_1, 1, 2 );
    var mes_data_1 = substr( data_1, 4, 2 );
    var ano_data_1 = substr( data_1, 7, 4 );
    var data_formato_EUA_1 = mes_data_1+'/'+dia_data_1+'/'+ano_data_1;
    var data_formatada_1   = new Date( data_formato_EUA_1 );

    var dia_data_2 = substr( data_2, 1, 2 );
    var mes_data_2 = substr( data_2, 4, 2 );
    var ano_data_2 = substr( data_2, 7, 4 );
    var data_formato_EUA_2 = mes_data_2+'/'+dia_data_2+'/'+ano_data_2;
    var data_formatada_2   = new Date( data_formato_EUA_2 );

    var retorno = false;
    switch( operador ){
        case '>':
            retorno = (data_formatada_1 > data_formatada_2)? true : false;
            break;
        case '<':
            retorno = (data_formatada_1 < data_formatada_2)? true : false;
            break;
        case '=':
            retorno = (data_formatada_1 == data_formatada_2)? true : false;
            break;
        case '!=':
            retorno = (data_formatada_1 != data_formatada_2)? true : false;
            break;
    }
    return retorno;
}

function substr(character, number, length) {
    number = number-1;
    return character.substring(number,number+length);
}