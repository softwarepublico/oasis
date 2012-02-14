$(document).ready(function(){
	gridHostoricoDemandaExecucao();
	$('#bt_registrar_execucao_demanda').click(function () {
		if(!validaForm()){return false;}
		if(!comparaDataHoraInicioFim('dt_demanda_nivel_servico','dt_inicio')){return false; }
		if(!comparaDataHoraInicioFim('dt_inicio','dt_fim')){return false; }
		var postData = $('#formRegistrarExecucaoDemanda :input').serialize();
		$.post(systemName+'/demanda-executor-demanda/registra-historico-demanda',
			   postData, 
			   function(response) {
			       alertMsg(response,'',"window.location.href = '"+systemName+"/demanda-executor-demanda'");
			   }
		);
	});	
});

function gridHostoricoDemandaExecucao()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/demanda-executor-demanda/grid-historico-execucao-demanda",
		data	: "cd_demanda="+$("#cd_demanda").val()+"&cd_nivel_servico="+$("#cd_nivel_servico").val(),
		success	: function(retorno){
			$('#gridHistoricoDemandaExecutada').html(retorno);
		}
	});
}

function excluirHistorico(cd_historico_execucao)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/demanda-executor-demanda/excluir-historico-execucao-demanda",
		data	: "cd_historico_execucao="+cd_historico_execucao,
		success	: function(retorno){
			gridHostoricoDemandaExecucao();
			alertMsg(retorno);
		}
	});
}

function recuperaHistoricoDemanda(cd_historico_execucao)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/demanda-executor-demanda/recuperar-historico-execucao-demanda",
		data	: "cd_historico_execucao="+cd_historico_execucao,
		dataType: "json",
		success	: function(retorno){
			$('#cd_historico_execucao_demanda'	).val(retorno['cd_historico_execucao_demanda']);
			$('#dt_inicio'						).val(retorno['dt_inicio']);
			$('#dt_fim'							).val(retorno['dt_fim']);
			$('#tx_historico'					).val(retorno['tx_historico']);
            
            if($('#cd_profissional_logado').val() != retorno['cd_profissional']){
                $('#bt_registrar_execucao_demanda').hide();
            }
            else {
				$('#bt_registrar_execucao_demanda').show(); 
			}
		}
	});	
}