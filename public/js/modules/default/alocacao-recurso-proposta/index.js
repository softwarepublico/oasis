$(document).ready(function() {
	
    $('#bt_cancelar_alocacao_recurso_proposta').click(function (e) {
        window.location.href = systemName+"/controle";
    });
	
    $('#bt_salvar_alocacao_recurso_proposta').click(function () {
        if( !validaAlocacao() ){
            return false;
        }
		salvarAlocacaoRecursoProposta();
    });

	if ($('#st_parcela_orcamento').val() == '') {
		$('#formAlocacaoRecursoProposta :input').each(function(){
			if(this.name.substr(0, 15) == 'modulo_proposta'){
				this.disabled = true;
			}
		});
	}
});

function salvarAlocacaoRecursoProposta(){
	var postData = $('#formAlocacaoRecursoProposta :input').serialize();
	$.post(systemName+'/alocacao-recurso-proposta/salvar',
		postData,
		function(response) {
			alertMsg(response,'',"window.location.href = '"+systemName+"/controle'");
		}
	);
}

function verificaAlocacao() 
{
    var resultado        = true;
    var soma_horas       = 0;
    var soma_horas_mp    = 0;
    var saldo            = 0;
    var valor_alocado    = 0;
    var valor_alocado_mp = 0;

    for (i=0;i<document.forms[0].elements.length;i++) {
      
        //HORAS DE PROPOSTA
        if (document.forms[0].elements[i].name.substr(0,19) == 'cd_projeto_previsto') {
            if (parseFloat(document.forms[0].elements[i].value) > 0){
                valor_alocado    = parseFloat(document.forms[0].elements[i].value);
                saldo            = parseFloat(document.forms[0].elements[i-1].value);
                valor_alocado_mp = parseFloat(document.forms[0].elements[i+1].value);
                if (parseFloat(valor_alocado) > parseFloat(saldo)){
                    var arrValue = new Array(valor_alocado.toString(),saldo.toString());
                    alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_VALOR_ALOCADO_MAIOR_SALDO_PROJETO, arrValue));
//                    alertMsg("Valor Alocado ("+valor_alocado+") é Maior que o Saldo do Projeto ("+saldo+")!");
                    resultado = false;
                }
                if ((parseFloat(valor_alocado)+parseFloat(valor_alocado_mp)) > parseFloat(saldo)){
                    var arrValue1 = new Array(valor_alocado.toString(),valor_alocado_mp.toString(),saldo.toString());
                    alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_VALOR_ALOCADO_VALOR_MODULO_PROPOSTA_MAIOR_SALDO_PROJETO, arrValue1));
//                    alertMsg("Valor Alocado ("+valor_alocado+") + Valor do Módulo Proposta ("+valor_alocado_mp+") é Maior que o Saldo do Projeto ("+saldo+")!");
                    resultado = false;
                }
            }
            soma_horas = (document.forms[0].elements[i].value) ? soma_horas + parseFloat(document.forms[0].elements[i].value) : soma_horas;
        }

        //MODULO PROPOSTA
        if (document.forms[0].elements[i].name.substr(0,15) == 'modulo_proposta') {
            if (parseFloat(document.forms[0].elements[i].value) > 0){
                valor_alocado_mp = parseFloat(document.forms[0].elements[i].value);
                valor_alocado    = parseFloat(document.forms[0].elements[i-1].value);
                saldo            = parseFloat(document.forms[0].elements[i-2].value);
                if (parseFloat(valor_alocado_mp) > parseFloat(saldo)){

                    var arrValue = new Array(valor_alocado_mp.toString(), saldo.toString());

                    alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_VALOR_ALOCADO_MODULO_PROPOSTA_MAIOR_SALDO_PROJETO, arrValue));
//                    alertMsg("Valor Alocado para Módulo Proposta ("+valor_alocado_mp+") é Maior que o Saldo do Projeto ("+saldo+")!");
                    resultado = false;
                }
            }
            soma_horas_mp = (document.forms[0].elements[i].value) ? soma_horas_mp + parseFloat(document.forms[0].elements[i].value) : soma_horas_mp;
        }
    }
    document.forms[0].soma_horas.value               = soma_horas;
    document.forms[0].soma_horas_mp.value            = soma_horas_mp;
    return resultado;
}

