$(document).ready(function(){

	$("#cd_contrato_controle_alteracao_proposta").change(function() {
		if ($("#cd_contrato_controle_alteracao_proposta").val() != "0") {
			montaComboProjetoAjax();
			$('#gridControleAlteracaoProposta').html('');
		}
	});
	
	$("#cd_projeto").change(function() {
		if ($("#cd_projeto").val() != "0") {
			executaAlteracaoPropostaAjax();
		} else {
			$('#gridControleAlteracaoProposta').html('');
		}
	});
});

function montaComboProjetoAjax() {
	$.ajax({
		type: "POST",
		url: systemName+"/associar-projeto-contrato/pesquisa-projeto-contrato",
		data: "cd_contrato="+$("#cd_contrato_controle_alteracao_proposta").val(),
		success: function(retorno){
			// atualiza a grid
			$("#cd_projeto").html(retorno);
		}
	});
}

function executaAlteracaoPropostaAjax() {
	$.ajax({
		type: "POST",
		url: systemName+"/controle-alteracao-proposta/pesquisa-propostas-alteracao",
		data: "cd_projeto="+$("#cd_projeto").val(),
		success: function(retorno){
			// atualiza a grid
			$("#gridControleAlteracaoProposta").html(retorno);
		}
	});
}

function abreModalAlteracaoProposta(cd_projeto, cd_proposta, tx_sigla_projeto)
{
    var jsonData = {'cd_projeto':cd_projeto,
                    'cd_proposta':cd_proposta};

	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_alteracao_proposta');}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarAlteracaoProposta();}+'};');
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
			alertMsg(retorno);
			executaAlteracaoPropostaAjax();
			closeDialog('dialog_alteracao_proposta');
		}
	});
}