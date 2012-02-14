var _interval;
$(document).ready(function(){
    executaAjax();

	$("#mes").change(function() {
		if (parseInt($(this).val()) != 0) {executaAjax();}
	});

	$("#ano").change(function() {
		if(parseInt($(this).val()) != 0) {executaAjax();}
	});

	$("#cd_objeto").change(executaAjax);
	$("#st_solicitacao").change(executaAjax);
	
	// pega evento no onclick do botao
	$("#nova_solicitacao").click(function(){
		window.location.href = systemName+"/solicitacao-service-desk";
	});
    _interval = window.setInterval( executaAjax, 60000); //1 minuto
});

function executaAjax()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/solicitacao-servico-service-desk/pesquisa-solicitacao-servico-service-desk",
		data	: "mes="+$("#mes").val()+
				  "&ano="+$("#ano").val()+
				  "&cd_objeto="+$("#cd_objeto").val()+
				  "&st_solicitacao="+$("#st_solicitacao").val(),
		success	: function(retorno){
			// atualiza a grid
			$("#grid").html(retorno);
		}
	});
}

function abreTabAnalisarJustificativa(cd_objeto,ni_solicitacao,ni_ano_solicitacao,tab_origem)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/solicitacao-servico-service-desk/tab-analise-solicitacao-service-desk-justificativa",
		data	: {"cd_objeto"          :cd_objeto,
				   "ni_solicitacao"     :ni_solicitacao,
				   "ni_ano_solicitacao" :ni_ano_solicitacao,
				   "tab_origem"         :tab_origem},
		success: function(retorno){
			$("#div-analise-solicitacao-service-desk-justificativa").html(retorno);
			$('#container-solicitacao-servico-service-desk').triggerTab(6);
			$("#li-analise-solicitacao-service-desk-justificativa" ).css("display", "");
		}
	});
}

function abreModalFechamentoSolicitacaoServiceDesk2(ni_solicitacao, ni_ano_solicitacao, cd_objeto, tx_objeto) 
{
	var jsonData = {'cd_objeto':cd_objeto,
					'tx_objeto': tx_objeto,
					'ni_solicitacao': ni_solicitacao,
					'ni_ano_solicitacao': ni_ano_solicitacao
				   };
	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_fechamento_solicitacao');}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarFechamentoSolicitacao();}+'};');
    loadDialog({
        id       : 'dialog_fechamento_solicitacao_service_desk',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_FECHAR_SOLICITACAO_SERVICO,// titulo do pop-up
        url      : systemName + '/fechamento-solicitacao-service-desk',	// url onde encontra-se o phtml
        data     : jsonData,							// parametros para serem transferidos para o pop-up
        height   : 300,									// altura do pop-up
        buttons  : buttons
    });
}

function salvarFechamentoSolicitacaoServiceDesk()
{
	if($("input[name=st_grau_satisfacao]:checked").val() == undefined ){
		alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_TIPO_AVALIACAO);
		return false;
	}

	$.ajax({
		type	: "POST",
		url		: systemName+"/fechamento-solicitacao-service-desk/salvar",
		data	: $('#formFechamentoSolicitacaoServiceDesk :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			executaAjax();
			closeDialog('dialog_fechamento_solicitacao_service_desk');
		}
	});
}

