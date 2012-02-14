$(document).ready(function(){
    executaAjax();

	$("#mes").change(function() {
		if ($("#mes").val() != "0") {
			executaAjax();
		}
	});

	$("#ano").change(function() {
		if ($("#ano").val() != "0") {
			executaAjax();
		}
	});

	$("#cd_objeto").change(function() {
		executaAjax();
	});

	$("#st_solicitacao").change(function() {
		executaAjax();
	});
	
	// pega evento no onclick do botao
	$("#nova_solicitacao").click(function(){
		window.location.href = systemName+"/solicitacao";
	});
});

function executaAjax()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/solicitacao-servico/pesquisa-solicitacao-servico",
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
		url		: systemName+"/solicitacao-servico/tab-analise-solicitacao-justificativa",
		data	: "cd_objeto="+cd_objeto
				 +"&ni_solicitacao="+ni_solicitacao
				 +"&ni_ano_solicitacao="+ni_ano_solicitacao
				 +"&tab_origem="+tab_origem,
		success: function(retorno){
			$("#div-analise-solicitacao-justificativa").html(retorno);
			$('#container-solicitacao-servico').triggerTab(6);
			$("#li-analise-solicitacao-justificativa" ).css("display", "");
		}
	});
}
function abreModalFechamentoSolicitacao2(ni_solicitacao, ni_ano_solicitacao, cd_objeto, tx_objeto) 
{
	var jsonData = {'cd_objeto':cd_objeto,
					'tx_objeto': tx_objeto,
					'ni_solicitacao': ni_solicitacao,
					'ni_ano_solicitacao': ni_ano_solicitacao
				   };
	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_fechamento_solicitacao');}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarFechamentoSolicitacao();}+'};');
    loadDialog({
        id       : 'dialog_fechamento_solicitacao',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_FECHAR_SOLICITACAO_SERVICO,// titulo do pop-up
        url      : systemName + '/fechamento-solicitacao',	// url onde encontra-se o phtml
        data     : jsonData,							// parametros para serem transferidos para o pop-up
        height   : 300,									// altura do pop-up
        buttons  : buttons
    });
}

function salvarFechamentoSolicitacao()
{
	if($("input[name=st_grau_satisfacao]:checked").val() == undefined ){
		alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_TIPO_AVALIACAO);
		return false;
	}

	$.ajax({
		type	: "POST",
		url		: systemName+"/fechamento-solicitacao/salvar",
		data	: $('#formFechamentoSolicitacao :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			executaAjax();
			closeDialog('dialog_fechamento_solicitacao');
		}
	});
}

