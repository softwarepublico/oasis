$(document).ready(function(){
	ajaxGridSolicitacaoTipoDemanda();
	//ajaxGridSolicitacaoTipoDemandaExecutada();
	//ajaxGridSolicitacaoTipoDemandaConcluida();
	
	var intervalID = window.setInterval('ajaxGridSolicitacaoTipoDemanda();', 300000);
	var intervalID = window.setInterval('ajaxGridSolicitacaoTipoDemandaExecutada();', 300000);
	var intervalID = window.setInterval('ajaxGridSolicitacaoTipoDemandaConcluida();', 300000);
	
	//Solicitações de Serviço Tipo Demanda a Encaminhar
	$("#mesSolicitacaoTipoDemanda").change(function(){
		ajaxGridSolicitacaoTipoDemanda();
		apresentaData($("#mesSolicitacaoTipoDemanda").val(),$("#anoSolicitacaoTipoDemanda").val(),"mesAnoTipoDemanda");
	});
	
	$("#anoSolicitacaoTipoDemanda").change(function(){
		ajaxGridSolicitacaoTipoDemanda();
		apresentaData($("#mesSolicitacaoTipoDemanda").val(),$("#anoSolicitacaoTipoDemanda").val(),"mesAnoTipoDemanda");
	});
	
	$("#cd_profissional_tipo_demanda").change(function(){
		ajaxGridSolicitacaoTipoDemanda();
		apresentaData($("#mesSolicitacaoTipoDemanda").val(),$("#anoSolicitacaoTipoDemanda").val(),"mesAnoTipoDemanda");
	});
	
	//Solicitações Tipo Demanda Encaminhadas
	//e Executadas
	$("#mesSolicitacaoTipoDemandaExecutada").change(function(){
		ajaxGridSolicitacaoTipoDemandaExecutada();
		apresentaData($("#mesSolicitacaoTipoDemandaExecutada").val(),$("#anoSolicitacaoTipoDemandaExecutada").val(),"mesAnoExecutada");
	});
	
	$("#anoSolicitacaoTipoDemandaExecutada").change(function(){
		ajaxGridSolicitacaoTipoDemandaExecutada();
		apresentaData($("#mesSolicitacaoTipoDemandaExecutada").val(),$("#anoSolicitacaoTipoDemandaExecutada").val(),"mesAnoExecutada");
	});	
	
	$("#cd_profissional_tipo_demanda_executada").change(function(){
		ajaxGridSolicitacaoTipoDemandaExecutada();
		apresentaData($("#mesSolicitacaoTipoDemandaExecutada").val(),$("#anoSolicitacaoTipoDemandaExecutada").val(),"mesAnoExecutada");
	});	
	
	//Solicitações Tipo Demanda Encaminhadas
	//e Concluidas
	$("#mesSolicitacaoTipoDemandaConcluida").change(function(){
		ajaxGridSolicitacaoTipoDemandaConcluida();
		apresentaData($("#mesSolicitacaoTipoDemandaConcluida").val(),$("#anoSolicitacaoTipoDemandaConcluida").val(),"mesAnoTipoDemandaConcluida");
	});
	
	$("#anoSolicitacaoTipoDemandaConcluida").change(function(){
		ajaxGridSolicitacaoTipoDemandaConcluida();
		apresentaData($("#mesSolicitacaoTipoDemandaConcluida").val(),$("#anoSolicitacaoTipoDemandaConcluida").val(),"mesAnoTipoDemandaConcluida");
	});		
	
	$("#cd_profissional_tipo_demanda_concluida").change(function(){
		ajaxGridSolicitacaoTipoDemandaConcluida();
		apresentaData($("#mesSolicitacaoTipoDemandaConcluida").val(),$("#anoSolicitacaoTipoDemandaConcluida").val(),"mesAnoTipoDemandaConcluida");
	});		

	$("#bt_ok").click(function (){

		$.ajax({
			type	: "POST",
			url		: systemName+"/solicitacao-tipo-demanda/grava-data-hora-leitura-solicitacao",
			data	: "cd_objeto="+$("#cd_objeto").val()+
					  "&ni_solicitacao="+$("#ni_solicitacao").val()+
					  "&ni_ano_solicitacao="+$("#ni_ano_solicitacao").val(),
			success: function(retorno){
				alertMsg(retorno,2,"redirecionaSolicitacaoTipoDemanda()");
			}
		});
	});	
	
	$("#bt_consultar").click(function (){
		ajaxGridSolicitacaoTipoDemandaConsulta();
	});	
});

