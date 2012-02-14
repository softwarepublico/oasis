$(document).ready(function(){
	ajaxGridExecucaoRotina();
	
	var intervalID = window.setInterval('ajaxGridExecucaoRotina();', 300000);
	
});

function ajaxGridExecucaoRotina()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/execucao-rotina/grid-execucao-rotina",
		data	: "dt_execucao_rotina="+$("#dt_execucao_rotina").val()
				 +"&cd_profissional="+$("#cd_profissional_execucao_rotina").val(),
		success	: function(retorno){
			$("#grid_execucao_rotina").html(retorno);
		}
	});
}

function abreTabHistoricoExecucaoRotina(dt_execucao_rotina, cd_profissional, cd_objeto, cd_rotina, tab_origem)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/execucao-rotina/tab-historico-execucao-rotina",
		data	: "dt_execucao_rotina="+dt_execucao_rotina
				 +"&cd_profissional="+cd_profissional
				 +"&cd_objeto="+cd_objeto
				 +"&cd_rotina="+cd_rotina
				 +"&tab_origem="+tab_origem,
		success: function(retorno){
			$("#historico_rotina"	    ).html(retorno);
			$('#container_painel_rotina').triggerTab(2);
			$("#li-historico-rotina"	).css("display", "");
		}
	});
}

function fecharExecucaoRotina(dt_execucao_rotina, cd_profissional, cd_objeto, cd_rotina)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_FECHAR_ROTINA, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/execucao-rotina/fecha-execucao-rotina",
			data	: "dt_execucao_rotina="+dt_execucao_rotina
				 +"&cd_profissional="+cd_profissional
				 +"&cd_objeto="+cd_objeto
				 +"&cd_rotina="+cd_rotina,
			success	: function(retorno){
				alertMsg(retorno);
				ajaxGridExecucaoRotina();
			}
		});
	});
}
