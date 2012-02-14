$(document).ready(function(){
	cancelarReuniao();
	$("#bt_excluir").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){excluirDadosReuniao();});
	});
	
	$("#cd_projeto_reuniao").change(function(){
		if($("#cd_projeto_reuniao").val() != 0){
			montaGridReuniao();
			$('#gridReuniao').show();
		} else {
			$('#gridReuniao').hide();
		}
	});
});

function salvarDadosReuniao()
{
	if(!validaReuniao()){return false};
	$.ajax({
		type	: "POST",
		url		: systemName+"/reuniao/salvar-dados-reuniao",
		data	: $('#reuniao :input').serialize(),
		success	: function(retorno){
			cancelarReuniao();
			montaGridReuniao();
			alertMsg(retorno);
		}
	});
}

function alterarDadosReuniao()
{
	if(!validaReuniao()){return false};
	$.ajax({
		type	: "POST",
		url		: systemName+"/reuniao/alterar-dados-reuniao",
		data	: $('#reuniao :input').serialize(),
		success	: function(retorno){
			cancelarReuniao();
			montaGridReuniao();
			alertMsg(retorno);
		}
	});
}

function excluirReuniao(cd_reuniao)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/reuniao/excluir",
			data	: "cd_reuniao="+cd_reuniao,
			success	: function(retorno){
				alertMsg(retorno);
				montaGridReuniao();
			}
		});
	});
}

function cancelarReuniao()
{
	$('#adicionarReuniao'	).show();
	$('#alterarReuniao'		).hide();
	$('#cancelarReuniao'	).hide();
	
	$('#dt_reuniao'			).val("");
	$('#tx_local_reuniao'	).val("");
	$('#tx_pauta'			).val("");
	$('#tx_participantes'	).val("");
	$('#tx_ata'				).wysiwyg('clear');
}

function montaGridReuniao()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/reuniao/grid-reuniao",
		data	: "cd_projeto="+$("#cd_projeto_reuniao").val(),
		success	: function(retorno){
			$('#gridReuniao').html(retorno);
		}
	});
}

function recuperaReuniao(cd_reuniao)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/reuniao/recupera-reuniao",
		data	: "cd_reuniao="+cd_reuniao,
		dataType: 'json',
		success	: function(retorno){
			$('#adicionarReuniao'	).hide();
			$('#alterarReuniao'		).show();
			$('#cancelarReuniao'	).show();
			
			$('#cd_reuniao'			).val(retorno[0]['cd_reuniao']);
			$('#cd_projeto_reuniao'	).val(retorno[0]['cd_projeto']);
			$('#dt_reuniao'			).val(retorno[0]['dt_reuniao']);
			$('#tx_local_reuniao'	).val(retorno[0]['tx_local_reuniao']);
			$('#tx_pauta'			).val(retorno[0]['tx_pauta']);
			$('#tx_participantes'	).val(retorno[0]['tx_participantes']);
			$('#tx_ata'				).wysiwyg('value',retorno[0]['tx_ata']);
		}
	});
}

function validaReuniao()
{
    var msg = i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO;

	if($('#cd_projeto_reuniao').val() == "0"){
		showToolTip(msg,$('#cd_projeto_reuniao'));
		$('#cd_projeto_reuniao').focus();
		return false;
	}
	if($('#dt_reuniao').val() == ""){
		showToolTip(msg,$('#dt_reuniao'));
		$('#dt_reuniao').focus();
		return false;
	}
	if($('#tx_local_reuniao').val() == ""){
		showToolTip(msg,$('#tx_local_reuniao'));
		$('#tx_local_reuniao').focus();
		return false;
	}
	if($('#tx_pauta').val() == ""){
		showToolTip(msg,$('#tx_pauta'));
		$('#tx_pauta').focus();
		return false;
	}
	if($('#tx_participantes').val() == ""){
		showToolTip(msg,$('#tx_participantes'));
		$('#tx_participantes').focus();
		return false;
	}
	if($('#tx_ata').wysiwyg('getContent').val() == ""){
		showToolTip(msg,$('#tx_ata_editor'));
		var t = setTimeout('removeTollTip()',5000);
		return false;
	}
	
	return true;
}