function redirecionaSolicitacaoTipoDemanda()
{
    window.location.href = systemName+"/solicitacao-tipo-demanda";
}

function ajaxGridSolicitacaoTipoDemanda() 
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/solicitacao-tipo-demanda/grid-solicitacao-tipo-demanda",
		data	: "mes="+$("#mesSolicitacaoTipoDemanda").val()
				 +"&ano="+$("#anoSolicitacaoTipoDemanda").val()
				 +"&cd_profissional="+$("#cd_profissional_tipo_demanda").val(),
		success	: function(retorno){
			$("#gridSolicitacaoTipoDemanda").html(retorno);
			$('#mesAno').html($('#mesAnoSolicitacaoTipoDemanda').val());
		}
	});
}

function ajaxGridSolicitacaoTipoDemandaExecutada() 
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/solicitacao-tipo-demanda/grid-solicitacao-tipo-demanda-executada",
		data	: "mes="+$("#mesSolicitacaoTipoDemandaExecutada").val()
				 +"&ano="+$("#anoSolicitacaoTipoDemandaExecutada").val()
				 +"&cd_profissional="+$("#cd_profissional_tipo_demanda_executada").val(),
		success: function(retorno){
			$("#gridSolicitacaoTipoDemandaExecutada").html(retorno);
			$('#mesAnoExecutada'					).html($('#mesAnoSolicitacaoTipoDemandaExecutada').val());
		}
	});
}

function ajaxGridSolicitacaoTipoDemandaConcluida() 
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/solicitacao-tipo-demanda/grid-solicitacao-tipo-demanda-concluida",
		data	: "mes="+$("#mesSolicitacaoTipoDemandaConcluida").val()
				 +"&ano="+$("#anoSolicitacaoTipoDemandaConcluida").val()
				 +"&cd_profissional="+$("#cd_profissional_tipo_demanda_concluida").val(),
		success: function(retorno){
			$("#gridSolicitacaoTipoDemandaConcluida").html(retorno);
			$('#mesAnoConcluida'					).html($('#mesAnoSolicitacaoTipoDemandaConcluida').val());
		}
	});
}

function ajaxGridSolicitacaoTipoDemandaConsulta() 
{
	if ($("#cd_unidade").val() == "0" && $("#tx_solicitante").val() == "" && $("#dt_inicio").val() == "" && $("#dt_fim").val() == "" && $("#cd_profissional").val() == "-1" && $("#solicitacao").val() == "" && $("#tx_demanda").val() == ""){
		alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PARAMETRO_PESQUISA);
		return false;
	}

	if (($("#dt_inicio").val() != "" && $("#dt_fim").val() == "") || ($("#dt_inicio").val() == "" && $("#dt_fim").val() != "")){
		alertMsg(i18n.L_VIEW_SCRIPT_INDIQUE_PERIODO_PESQUISA);
		return false;
	}
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/solicitacao-tipo-demanda/grid-solicitacao-tipo-demanda-consulta",
		data	: "cd_unidade="+$("#cd_unidade").val()+
				  "&tx_solicitante="+$("#tx_solicitante").val()+
				  "&dt_inicio="+$("#dt_inicio").val()+
				  "&dt_fim="+$("#dt_fim").val()+
				  "&cd_profissional="+$("#cd_profissional").val()+
				  "&solicitacao="+$("#solicitacao").val()+
				  "&tx_demanda="+$("#tx_demanda").val()+
				  "&tipo_consulta="+$("input[name=tipo_consulta]:checked").val(),
		success: function(retorno){
			$("#gridSolicitacaoTipoDemandaConsulta").html(retorno);
		}
	});
}

/**
 * Abre pop-up para justificativa de solicitação
 */
function abreModalJustificar(cd_objeto, ni_solicitacao, ni_ano_solicitacao) {

	var jsonData = {'cd_objeto_justificativa_solicitacao':cd_objeto,
					'ni_solicitacao_justificativa_solicitacao': ni_solicitacao,
					'ni_ano_solicitacao_justificativa_solicitacao': ni_ano_solicitacao
				   };

    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_justificativa_solicitacao');}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarJustificativaSolicitacao();}+'};');

	loadDialog({
        id       : 'dialog_justificativa_solicitacao',			//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_JUSTIFICATIVA_SOLICITACAO_SERVICO,	// titulo do pop-up
        url      : systemName + '/justificativa-solicitacao',	// url onde encontra-se o phtml
        data     : jsonData,									// parametros para serem transferidos para o pop-up
        height   : 300,											// altura do pop-up
        buttons  : buttons
    });
}

