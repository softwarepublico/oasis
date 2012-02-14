$(document).ready(function(){
	montaGridTreinamento();
	configTreinamento();
	
	// pega evento no onclick dos botões
	$("#btn_salvar_treinamento").click(function(){
		if( !validaForm() ){ return false; }
		salvarTreinamento();
	});

	$('#btn_alterar_treinamento').click(function(){
		if( !validaForm() ){ return false; }
		alterarTreinamento();
	});
});

function salvarTreinamento()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/treinamento/salvar",
		data	: $("form :input").serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			montaGridTreinamento();
			configTreinamento();
		}
	});
}

function alterarTreinamento()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/treinamento/alterar",
		data	: $("form :input").serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			montaGridTreinamento();
			configTreinamento();
		}
	});
}

function excluirTreinamento(cd_treinamento)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName + "/treinamento/excluir",
			data	: "cd_treinamento=" + cd_treinamento,
			success	: function(retorno){
				alertMsg(retorno);
				montaGridTreinamento();
				configTreinamento();
			}
		});
	});
}

function montaGridTreinamento()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/treinamento/grid-treinamento",
		success	: function(retorno){
			$('#gridTreinamento').html(retorno);
		}
	});
}

function recuperaTreinamento(cd_treinamento)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/treinamento/recupera-dados",
		data	: "cd_treinamento="+cd_treinamento,
		dataType: 'json',
		success	: function(retorno){
			$('#cd_treinamento'			).val(retorno['cd_treinamento']);
			$('#tx_treinamento'			).val(retorno['tx_treinamento']);
			$('#tx_obs_treinamento'		).val(retorno['tx_obs_treinamento']);
			$('#btn_alterar_treinamento').show();
			$('#btn_salvar_treinamento'	).hide();
		}
	});
}

function configTreinamento()
{
	$('#cd_treinamento'			).val("");
	$('#tx_treinamento'			).val("");
	$('#tx_obs_treinamento'		).val("");
	$('#btn_alterar_treinamento').hide();
	$('#btn_salvar_treinamento'	).show();
}