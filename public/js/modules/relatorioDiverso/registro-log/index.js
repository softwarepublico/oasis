$(document).ready(function(){
	
	$("#btn_gerar").click(function(){
		if(!validaForm("#form_registro_log")){return false};
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
				gerarRelatorio( $('#form_registro_log') , 'registro-log/registro-log' );
			}
		}
	});
}