/**
 * Abre pop-up para ver a justificativa de solicitação registrada
 */
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

/**
 * Salva registro de justificativa de solicitação
 */
function salvarJustificativaSolicitacao() {

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
				ajaxGridSolicitacaoTipoDemanda();
				closeDialog('dialog_justificativa_solicitacao');
			}
		}
	});
}

/**
 *	Abre pop-up para registra da conclusao da solicitação
 */
function abreModalConclusaoSolicitacaoTipoDemanda(cd_objeto, ni_solicitacao, ni_ano_solicitacao) {

	var jsonData = {'cd_objeto':cd_objeto,
					'ni_solicitacao': ni_solicitacao,
					'ni_ano_solicitacao': ni_ano_solicitacao
				   };
    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_conclusao_solicitacao_tipo_demanda');}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarConclusaoSolicitacaoTipoDemanda();}+'};');

    loadDialog({
        id       : 'dialog_conclusao_solicitacao_tipo_demanda',			//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_CONCLUSAO_SOLICITACAO_SERVICO,	// titulo do pop-up
        url      : systemName + '/solicitacao-tipo-demanda/conclusao-solicitacao',	// url onde encontra-se o phtml
        data     : jsonData,									// parametros para serem transferidos para o pop-up
        height   : 300,											// altura do pop-up
        buttons  : buttons
    });
}

/**
 * Salva a conclusão da solicitação tipo demanda
 */
function salvarConclusaoSolicitacaoTipoDemanda()
{
	if( !validaForm("#formConclusaoSolicitacao", true) ){return false; }

	$.ajax({
		type	: "POST",
		url		: systemName+'/solicitacao-tipo-demanda/concluir',
		data	: $('#formConclusaoSolicitacao :input').serialize(),
		success	: function(retorno){
				alertMsg(retorno,2,function(){redirecionaSolicitacaoTipoDemanda()});
				ajaxGridSolicitacaoTipoDemandaExecutada();
				ajaxGridSolicitacaoTipoDemandaConcluida();
				closeDialog('dialog_conclusao_solicitacao_tipo_demanda');
		}
	});
}

function redirecionaSolicitacaoTipoDemanda()
{
    window.location.href = systemName+"/solicitacao-tipo-demanda\#solicitacao-tipo-demanda-andamento";
}

function encaminhaSolicitacaoTipoDemanda(cd_objeto, ni_solicitacao, ni_ano_solicitacao)
{
	window.location.href = systemName+"/encaminhar-solicitacao-tipo-demanda/index/cd_objeto/"+cd_objeto+"/ni_solicitacao/"+ni_solicitacao+"/ni_ano_solicitacao/"+ni_ano_solicitacao;
}

function encaminhaSolicitacaoTipoDemandaParaOutroProfissional(cd_objeto, ni_solicitacao, ni_ano_solicitacao, cd_demanda)
{
	window.location.href = systemName+"/encaminhar-solicitacao-tipo-demanda/index/cd_objeto/"+cd_objeto+"/ni_solicitacao/"+ni_solicitacao+"/ni_ano_solicitacao/"+ni_ano_solicitacao+"/cd_demanda/"+cd_demanda;
}

function reencaminhaSolicitacaoTipoDemanda(cd_objeto, ni_solicitacao, ni_ano_solicitacao, cd_demanda)
{
	window.location.href = systemName+"/reencaminhar-solicitacao-tipo-demanda/index/cd_objeto/"+cd_objeto+"/ni_solicitacao/"+ni_solicitacao+"/ni_ano_solicitacao/"+ni_ano_solicitacao+"/cd_demanda/"+cd_demanda;
}

function abreTabProfissionalDesignado(cd_demanda, cd_profissional, tab_origem)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/solicitacao-tipo-demanda/tab-profissional-designado",
		data	: "cd_demanda="+cd_demanda
				 +"&cd_profissional="+cd_profissional
				 +"&tab_origem="+tab_origem,
		success: function(retorno){
			$("#profissional-designado"			).html(retorno);
			$('#containerSolicitacaoTipoDemanda').triggerTab(4);
			$("#li-profissional-designado"		).css("display", "");
		}
	});
}

/**
 * Abre pop-up para visualização de relatorios
 */
function abrePopUp(url){
	var w = '800';
    var h = '600';

    openPopup( url, w, h );
}