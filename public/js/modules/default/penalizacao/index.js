$(document).ready(function(){
	$('#cd_objeto_penalizacao').change(function(){
		camposPenalizacao();
	});
	$('#dt_penalizacao_hidden').change(function(){
		camposPenalizacao();
	});
	$('#dt_penalizacao').bind('blur',function(){removeTollTip()});
});

function camposPenalizacao()
{
	if($('#cd_objeto_penalizacao').val() != "-1"){
		mostraObjetoContrato();
		if($('#dt_penalizacao').val() != ""){
			montaFormPenalizacao();
		} else {
			showToolTip(i18n.L_VIEW_SCRIPT_INFORME_DATA,$('#dt_penalizacao'));
		}
	} else {
		$('#divObjetoContrato').hide().html('');
		$('#penalizacaoForm'  ).hide().html('');
	}
}

function mostraObjetoContrato()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/penalizacao/recupera-contrato",
		data	:"cd_objeto="+$('#cd_objeto_penalizacao').val(),
		dataType:"json",
		success	: function(retorno){
			$('#cd_contrato_penalizacao').val(retorno['cd_contrato']);
			$('#contratoObjeto'			).html(retorno['tx_numero_contrato']);
			$('#divObjetoContrato'		).show();
		}
	});
}

function montaFormPenalizacao()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/penalizacao/penalizacao-form",
		data	: "cd_objeto="+$('#cd_objeto_penalizacao').val()
				 +"&dt_penalizacao="+$('#dt_penalizacao').val(),
		success	: function(retorno){
			$('#penalizacaoForm').html(retorno);
			$('#penalizacaoForm').show();
		}
	});
}