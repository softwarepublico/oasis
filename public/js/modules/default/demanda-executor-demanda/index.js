$(document).ready(function(){
	ajaxGridDemandaSolicitada();
	ajaxGridDemandaExecutada();
	
	var intervalID = window.setInterval('ajaxGridDemandaSolicitada();', 300000);	
	
	//TELA DEMANDA SOLICITADA
	$("#mesDemandaSolicitada").change(function(){
		ajaxGridDemandaSolicitada();
		apresentaData($("#mesDemandaSolicitada").val(),$("#anoDemandaSolicitada").val(),"mesAnoDemandaSolicitada");
	});
	
	$("#anoDemandaSolicitada").change(function(){
		ajaxGridDemandaSolicitada();
		apresentaData($("#mesDemandaSolicitada").val(),$("#anoDemandaSolicitada").val(),"mesAnoDemandaSolicitada");
	});
	
	//TELA DEMANDA EXECUTADA
	$("#mesDemandaExecutada").change(function(){
		ajaxGridDemandaExecutada();
		apresentaData($("#mesDemandaExecutada").val(),$("#anoDemandaExecutada").val(),"mesAnoDemandaExecutada");
	});
	
	$("#anoDemandaExecutada").change(function(){
		ajaxGridDemandaExecutada();
		apresentaData($("#mesDemandaExecutada").val(),$("#anoDemandaExecutada").val(),"mesAnoDemandaExecutada");
	});
	
	//TELA DETALHES DA DEMANDA SOLICITADA
	$("#bt_voltar").click(function (){
		window.location.href = systemName+"/demanda-executor-demanda";
	});
	
	//TELA DETALHES DA DEMANDA EXECUTADA
	$("#bt_retornar").click(function (){
		window.location.href = systemName+"/demanda-executor-demanda\#"+$("#abaOrigem").val();
	});	
	
	//TELA DE REGISTRO DE EXECUÇÃO DE DEMANDA
	$("#bt_cancelar_execucao_demanda").click(function (){
		window.location.href = systemName+"/demanda-executor-demanda";
	});	
});

function ajaxGridDemandaSolicitada() 
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/demanda-executor-demanda/grid-demanda-solicitada",
		data	: "mes="+$("#mesDemandaSolicitada").val()
			     +"&ano="+$("#anoDemandaSolicitada").val(),
		success	: function(retorno){
			$("#gridDemandaSolicitada").html(retorno);
			$('#mesAno').html($('#mesAnoDemandaSolicitada').val());
		}
	});
}

function ajaxGridDemandaExecutada() 
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/demanda-executor-demanda/grid-demanda-executada",
		data	: "mes="+$("#mesDemandaExecutada").val()
				  +"&ano="+$("#anoDemandaExecutada").val(),
		success	: function(retorno){
			$("#gridDemandaExecutada").html(retorno);
			$('#mesAnoExecutada').html($('#mesAnoDemandaExecutada').val());
		}
	});
}

/**
 * Abre o pop-up para a justificativa da demanda
 */
function abreModalJustificar(cd_demanda, dt_demanda, cd_nivel_servico) 
{
	var jsonData = {'cd_demanda':cd_demanda,
					'cd_nivel_servico': cd_nivel_servico,
					'dt_demanda': dt_demanda
				   };
	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_justificativa_demanda');}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarJustificativaDemanda();}+'};');
    loadDialog({
        id       : 'dialog_justificativa_demanda',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_JUSTIFICATIVA_DEMANDA,	// titulo do pop-up
        url      : systemName + '/justificativa-demanda', // url onde encontra-se o phtml
        data     : jsonData,							  // parametros para serem transferidos para o pop-up
        height   : 300,									  // altura do pop-up
        buttons  : buttons
    });
}

/**
 * Salva a justificativa da demanda
 */
function salvarJustificativaDemanda() {

	$.ajax({
		type	: "POST",
		url		: systemName+"/justificativa-demanda/salvar",
		data	: $('#formJustificativaDemanda :input').serialize(),
		success	: function(retorno){
		   alertMsg(retorno);
		   ajaxGridDemandaSolicitada();
		   closeDialog('dialog_justificativa_demanda');
		}
	});
}

function gravaDataHoraLeituraDemanda() {
	$.ajax({
		type	: "POST",
		url		: systemName+"/demanda-executor-demanda/grava-data-hora-leitura-demanda",
		data	: "cd_demanda="+$("#cd_demanda").val()
				 +"&cd_profissional="+$('#cd_profissional').val()
				 +"&cd_nivel_servico="+$('#cd_nivel_servico').val(),
		success	: function(retorno){
			alertMsg(retorno,'',"window.location.href = '"+systemName+"/demanda-executor-demanda'");
		}
	});
}

function registraExecucaoDemanda(cd_demanda, cd_nivel_servico){
	window.location.href = systemName+"/demanda-executor-demanda/executar-demanda/cd_demanda/"+cd_demanda+"/cd_nivel_servico/"+cd_nivel_servico;
}

function fecharExecucaoDemanda(cd_demanda, cd_profissional, cd_nivel_servico)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_FECHAR_NIVEL_SERVICO, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/demanda-executor-demanda/fecha-execucao-demanda",
			data	: "cd_demanda="+cd_demanda
					 +"&cd_profissional="+cd_profissional
					 +"&cd_nivel_servico="+cd_nivel_servico,
			success	: function(retorno){
				alertMsg(retorno);
				ajaxGridDemandaSolicitada();
				ajaxGridDemandaExecutada();
			}
		});
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