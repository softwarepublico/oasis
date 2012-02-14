$(document).ready(function(){
    if( $("#cd_objeto_reuniao").val() != '-1' ){
		montaGridReuniaoGeral();
	}
    
	cancelarReuniaoGeral();
	$("#bt_excluir").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){excluirDadosReuniaoGeral();});
	});
	
	$("#cd_objeto_reuniao").change(function(){
		if($(this).val() != '-1'){
			montaGridReuniaoGeral();
			$('#gridReuniaoGeral').show();
		} else {
			$('#gridReuniaoGeral').hide();
		}
	});
});

function salvarDadosReuniaoGeral()
{
	if(validaForm('#reuniaogeral')){
        
        $.ajax({
            type	: "POST",
            url		: systemName+"/reuniao-geral/salvar-dados-reuniao-geral",
            data	: $('#reuniao-geral :input').serialize(),
            success	: function(retorno){
                cancelarReuniaoGeral();
                montaGridReuniaoGeral();
                alertMsg(retorno);
            }
        });
    }
}

function alterarReuniaoGeral()
{
	if(validaForm('#reuniaogeral')){
        $.ajax({
            type	: "POST",
            url		: systemName+"/reuniao-geral/alterar-reuniao-geral",
            data	: $('#reuniao-geral :input').serialize(),
            success	: function(retorno){
                cancelarReuniaoGeral();
                montaGridReuniaoGeral();
                alertMsg(retorno);
            }
        });
    }
}

function excluirReuniaoGeral(cd_reuniao_geral)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/reuniao-geral/excluir-reuniao-geral",
			data	: "cd_reuniao_geral="+cd_reuniao_geral,
			success	: function(retorno){
				alertMsg(retorno);
				montaGridReuniaoGeral();
			}
		});
	});
}

function cancelarReuniaoGeral()
{
	$('#adicionarReuniaoGeral'	).show();
	$('#alterarReuniaoGeral'	).hide();
	$('#cancelarReuniaoGeral'	).hide();
	
	$('#dt_reuniao'			).val("");
	$('#tx_local_reuniao'	).val("");
	$('#tx_pauta'			).val("");
	$('#tx_participantes'	).val("");
	$('#tx_ata'				).wysiwyg('clear');
}

function montaGridReuniaoGeral()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/reuniao-geral/grid-reuniao-geral",
		data	: "cd_objeto="+$("#cd_objeto_reuniao").val(),
		success	: function(retorno){
			$('#gridReuniaoGeral').html(retorno);
		}
	});
}

function recuperaReuniaoGeral(cd_reuniao_geral)
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/reuniao-geral/recupera-reuniao-geral",
		data	: "cd_reuniao_geral="+cd_reuniao_geral,
		dataType: 'json',
		success	: function(retorno){
			$('#adicionarReuniaoGeral'	).hide();
			$('#alterarReuniaoGeral'	).show();
			$('#cancelarReuniaoGeral'	).show();
			
			$('#cd_reuniao_geral'	).val(retorno[0]['cd_reuniao_geral']);
			$('#cd_objeto_reuniao'	).val(retorno[0]['cd_objeto']);
			$('#dt_reuniao'			).val(retorno[0]['dt_reuniao']);
			$('#tx_local_reuniao'	).val(retorno[0]['tx_local_reuniao']);
			$('#tx_pauta'			).val(retorno[0]['tx_pauta']);
			$('#tx_participantes'	).val(retorno[0]['tx_participantes']);
			$('#tx_ata'				).wysiwyg('value',retorno[0]['tx_ata']);
		}
	});
}

function validaReuniaoGeral()
{
    var msg = i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO;

	if($('#cd_objeto_reuniao').val() == "0"){
		showToolTip(msg,$('#cd_objeto_reuniao'));
		$('#cd_objeto_reuniao').focus();
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