function validaAlocacao()
{
    var resultado = verificaAlocacao();
    if (resultado == true){
        //PROPOSTA
		var total_a_ser_alocado = parseFloat(document.forms[0].soma_total_contrato.value) - parseFloat(document.forms[0].nu_horas_alocado_contrato_atual.value);
        total_a_ser_alocado     = total_a_ser_alocado.toFixed(1);

        var auxfloat = parseFloat(document.forms[0].soma_horas.value);
        auxfloat = auxfloat.toFixed(1);

		if (total_a_ser_alocado != auxfloat){
			var arrValue2 = new Array(document.forms[0].soma_horas.value.toString(), total_a_ser_alocado.toString());
				alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_VALOR_ALOCADO_PROPOSTA_DIFERENTE_VALOR_A_SER_ALOCADO, arrValue2));
//				alertMsg("Unidades de Métrica Alocadas para Proposta (%0%) é diferente de Unidades de Métrica a serem alocadas para a Proposta (%1%)");
				resultado = false;
		}
		
        //MÓDULO PROPOSTA
        if (Math.abs(parseFloat(document.forms[0].soma_horas_mp.value)) > Math.abs(parseFloat(document.forms[0].nu_horas_modulo_proposta.value))){

            var arrValue3 = new Array(document.forms[0].soma_horas_mp.value.toString(), document.forms[0].nu_horas_modulo_proposta.value.toString());
            if (parseFloat(document.forms[0].soma_horas_mp.value) > 0){

                alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_VALOR_ALOCADO_MODULO_PROPOSTA_MAIOR_HORAS_MODULO_PROPOSTA, arrValue3));
//                alertMsg("Número de Horas Alocado para Módulo Proposta ("+document.forms[0].soma_horas_mp.value+") é maior que Horas de Módulo Proposta ("+document.forms[0].nu_horas_modulo_proposta.value+")!");
                resultado = false;
            }
            else{
                alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_VALOR_ALOCADO_MODULO_PROPOSTA_MENOR_HORAS_MODULO_PROPOSTA, arrValue3));
//                alertMsg("Número de Horas Alocado para Módulo Proposta ("+document.forms[0].soma_horas_mp.value+") é menor que Horas de Módulo Proposta ("+document.forms[0].nu_horas_modulo_proposta.value+")!");
                resultado = false;
            }
        }
        if (parseFloat(document.forms[0].soma_horas_mp.value) != parseFloat(document.forms[0].nu_horas_modulo_proposta.value)){
            var arrValue4 = new Array(document.forms[0].soma_horas_mp.value.toString(), document.forms[0].nu_horas_modulo_proposta.value.toString());

            alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_VALOR_ALOCADO_MODULO_PROPOSTA_DIFERENTE_HORAS_MODULO_PROPOSTA, arrValue4));
//            alertMsg("Número de Horas Alocado para Módulo Proposta ("+document.forms[0].soma_horas_mp.value+") é diferente de Horas de Módulo Proposta ("+document.forms[0].nu_horas_modulo_proposta.value+")!");
            resultado = false;
        }
		 
        return resultado;
    }
}

function validaAlocacao_old()
{
    var resultado = verificaAlocacao();
    if (resultado == true){
        //PROPOSTA
        if (Math.abs(parseFloat(document.forms[0].soma_horas.value)) < Math.abs(parseFloat(document.forms[0].nu_horas_proposta.value))){
            var sobra = parseFloat(document.forms[0].nu_horas_proposta.value) - parseFloat(document.forms[0].soma_horas.value);

			sobra        = sobra.toFixed(1);
			var arrValue = new Array(sobra.toString());

            if (!confirm(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_FALTAM_HORAS_COMPLETAR_PROPOSTA, arrValue))) {
//            if (!confirm('Faltam '+sobra+' horas para completar proposta! Deseja prosseguir?')) {
				return false;
			}
        }

        if (Math.abs(parseFloat(document.forms[0].soma_horas.value)) > Math.abs(parseFloat(document.forms[0].nu_horas_proposta.value))){

            var arrValue1 = new Array(document.forms[0].soma_horas.value.toString(), document.forms[0].nu_horas_proposta.value.toString());

            if (parseFloat(document.forms[0].soma_horas.value) > 0){
                alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_HORA_ALOCADA_MAIOR_HORA_PROPOSTA, arrValue1));
//                alertMsg("Número de Horas Alocado ("+document.forms[0].soma_horas.value+") é maior que Horas da Proposta ("+document.forms[0].nu_horas_proposta.value+")!");
                resultado = false;
            }
            else{
                alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_HORA_ALOCADA_MENOR_HORA_PROPOSTA, arrValue1));
//                alertMsg("Número de Horas Alocado ("+document.forms[0].soma_horas.value+") é menor que Horas da Proposta ("+document.forms[0].nu_horas_proposta.value+")!");
                resultado = false;
            }
        }
        if (parseFloat(document.forms[0].soma_horas.value) > parseFloat(document.forms[0].soma_total_contrato.value)){

            var arrValue2 = new Array(document.forms[0].soma_horas.value.toString(), document.forms[0].soma_total_contrato.value.toString());
            alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_HORAS_ALOCADAS_MAIOR_HORAS_ALOCADAS_DENTRO_CONTRATO, arrValue2));

