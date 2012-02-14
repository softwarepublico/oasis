$(document).ready(function(){

	$('#bt_excluir_contrato' ).hide();
	
	$("#cd_empresa_contrato").change(function(){
		if($(this).val() != 0){
			getPreposto();
		}
	});
	
	$("#bt_excluir_contrato").click(function() {
		excluirContrato();
	});
	// pega evento no onclick do botao
	$("#submitbuttonContrato").click(function(){
		salvarContrato();
	});

	$('#bt_cancelar_contrato').click(function(){
		$('#form_contrato :input'		).val('');
		$('#cd_contato_empresa_contrato').empty();
		$('#bt_excluir_contrato'		).hide();
		$('#li-contrato'				).hide();
		$("#tx_objeto"					).wysiwyg('value','');
		$('#container_painel_contrato'	).triggerTab(1);
		$('.lb_combo_sigla_metrica'		).removeClass('required');
	});

	$('#div_tx_num_contrato').append('<span class="float-r" style="color: red; margin-top: 6px; margin-right: 7px;">Ex.: 00/2009</span>');

});

function getPreposto(funcao)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/contrato/get-preposto-empresa",
		data	: "cd_empresa="+$("#cd_empresa_contrato").val(),
		success	: function(retorno){
			$("#cd_contato_empresa_contrato").html(retorno);
			if (funcao != "undefined") {
				eval(funcao);
			}
		}
	});
}

function salvarContrato()
{
    if(!validaForm("#form_contrato")){return false;}

	var tx_numero_contrato = $("#tx_numero_contrato").val();

	if( tx_numero_contrato.indexOf("/") <= 0 ){
		alertMsg(i18n.L_VIEW_SCRIPT_VERICAR_NR_CONTRATO);
		return false;
	}

    $.ajax({
		type    : "POST",
		url     : systemName+"/contrato/salvar",
		data    : $('#form_contrato :input').serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type'],'limpaFormContrato()');
				$("#config_hidden_metrica_contrato").val('N');
				$("#config_hidden_projeto_contrato").val('N');
			}
		}
    });
}

function limpaFormContrato(){
    $('#form_contrato :input')
		.not('#st_situacao_lista_contrato')
		.not('#cd_empresa_lista_contrato')
		.not('#novo_contrato')
		.not('#bt_excluir_contrato')
		.not('#submitbuttonContrato')
		.not('#bt_cancelar_contrato')
		.not('#st_contrato')
		.not('#st_aditivo')
		.val('');

	$("#tx_objeto"	).wysiwyg('value','');
		
	$('#st_contrato').removeAttr('checked');
	$('#st_aditivo'	).removeAttr('checked');

    $('#submitbuttonContrato'	  ).show();
    $('#bt_excluir_contrato'	  ).hide();
    $('#li-contrato'			  ).hide();
    $('#container_painel_contrato').triggerTab(1);
	
    montaGridContrato();
	comboContratoAtivo('cd_contrato_objeto_contrato');
	comboContratoAtivoObjeto('cd_contrato_penalidade');
	comboGroupContrato('P', 'cd_contrato_projeto_previsto');
}

function excluirContrato()
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/contrato/excluir",
			data	: "cd_contrato="+$("#cd_contrato").val(),
			success	: function(retorno){
				alertMsg(retorno,'',"limpaFormContrato()");
				$("#config_hidden_metrica_contrato").val('N');
				$("#config_hidden_projeto_contrato").val('N');
			}
		});
	});
}