$(document).ready(function(){

	$("#bt_cancelar_desalocacao_recurso_proposta").click(function() {
		$('#container-proposta').triggerTab(6);
		$("#li-desalocacao").css("display", "none");
	});
	
	$("#bt_salvar_desalocacao_recurso_proposta").click(function() {
		
		if( !validaDesalocacao() ){ return false; }
		
		var postData = $('#formDesalocacaoRecursoProposta :input').serialize();

		$.ajax({
			type	: "POST",
			url		: systemName+"/desalocacao-recurso-proposta/salvar",
			data	: postData,
			success	: function(retorno){
				alertMsg(retorno,2,"fechaTabDesalocacao()");
			}
		});
	});
});


function verificaDesalocacao()
{
    var resultado        = true;
	var soma_horas       = 0;
	var saldo            = 0;
	var valor_alocado    = 0;
	
	$('#tableDesalocacaoRecursoPropostaProjetosPrevistos :input[id^=cd_projeto_previsto_]').each(function(){
		if (Math.abs(parseFloat($(this).val())) > 0){
			id = $(this).attr('id');
			codigo = id.replace(/cd_projeto_previsto_/, "");

			valor_alocado = $(this).val();
			saldo         = $('#saldo_'+codigo).val();

			if (parseFloat(valor_alocado) > parseFloat(saldo)){
				alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_VALOR_ALOCADO_MAIOR_SALDO_PROJETO, new Array(valor_alocado,saldo)));
	//			  	alertMsg("Valor Alocado ("+valor_alocado+") é Maior que o Saldo do Projeto ("+saldo+")!");
				resultado = false;
			}
			soma_horas += parseFloat(valor_alocado);
		}
	});
    $('#soma_horas').val(soma_horas);
	return resultado;
}

function validaDesalocacao()
{
     var resultado = verificaDesalocacao();
	 if (resultado == true){
		 if (Math.abs(parseFloat($('#soma_horas').val())) > Math.abs(parseFloat($('#valor_a_ser_alocado').val()))){
			 alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_HORA_ALOCADA_MAIOR_HORA_PROPOSTA, new Array($('#soma_horas').val(), $('#valor_a_ser_alocado').val())));
//				 alertMsg("Número de Horas Alocado ("+$('#soma_horas').val()+") é maior que Horas da Proposta ("+$('#valor_a_ser_alocado').val()+")!");
			 resultado = false;
		 }
		 if (Math.abs(parseFloat($('#soma_horas').val())) < Math.abs(parseFloat($('#valor_a_ser_alocado').val()))){
			 alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_HORA_ALOCADA_MENOR_HORA_PROPOSTA, new Array($('#soma_horas').val(), $('#valor_a_ser_alocado').val())));
//				 alertMsg("Número de Horas Alocado ("+$('#soma_horas').val()+") é menor que Horas da Proposta ("+$('#valor_a_ser_alocado').val()+")!");
			 resultado = false;
		 }
		 
		return resultado;
	 }	
}

function SomaHoras() {
	var horas     = 0;
	var total     = 0;

	$('#tableDesalocacaoRecursoPropostaProjetosPrevistos :input[id^=cd_projeto_previsto_]').each(function(){
		if ($(this).val() > 0){
			horas = $(this).val();
			
			if (horas == ''){
				horas = 0;
			}
			total += parseFloat(horas);
		}
	});
	$('#total').val(total);
}

function fechaTabDesalocacao()
{
	$('#container-proposta').triggerTab(6);
	$("#li-desalocacao").css("display", "none");
	gridDesalocacaoRecursoPropostaAjax();
}
