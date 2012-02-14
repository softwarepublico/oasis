$(document).ready(function(){
	cancelarProjetoContinuado();
	
	if ($("#cd_objeto_projeto_continuado").val() != -1) {
		montaGridProjetoContinuado();
	}
	$("#bt_excluir").click(function() {
        excluirProjetoContinuado();
	});

	// pega evento no onclick do botao
	$("#submitbuttonProjetoContinuado").click(function(){
		$("form#projeto_continuado").submit();
	});
	
	$('#cd_objeto_projeto_continuado').change(function(){
		if($(this).val() != -1){
			montaGridProjetoContinuado();
		} else {
			$('#gridProjetoContinuado').hide();
		}
	});
});

function validaProjetoContinuo()
{
    var msg = i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO;

	if($('#cd_objeto_projeto_continuado').val() == "-1"){
		showToolTip(msg,$('#cd_objeto_projeto_continuado'));
		$('#cd_objeto_projeto_continuado').focus();
		return false;
	}
	if($('#tx_projeto_continuado').val() == ""){
		showToolTip(msg,$('#tx_projeto_continuado'));
		$('#tx_projeto_continuado').focus();
		return false;
	}
	if($('#tx_objetivo_projeto_continuado').wysiwyg('getContent').val() == ""){
		showToolTip(msg,$('#tx_objetivo_projeto_continuado_editor'));
		var t = setTimeout('removeTollTip()',5000);
		return false;
	}
	if($('#st_prioridade_proj_continuado').val() == "0"){
		showToolTip(msg,$('#st_prioridade_proj_continuado'));
		$('#st_prioridade_proj_continuado').focus();
		return false;
	}
	return true;
}

function salvarProjetoContinuado()
{
	if(!validaProjetoContinuo()){return false;}
	$.ajax({
		type	: "POST",
		url		: systemName+"/projeto-continuado/salvar-projeto-continuado",
		data	: $('#projeto_continuado :input').serialize(),
		success	: function(retorno){
			cancelarProjetoContinuado();
			montaGridProjetoContinuado();
			alertMsg(retorno);
		}
	});
}

function alterarProjetoContinuado()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/projeto-continuado/alterar-projeto-continuado",
		data	: $('#projeto_continuado :input').serialize(),
		success	: function(retorno){
			cancelarProjetoContinuado();
			montaGridProjetoContinuado();
			alertMsg(retorno);
		}
	});
}

function excluirProjetoContinuado(cd_projeto_continuado)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/projeto-continuado/excluir",
			data	: "cd_projeto_continuado="+cd_projeto_continuado,
			success	: function(retorno){
				alertMsg(retorno);
				cancelarProjetoContinuado();
				montaGridProjetoContinuado();
			}
		});
	});
}

function cancelarProjetoContinuado()
{
	$(".toolTip"							).remove();
	$('#adicionarProjetoContinuo'			).show();
	$('#alterarProjetoContinuo'				).hide();
	$('#cancelarProjetoContinuo'			).hide();
	$('#tx_projeto_continuado'				).val("");
	$('#tx_objetivo_projeto_continuado'		).wysiwyg("clear");
	$('#tx_obs_projeto_continuado'			).val("");
	$('#st_prioridade_proj_continuado'	).val("0");
}

function montaGridProjetoContinuado()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/projeto-continuado/grid-projeto-continuado",
		data	: "cd_objeto="+$("#cd_objeto_projeto_continuado").val(),
		success	: function(retorno){
			$('#gridProjetoContinuado').html(retorno);
			$('#gridProjetoContinuado').show();
		}
	});
}

function recuperaDadosProjetoContinuo(cd_projeto_continuado)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/projeto-continuado/recupera-projeto-continuado",
		data	: "cd_projeto_continuado="+cd_projeto_continuado,
		dataType: 'json',
		success	: function(retorno){
			$('#adicionarProjetoContinuo'		).hide();
			$('#alterarProjetoContinuo'			).show();
			$('#cancelarProjetoContinuo'		).show();
			$('#cd_projeto_continuado'			).val(retorno[0]['cd_projeto_continuado']);
			$('#tx_projeto_continuado'			).val(retorno[0]['tx_projeto_continuado']);
			$('#tx_objetivo_projeto_continuado'	).wysiwyg('value',retorno[0]['tx_objetivo_projeto_continuado']);
			$('#tx_obs_projeto_continuado'		).val(retorno[0]['tx_obs_projeto_continuado']);
			$('#st_prioridade_proj_continuado'	).val(retorno[0]['st_prioridade_proj_continuado']);
		}
	});	
}