//            alertMsg("Número de Horas Alocado ("+document.forms[0].soma_horas.value+") é maior que Horas a Serem Alocadas Dentro do Contrato ("+document.forms[0].soma_total_contrato.value+")!");
            resultado = false;
        }
        //MÓDULO PROPOSTA
        if (Math.abs(parseFloat(document.forms[0].soma_horas_mp.value)) > Math.abs(parseFloat(document.forms[0].nu_horas_modulo_proposta.value))){

            var arrValue3 = new Array(document.forms[0].soma_horas_mp.value.toString(), document.forms[0].nu_horas_modulo_proposta.value.toString());
            if (parseFloat(document.forms[0].soma_horas_mp.value) > 0){

                alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_VALOR_ALOCADO_MODULO_PROPOSTA_MAIOR_HORAS_MODULO_PROPOSTA, arrValue3));
//                alertMsg("Número de Horas Alocado para Módulo Proposta ("+document.forms[0].soma_horas_mp.value+") é maior que Horas de Módulo Proposta ("+document.forms[0].nu_horas_modulo_proposta.value+")!");
                resultado = false;
            }
            else{
                alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_VALOR_ALOCADO_MODULO_PROPOSTA_MENOR_HORAS_MODULO_PROPOSTA, arrValue3));
//                alertMsg("Número de Horas Alocado para Módulo Proposta ("+document.forms[0].soma_horas_mp.value+") é menor que Horas de Módulo Proposta ("+document.forms[0].nu_horas_modulo_proposta.value+")!");
                resultado = false;
            }
        }
        if (parseFloat(document.forms[0].soma_horas_mp.value) != parseFloat(document.forms[0].nu_horas_modulo_proposta.value)){
            var arrValue4 = new Array(document.forms[0].soma_horas_mp.value.toString(), document.forms[0].nu_horas_modulo_proposta.value.toString());

            alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_VALOR_ALOCADO_MODULO_PROPOSTA_DIFERENTE_HORAS_MODULO_PROPOSTA, arrValue4));
//            alertMsg("Número de Horas Alocado para Módulo Proposta ("+document.forms[0].soma_horas_mp.value+") é diferente de Horas de Módulo Proposta ("+document.forms[0].nu_horas_modulo_proposta.value+")!");
            resultado = false;
        }

        return resultado;
    }
}

function SomaHoras() {
    var horas     = 0;
    var horas2    = 0;
    var total     = 0;
    var total2    = 0;
    var campo     = 'cd_projeto_previsto';
    var campo2    = 'modulo_proposta';
    var tam_campo = campo.length;
    var tam_campo2= campo2.length;
    for (i=0;i<document.forms[0].elements.length;i++) {
        if (document.forms[0].elements[i].name.substr(0,tam_campo) == campo) {
            horas = document.forms[0].elements[i].value;
            if (horas == ''){
                horas = 0;
            }
            total += parseFloat(horas);
        }
        if (document.forms[0].elements[i].name.substr(0,tam_campo2) == campo2) {
            horas2 = document.forms[0].elements[i].value;
            if (horas2 == ''){
                horas2 = 0;
            }
            total2 += parseFloat(horas2);
        }
    }
    document.forms[0].total.value = total;
    document.forms[0].total_modulo_proposta.value = total2;
}

function abreModalAlteracaoPropostaAlocacao()
{
	var jsonData = {'cd_projeto':$('#cd_projeto_alocacao_recurso_proposta').val(),
                    'cd_proposta':$('#cd_proposta_alocacao_recurso_proposta').val()};

//	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_alteracao_proposta');}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarAlteracaoProposta();}+'};');
    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_alteracao_proposta');}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){atualizaProcessamentoProposta();}+'};');
    loadDialog({
        id       : 'dialog_alteracao_proposta',		//id do pop-up
        title    : 'Altera&ccedil;&atilde;o de Proposta',// titulo do pop-up
        url      : systemName + '/alteracao-proposta',	 // url onde encontra-se o phtml
        data     : jsonData,							 // parametros para serem transferidos para o pop-up
        height   : 250,									 // altura do pop-up
        buttons  : buttons
    });
}

function salvarAlteracaoProposta() {

	if($("#tx_motivo_alteracao_proposta").val() == ''){
		alertMsg(i18n.L_VIEW_SCRIPT_DESCREVA_MOTIVO_ALTERACAO_PROPOSTA);
		return false;
	}

	$.ajax({
		type	: 'POST',
		url		: systemName+'/alteracao-proposta/salvar',
		data	: $('#formAlteracaoProposta :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno,1,function(){
                closeDialog('dialog_alteracao_proposta');
                window.location.href = systemName+'/controle';
            });
			
		}
	});
}

function atualizaProcessamentoProposta(){
    $.ajax({
		type	: 'POST',
		url		: systemName+'/alocacao-recurso-proposta/atualiza-processamento-proposta',
		data	: $('#formAlocacaoRecursoProposta :input').serialize(),
		success	: function(retorno){
			salvarAlteracaoProposta();
		}
	});
}