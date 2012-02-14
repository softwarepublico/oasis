$(document).ready(function(){
	$('#bt_excluir_penalidade').hide();
	$('#bt_cancelar_penalidade').hide();
    
    $("#cd_contrato_penalidade").change(function() {
		montaGridPenalidade();
	});
    $("#bt_salvar_penalidade").click(function(){
        validaDadosPenalidade();
    });
	$("#bt_excluir_penalidade").click(function() {
        excluirPenalidade();
    });
	$("#bt_cancelar_penalidade").click(function() {
        limpaDadosPenalidade();
    });
});

function validaDadosPenalidade()
{
	var msg = i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO;
	if($('#cd_contrato').val() == "0"){
		showToolTip(msg, $('#cd_contrato_penalidade'));
		$('#cd_contrato').focus();
		return false
	}
	if($('#tx_penalidade').val() == ""){
		showToolTip(msg, $('#tx_penalidade'));
		$('#tx_penalidade').focus();
		return false
	}
	if($('#tx_abreviacao_penalidade').val() == ""){
		showToolTip(msg, $('#tx_abreviacao_penalidade'));
		$('#tx_abreviacao_penalidade').focus();
		return false
	}
	if($('#ni_valor_penalidade').val() == ""){
		showToolTip(msg, $('#ni_valor_penalidade'));
		$('#ni_valor_penalidade').focus();
		return false
	}
	if($('#ni_penalidade').val() == ""){
		showToolTip(msg, $('#ni_penalidade'));
		$('#ni_penalidade').focus();
		return false
	}
	if ($("#cd_penalidade").val() == ""){
		validaNumeroPenalidade();
	}else{
		salvaPenalidade();
	}
}

function salvaPenalidade()
{
	//if(!validaForm("#penalidade")){return false;}
	$.ajax({
		type    : "POST",
		url     : systemName+"/penalidade/salvar",
		data    : $('#penalidade :input').serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type'],'limpaDadosPenalidade()');
			}
		}
	});
}

function validaNumeroPenalidade()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/penalidade/verifica-numero-penalidade",
		data	: "cd_contrato="+$("#cd_contrato_penalidade").val()+
				  "&ni_penalidade="+$("#ni_penalidade").val(),
		success	: function(retorno){
			if(parseInt(retorno) == 2){
			   showToolTip(i18n.L_VIEW_SCRIPT_NR_PENALIDADE_JA_CADASTRADO, $("#ni_penalidade"));
			   return false;
			} else { 
				salvaPenalidade();
			}
		}
	});
}

function montaGridPenalidade(){
	$.ajax({
		type	: "POST",
		url		: systemName+"/penalidade/grid-penalidade",
		data	: "cd_contrato="+$("#cd_contrato_penalidade").val(),
		success	: function(retorno){
			// atualiza a grid
			$("#gridPenalidade").html(retorno);
		}
	});
}

function limpaDadosPenalidade()
{
    $('#penalidade :input').not('#cd_contrato_penalidade, :checkbox').val("");
    $('#penalidade :checkbox').removeAttr('checked');
    $('#bt_excluir_penalidade, #bt_cancelar_penalidade').hide();
	montaGridPenalidade();
}

function recuperaPenalidade(cd_penalidade)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/penalidade/recupera-penalidade",
		data	: "cd_penalidade="+cd_penalidade,
		dataType:'json',
		success	: function(retorno){
			$('#cd_contrato_penalidade'  ).val(retorno[0]['cd_contrato']);
			$('#cd_penalidade'			 ).val(retorno[0]['cd_penalidade']);
			$('#tx_penalidade'			 ).val(retorno[0]['tx_penalidade']);
			$('#tx_abreviacao_penalidade').val(retorno[0]['tx_abreviacao_penalidade']);
			$('#ni_valor_penalidade'	 ).val(converteFloatMoeda(retorno[0]['ni_valor_penalidade']));
			$('#ni_penalidade'			 ).val(retorno[0]['ni_penalidade']);
			if (retorno[0]['st_ocorrencia'] == "S") {
				$('#st_ocorrencia'		 ).attr('checked','checked');
			} else {
				$('#st_ocorrencia'		 ).removeAttr('checked');
			}

			$('#bt_excluir_penalidade'	 ).show();
			$('#bt_cancelar_penalidade'	 ).show();
		}
	});
}

/**
 * excluir penalidade
 */
function excluirPenalidade() {
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
	  $.ajax({
			type	: "POST",
			url		: systemName+"/penalidade/excluir",
			data	: "cd_penalidade="+$("#cd_penalidade").val(),
			success	: function(retorno){
				alertMsg(retorno,'',"limpaDadosPenalidade()");
			}
		});
    });
}