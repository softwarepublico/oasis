$(document).ready(function(){
    executaSolicitacaoAjax();
	apresentaData($("#mes").val(),$("#ano").val(),"mesAnoProjetoSolicitacao");
	$("#mes").change(function() {
		if ($("#mes").val() != "0") {
			executaSolicitacaoAjax();
			apresentaData($("#mes").val(),$("#ano").val(),"mesAnoProjetoSolicitacao");
		} else {
			apresentaData($("#mes").val(),$("#ano").val(),"mesAnoProjetoSolicitacao");
		}
	});
	
	$("#ano").change(function() {
		if ($("#ano").val() != "0") {
			executaSolicitacaoAjax();
			apresentaData($("#mes").val(),$("#ano").val(),"mesAnoProjetoSolicitacao");
		} else {
			apresentaData($("#mes").val(),$("#ano").val(),"mesAnoProjetoSolicitacao");
		}
	});
	
	$("#bt_consultar").click(function (){
		ajaxGridSolicitacaoServicoConsulta();
	});		
});

function salvarJustificativaSolicitacao()
{
	$.ajax({
		type	: "POST",
		url		: systemName+'/justificativa-solicitacao/salvar',
		data	: $('#formJustificativaSolicitacao :input').serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['error'] == true){
				alertMsg(retorno['msg'], retorno['errorType']);
			}else{
				alertMsg(retorno['msg']);
				executaSolicitacaoAjax();
				closeDialog('dialog_justificativa_solicitacao');
			}
		}
	});
}

function executaSolicitacaoAjax()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/gerenciar-projetos-solicitacao/pesquisa-solicitacoes",
		data	: "mes="+$("#mes").val()+"&ano="+$("#ano").val(),
		success	: function(retorno){
			// atualiza a grid
			$("#gridSolicitacao").html(retorno);
		}
	});
}

function abreModal(cd_objeto, ni_solicitacao, ni_ano_solicitacao)
{
	var jsonData = {'cd_objeto':cd_objeto,
					'ni_solicitacao': ni_solicitacao,
					'ni_ano_solicitacao': ni_ano_solicitacao
				   };
	    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_criar_proposta');}+', "'+i18n.L_VIEW_SCRIPT_BTN_CRIAR_PROPOSTA+'": '+function(){criarPropostaModal();}+'};');

    loadDialog({
        id       : 'dialog_criar_proposta',	//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_CRIAR_PROPOSTA,//titulo do pop-up
        url      : systemName + '/proposta',//url onde encontra-se o phtml
        data     : jsonData,				// parametros para serem transferidos para o pop-up
        height   : 350,						// altura do pop-up
        width    : 600,						// largura do pop-up
        buttons  : buttons
    });
}

function abreModalJustificar(cd_objeto, ni_solicitacao, ni_ano_solicitacao)
{
	var jsonData = {'cd_objeto_justificativa_solicitacao':cd_objeto,
					'ni_solicitacao_justificativa_solicitacao': ni_solicitacao,
					'ni_ano_solicitacao_justificativa_solicitacao': ni_ano_solicitacao
				   };

    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_justificativa_solicitacao');}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarJustificativaSolicitacao();}+'};');
    loadDialog({
        id       : 'dialog_justificativa_solicitacao',			//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_JUSTIFICATIVA_SOLICITACAO_SERVICO,//titulo do pop-up
        url      : systemName + '/justificativa-solicitacao',	//url onde encontra-se o phtml
        data     : jsonData,									// parametros para serem transferidos para o pop-up
        height   : 300,											// altura do pop-up
        buttons  : buttons
    });
}

function abreModalVerJustificativa(cd_objeto, ni_solicitacao, ni_ano_solicitacao) {

	var jsonData = {'cd_objeto_justificativa_solicitacao':cd_objeto,
					'ni_solicitacao_justificativa_solicitacao': ni_solicitacao,
					'ni_ano_solicitacao_justificativa_solicitacao': ni_ano_solicitacao
				   };

    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_ver_justificativa_solicitacao');}+'};');
    loadDialog({
        id       : 'dialog_ver_justificativa_solicitacao',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_JUSTIFICATIVA_SOLICITACAO_SERVICO,	//titulo do pop-up
        url      : systemName + '/justificativa-solicitacao/ver-justificativa-solicitacao',	//url onde encontra-se o phtml
        data     : jsonData,									// parametros para serem transferidos para o pop-up
        height   : 300,											// altura do pop-up
        buttons  : buttons
    });
}

function ajaxGridSolicitacaoServicoConsulta() 
{
	/*if ($("#solicitacao").val().indexOf("/") == -1)
	{
		alertMsg("A formatação do campo Número está incorreta. O formato deve ser MM/AAAA.");
		return false;
	}*/
	if ($("#cd_unidade").val() == "0" && $("#tx_solicitante").val() == "" && $("#solicitacao").val() == "" && $("#dt_inicio").val() == "" && $("#dt_fim").val() == "" && $("#cd_profissional").val() == "-1" && $("#tx_solicitacao").val() == ""){
		alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PARAMETRO_PESQUISA);
		return false;
	}
	if (($("#dt_inicio").val() != "" && $("#dt_fim").val() == "") || ($("#dt_inicio").val() == "" && $("#dt_fim").val() != "")){
		alertMsg(i18n.L_VIEW_SCRIPT_INDIQUE_PERIODO_PESQUISA);
		return false;
	}
	
	$.ajax({
		type: "POST",
		url : systemName+"/gerenciar-projetos-solicitacao/grid-solicitacao-servico-consulta",
		data: "cd_unidade="+$("#cd_unidade").val()+
			  "&solicitacao="+$("#solicitacao").val()+
			  "&tx_solicitante="+$("#tx_solicitante").val()+
			  "&dt_inicio="+$("#dt_inicio").val()+
			  "&dt_fim="+$("#dt_fim").val()+
			  "&cd_profissional="+$("#cd_profissional").val()+
			  "&tx_solicitacao="+$("#tx_solicitacao").val()+
			  "&tipo_consulta="+$("input[name=tipo_consulta]:checked").val(),
		success: function(retorno){
			$("#gridSolicitacaoServicoConsulta").html(retorno);
		}
	});
}

function abreTabDetalheSolicitacao(cd_objeto, ni_solicitacao, ni_ano_solicitacao, tab_origem)
{
	$.ajax({
		type: "POST",
		url : systemName+"/gerenciar-projetos-solicitacao/tab-detalhe-solicitacao",
		data: "cd_objeto="+cd_objeto
		      +"&ni_solicitacao="+ni_solicitacao
		      +"&ni_ano_solicitacao="+ni_ano_solicitacao
		      +"&tab_origem="+tab_origem,
		success: function(retorno){
			$("#detalhe-solicitacao").html(retorno);
			$('#container-gerenciarProjeto').triggerTab(6);
			$("#li-detalhe-solicitacao").css("display", "");
		}
	});
}