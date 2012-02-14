$(document).ready(function(){
	//apresentaData($("#mes_pre_demanda").val(),$("#ano_pre_demanda").val(),"mesAnoPreDemanda");
	//apresentaData($("#mes_pre_demanda_executada").val(),$("#ano_pre_demanda_executada").val(),"mesAnoPreDemanda");
	
	var intervalID = window.setInterval('ajaxGridPreDemanda();', 300000);
	var intervalID = window.setInterval('ajaxGridPreDemandaExecutada();', 300000);
		
	//tela pre-demanda para encaminhar
	ajaxGridPreDemanda();
	$("#mes_pre_demanda").change(function(){
		ajaxGridPreDemanda();
		apresentaData($("#mes_pre_demanda").val(),$("#ano_pre_demanda").val(),"mesAnoPreDemanda");
	});
	$("#ano_pre_demanda").change(function(){
		ajaxGridPreDemanda();
		apresentaData($("#mes_pre_demanda").val(),$("#ano_pre_demanda").val(),"mesAnoPreDemanda");
	});
	$("#cd_nucleo_pre_demanda").change(function(){
		ajaxGridPreDemanda();
	});
	
	//tela pre-demanda executada
	ajaxGridPreDemandaExecutada();
	$("#mes_pre_demanda_executada").change(function(){
		ajaxGridPreDemandaExecutada();
		apresentaData($("#mes_pre_demanda_executada").val(),$("#ano_pre_demanda_executada").val(),"mesAnoPreDemanda");
	});
	$("#ano_pre_demanda_executada").change(function(){
		ajaxGridPreDemandaExecutada();
		apresentaData($("#mes_pre_demanda_executada").val(),$("#ano_pre_demanda_executada").val(),"mesAnoPreDemanda");
	});
	$("#cd_objeto_receptor_executada").change(function(){
		ajaxGridPreDemandaExecutada();
	});	
});

function ajaxGridPreDemanda() 
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/coordenador-pre-demanda/grid-pre-demanda",
		data	: "mes="+$("#mes_pre_demanda").val()
			      +"&ano="+$("#ano_pre_demanda").val()
			      +"&nucleo="+$("#cd_nucleo_pre_demanda").val(),
		success	: function(retorno){
			$("#gridPreDemanda").html(retorno);
			$('#mesAno').html($('#mesAnoCoodenador').val());
		}
	});
}

function ajaxGridPreDemandaExecutada() 
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/coordenador-pre-demanda/grid-pre-demanda-executada",
		data	: "mes="+$("#mes_pre_demanda_executada").val()
			      +"&ano="+$("#ano_pre_demanda_executada").val()
			      +"&cd_objeto_receptor="+$("#cd_objeto_receptor_executada").val(),
		success	: function(retorno){
			$("#gridPreDemandaExecutada").html(retorno);
			$('#mesAnoExecutada').html($('#mesAnoCoodenadorExecutada').val());
		}
	});
}

function encaminharPreDemanda(cd_pre_demanda)
{
	window.location.href = systemName+"/encaminhar-pre-demanda/index/cd_pre_demanda/"+cd_pre_demanda;
}

function abreModalFechamentoSolicitacao(ni_solicitacao, ni_ano_solicitacao, cd_objeto, tx_objeto) 
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
			ajaxGridPreDemandaExecutada();
			closeDialog('dialog_fechamento_solicitacao');
		}
	});
}

function abreModalReaberturaPreDemanda(cd_pre_demanda, ni_solicitacao, ni_ano_solicitacao, cd_objeto)
{
	var jsonData = {  'cd_pre_demanda':cd_pre_demanda,
					  'ni_solicitacao':ni_solicitacao,
					  'ni_ano_solicitacao':ni_ano_solicitacao,
					  'cd_objeto':cd_objeto
				   };
	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_reabrir_pre_demanda');}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarReaberturaPreDemanda();}+'};');

    loadDialog({
        id       : 'dialog_reabrir_pre_demanda',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_PARCER_REABRIR_PRE_DEMANDA,	// titulo do pop-up
        url      : systemName + '/coordenador-pre-demanda/reabrir-pre-demanda',	// url onde encontra-se o phtml
        data     : jsonData,							// parametros para serem transferidos para o pop-up
        height   : 250,									// altura do pop-up
        buttons  : buttons
    });
}