$(document).ready(function(){
	$("#cd_contrato_desalocacao").change(function() {
		if ($("#cd_contrato_desalocacao").val() != 0){
			gridDesalocacaoRecursoPropostaAjax();
		} else {
			$("#gridDesalocacaoRecursoProposta").html('');
		}
	});
});

function gridDesalocacaoRecursoPropostaAjax() {
	$.ajax({
		type	: "POST",
		url		: systemName+"/desalocacao-recurso-proposta/grid-desalocacao-recurso-proposta",
		data	: "cd_contrato="+$("#cd_contrato_desalocacao").val(),
		success	: function(retorno){
			// atualiza a grid
			$("#gridDesalocacaoRecursoProposta").html(retorno);
		}
	});
}

function abreDesalocacaoRecursoProposta(cd_projeto, cd_proposta, tx_sigla_projeto) {
	$.ajax({
		type	: "POST",
		url		: systemName+"/desalocacao-recurso-proposta/tab-desalocacao",
		data	:  "cd_projeto="+cd_projeto
			      +"&cd_proposta="+cd_proposta
			      +"&tx_sigla_projeto="+tx_sigla_projeto,
		success	: function(retorno){
			$("#desalocacao"		).html(retorno);
			$('#container-proposta'	).triggerTab(7);
			$("#li-desalocacao"		).css("display", "");
		}
	});
}