$(document).ready(function(){

	$("#cd_contrato_atual").change(function() {
		if ($("#cd_contrato_atual").val() != 0){
			gridAlocacaoRecursoPropostaContratoAnteriorAjax();
		} else {
			$("#gridAlocacaoRecursoPropostaContratoAnterior").html("");
		}
	});
	
});


function gridAlocacaoRecursoPropostaContratoAnteriorAjax() {
	$.ajax({
		type: "POST",
		url: systemName+"/alocacao-recurso-proposta-contrato-anterior/grid-alocacao-recurso-proposta-contrato-anterior",
		data: "cd_contrato="+$("#cd_contrato_atual").val(),
		success: function(retorno){
			// atualiza a grid
			$("#gridAlocacaoRecursoPropostaContratoAnterior").html(retorno);
		}
	});
}

function abreAlocacaoRecursoPropostaContratoAnterior(cd_projeto, cd_proposta, tx_sigla_projeto) {
	$.ajax({
		type: "POST",
		url: systemName+"/alocacao-recurso-proposta-contrato-anterior/tab-alocacao",
		data:  "cd_projeto="+cd_projeto
		      +"&cd_proposta="+cd_proposta
		      +"&tx_sigla_projeto="+tx_sigla_projeto,
		success: function(retorno){
			$("#alocacao").html(retorno);
			$('#container-proposta').triggerTab(5);
			$("#li-alocacao").css("display", "");
		}
	});
}



