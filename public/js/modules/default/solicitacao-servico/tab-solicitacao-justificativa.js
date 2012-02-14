$(document).ready(function(){
	$('#btn_salvar_analise_solicitacao_justificativa').click(function(){
		salvaAnaliseSolicitacaoJustificativa();
	});

	$('#btn_cancelar_analise_solicitacao_justificativa').click(function(){
		var tab_origem = parseInt($('#tab_origem').val());
		$('#container-solicitacao-servico').triggerTab(tab_origem);
		$("#li-analise-solicitacao-justificativa").css("display", "none");
	});
});

function salvaAnaliseSolicitacaoJustificativa()
{
	if($("input[name=st_aceite_just_solicitacao]:checked").val() == undefined ){
		alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_ACEITE);
		return false;
	}

	if($("input[name=st_aceite_just_solicitacao]:checked").val() == 'N' &&
	   $("#tx_obs_aceite_just_solicitacao").val() == ''){
		alertMsg(i18n.L_VIEW_SCRIPT_INDIQUE_MOTIVO_ACEITE_NEGATIVO);
		return false;
	}

	$.ajax({
		type	: "POST",
		url		: systemName+"/solicitacao-servico/salva-analise-solicitacao-justificativa",
		data	: $('#form_analise_solicitacao_justificativa :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			var tab_origem = parseInt($('#tab_origem').val());
			$('#container-solicitacao-servico').triggerTab(tab_origem);
			$("#li-analise-solicitacao-justificativa").css("display", "none");
			executaAjax();
		}
	});
}