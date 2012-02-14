$(document).ready(function(){
	$('#btn_salvar_analise_solicitacao_service_desk_justificativa').click(function(){
		salvaAnaliseSolicitacaoServiceDeskJustificativa();
	});

	$('#btn_cancelar_analise_solicitacao_service_desk_justificativa').click(function(){
		var tab_origem = parseInt($('#tab_origem').val());
		$('#container-solicitacao-servico-service-desk').triggerTab(tab_origem);
		$("#li-analise-solicitacao-service-desk-justificativa").css("display", "none");
	});
});

function salvaAnaliseSolicitacaoServiceDeskJustificativa()
{
	if($("input[name=st_aceite_just_solicitacao_service_desk]:checked").val() == undefined ){
		alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_ACEITE);
		return false;
	}

	if($("input[name=st_aceite_just_solicitacao_service_desk]:checked").val() == 'N' &&
	   $("#tx_obs_aceite_just_solicitacao").val() == ''){
		alertMsg(i18n.L_VIEW_SCRIPT_INDIQUE_MOTIVO_ACEITE_NEGATIVO);
		return false;
	}

	$.ajax({
		type	: "POST",
		url		: systemName+"/solicitacao-servico-service-desk/salva-analise-solicitacao-service-desk-justificativa",
		data	: $('#form_analise_solicitacao_service_desk_justificativa :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			var tab_origem = parseInt($('#tab_origem').val());
			$('#container-solicitacao-servico-service-desk').triggerTab(tab_origem);
			$("#li-analise-solicitacao-service-desk-justificativa").css("display", "none");
			executaAjax();
		}
	});
}