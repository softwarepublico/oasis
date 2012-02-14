$(document).ready(function(){

	//inicialização da tela
	$('#alterarEvento').hide();
	$('#cancelarEvento').hide();
	montaGridEvento();
	limpaDadosEvento();
	
	$('#salvarEvento').click(function(){
		validaDadosEvento("S");
	});
	$('#alterarEvento').click(function(){
		validaDadosEvento("A");
	});
	$('#cancelarEvento').click(function(){
		limpaDadosEvento();
	});
});

function validaDadosEvento(cond)
{
	if($('#tx_evento').val() == ""){
		$('#tx_evento').focus();
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#tx_evento'));
		return false;
	}
	if(cond == "S"){
		salvarEvento();
	} else {
		alterarEvento();
	}
}

function salvarEvento()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/evento/salvar-evento",
		data	: $('#evento :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			limpaDadosEvento();
			montaGridEvento();
		}
	});
}

function alterarEvento()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/evento/alterar-evento",
		data	: $('#evento :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			limpaDadosEvento();
			montaGridEvento();
		}
	});
}

function montaGridEvento()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/evento/grid-evento",
		success	: function(retorno){
			// atualiza a grid
			$("#gridEvento").html(retorno);
		}
	});
}

function excluirEvento(cd_evento)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/evento/excluir-evento",
			data	: "cd_evento="+cd_evento,
			success	: function(retorno){
				alertMsg(retorno);
				montaGridEvento();
			}
		});
    });
}

function recuperaEvento(cd_evento)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/evento/recupera-evento",
		data	: "cd_evento="+cd_evento,
		dataType: 'json',
		success	: function(retorno){
			$('#cd_evento'		).val(retorno['cd_evento']);
			$('#tx_evento'		).val(retorno['tx_evento']);
			$('#salvarEvento'	).hide();
			$('#alterarEvento'	).show();
			$('#cancelarEvento' ).show();
		}
	});
}

function limpaDadosEvento()
{
	$('#salvarEvento'	).show();
	$('#alterarEvento'	).hide();
	$('#cancelarEvento'	).hide();
	$('#tx_evento'		).val("");
}