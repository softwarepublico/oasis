$(document).ready(function(){
	ajaxGridExecucaoRotina();
	
	var intervalID = window.setInterval('ajaxGridExecucaoRotina();', 300000);
	
	$("#cd_profissional_execucao_rotina").change(function(){
		if ($("#cd_profissional_execucao_rotina").val() != -1) {
            ajaxGridExecucaoRotina();
        }
	});	
	
});

function ajaxGridExecucaoRotina()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/gerenciamento-rotina/grid-execucao-rotina",
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
		url		: systemName+"/gerenciamento-rotina/tab-historico-execucao-rotina",
		data	: "dt_execucao_rotina="+dt_execucao_rotina
				 +"&cd_profissional="+cd_profissional
				 +"&cd_objeto="+cd_objeto
				 +"&cd_rotina="+cd_rotina
				 +"&tab_origem="+tab_origem,
		success: function(retorno){
			$("#historico_rotina"	).html(retorno);
			$('#container_painel_rotina').triggerTab(3);
			$("#li-historico-rotina"	).css("display", "");
		}
	});
}

