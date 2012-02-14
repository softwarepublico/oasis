$(document).ready(function(){
	
	$("#btn_pesquisar").click(function(){
		if(!validaForm("#form_accordion_log")){return false};
		validaPeriodoDataLog();
	});
});

function validaPeriodoDataLog()
{
	$.ajax({
		type     : 'POST',
		url      : systemName+"/log/valida-periodo",
		data     : "dt_inicial="+$("#dt_inicio_log").val()+"&dt_final="+$("#dt_fim_log").val(),
		success  : function(retorno){
			if( !retorno ){
				alertMsg(i18n.L_VIEW_SCRIPT_PERIODO_PESQUISA_LOG_MUITO_GRANDE);
			}else{
				pesquisaRegistrotLog();
			}
		}
	});
}

function pesquisaRegistrotLog()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/log/grid-log",
		data	: $("#form_accordion_log :input").serialize(),
		success	: function(retorno){
		   $("#grid_registro_log").html(retorno);
		}
	});
}