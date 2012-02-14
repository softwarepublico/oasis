$(document).ready(function(){
	gridHistoricoExecucaoRotina();
    
	$('#bt_registrar_historico_execucao_rotina').click(function () {
		if(!validaForm()){return false;}
//		if(!comparaDataHoraInicioFim('dt_demanda_nivel_servico','dt_inicio')){return false; }
		var postData = $('#tabHistoricoExecucaoRotina :input').serialize();
		$.post(systemName+'/execucao-rotina/registra-historico-rotina',
			   postData, 
			   function(response) {
			       alertMsg(response,'',"window.location.href = '"+systemName+"/execucao-rotina'");
			   }
		);
	});
});

function gridHistoricoExecucaoRotina()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/execucao-rotina/grid-historico-execucao-rotina",
		data	: "dt_execucao_rotina="+$('#dt_execucao_rotina').val()
				 +"&cd_profissional="+$('#cd_profissional').val()
				 +"&cd_objeto="+$('#cd_objeto').val()
				 +"&cd_rotina="+$('#cd_rotina').val(),
		success	: function(retorno){
			$('#gridHistoricoExecucaoRotina').html(retorno);
		}
	});
}

function excluirHistorico(dt_historico_execucao_rotina, dt_execucao_rotina, cd_profissional, cd_objeto, cd_rotina)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/execucao-rotina/excluir-historico-execucao-rotina",
		data	: "dt_execucao_rotina="+dt_execucao_rotina
				 +"&cd_profissional="+cd_profissional
				 +"&cd_objeto="+cd_objeto
				 +"&cd_rotina="+cd_rotina
				 +"&dt_historico_execucao_rotina="+dt_historico_execucao_rotina,
		success	: function(retorno){
			gridHistoricoExecucaoRotina();
			alertMsg(retorno);
		}
	});
}

function recuperaHistoricoRotina(dt_historico_execucao_rotina, dt_execucao_rotina, cd_profissional, cd_objeto, cd_rotina)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/execucao-rotina/recuperar-historico-execucao-rotina",
		data	: "dt_execucao_rotina="+dt_execucao_rotina
				 +"&cd_profissional="+cd_profissional
				 +"&cd_objeto="+cd_objeto
				 +"&cd_rotina="+cd_rotina
				 +"&dt_historico_execucao_rotina="+dt_historico_execucao_rotina,
		dataType: "json",
		success	: function(retorno){
            $('#dt_historico_execucao_rotina'	).val(retorno['dt_historico_execucao_rotina']);
			$('#cd_rotina'						).val(retorno['cd_rotina']);
			$('#cd_objeto'						).val(retorno['cd_objeto']);
			$('#cd_profissional'				).val(retorno['cd_profissional']);
			$('#dt_execucao_rotina'				).val(retorno['dt_execucao_rotina']);
			$('#dt_historico_rotina'			).val(retorno['dt_historico_rotina']);
			$('#tx_historico_execucao_rotina'	).val(retorno['tx_historico_execucao_rotina']);
		}
	});	
}