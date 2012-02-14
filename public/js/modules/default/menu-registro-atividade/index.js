$(document).ready(function(){
    //verifica se o usuário ira sair da pagina mesmo
//    window.onbeforeunload=goodbye;
/**
	tela de histórico
*/
	cancelarHistorico();
	$('#submitbuttonHistorico'	).hide();
	$('#adicionarHistorico'		).show();
	$('#alterarHistorico'		).hide();
	$('#cancelarHistorico'		).hide();
	$('#cd_projeto_historico'	).val("0");
	$('#cd_proposta_historico'	).val("0");
	
	$('#adicionarHistorico').click(function(){
		salvarDadosHistorico();
	});
	$('#alterarHistorico').click(function(){
		alterarDadosHistorico();
	});
	$('#cancelarHistorico').click(function(){
		cancelarHistorico();
	});

/**
tela de Reunião
*/
	$('#submitbuttonReuniao').hide();
	$('#adicionarReuniao'	).show();
	$('#alterarReuniao'		).hide();
	$('#cancelarReuniao'	).hide();
	
	$('#adicionarReuniao').click(function(){
		//função esta no java script da reunião reuniao/index.js
		salvarDadosReuniao();
	});
	$('#alterarReuniao').click(function(){
		//função esta no java script da reunião reuniao/index.js
		alterarDadosReuniao()
	});
	$('#cancelarReuniao').click(function(){
		//função esta no java script da reunião reuniao/index.js
		cancelarReuniao();
	});
});

function salvarDadosHistorico()
{
	if(!validaHistorico()){return false;}
	$.ajax({
		type	: "POST",
		url		: systemName+"/historico/salvar-dados-historico",
		data	: $('#historico :input').serialize(),
		success	: function(retorno){
			ajaxGridHistorico();
			cancelarHistorico();
			alertMsg(retorno);
		}
	});
}

function alterarDadosHistorico()
{
	if(!validaHistorico()){return false;}
	$.ajax({
		type	: "POST",
		url		: systemName+"/historico/alterar-dados-historico",
		data	: $('#historico :input').serialize(),
		success	: function(retorno){
			ajaxGridHistorico();
			cancelarHistorico();
			alertMsg(retorno);
		}
	});
}

function cancelarHistorico()
{
	$('#adicionarHistorico'		).show();
	$('#alterarHistorico'		).hide();
	$('#cancelarHistorico'		).hide();
	$('#cd_modulo_historico'	).val("0");
	$('#cd_etapa_historico'		).val("0");
	$('#cd_atividade_historico'	).val("0");
	$('#dt_inicio_historico'	).val("");
	$('#dt_fim_historico'		).val("");
	$('#cd_historico'			).val("");
	$('#tx_historico'			).wysiwyg('clear');
}


