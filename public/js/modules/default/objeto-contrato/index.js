$(document).ready(function(){

	$('#bt_excluir_objeto_contrato').hide();
	$('#div_porcentagem_orcamento' ).hide();
	$('#div_justificativa'         ).hide();

	$("#bt_excluir_objeto_contrato").click(function() {
		excluirObjetoContrato();
	});
	
	$("#submitbuttonObjetoContrato").click(function(){
		salvarObjetoContrato();
 	});

	$("#st_parcela_orcamento").click(function(){
		if ($("input[id=st_parcela_orcamento]:checked").val() == 'S') {
			$('#div_porcentagem_orcamento').show();
		}else{
			$('#div_porcentagem_orcamento').hide();
			$('#ni_porcentagem_parc_orcamento').val('');
		}
 	});

	$("#st_necessita_justificativa").click(function(){
		if ($("input[id=st_necessita_justificativa]:checked").val() == 'S') {
			$('#div_justificativa').show();
		}else{
			$('#div_justificativa').hide();
			$('#ni_minutos_justificativa').val('');
			$('#tx_hora_inicio_just_periodo_1').val('');
			$('#tx_hora_fim_just_periodo_1').val('');
			$('#tx_hora_inicio_just_periodo_2').val('');
			$('#tx_hora_fim_just_periodo_2').val('');
		}
 	});

	$('#cd_contrato_objeto_contrato').change(function(){
		if($('#cd_contrato_objeto_contrato').val() != "-1"){
			montaDadosObjetoContrato();
		} else {
			limpaDadosObjetoContrato();
		}
	});

});

function montaDadosObjetoContrato()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/objeto-contrato/recupera-objeto-contrato",
		data	: "cd_contrato="+$("#cd_contrato_objeto_contrato").val(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno.length > 0){
				if (retorno[0]['cd_objeto'] == '') {
					$('#submitbuttonObjetoContrato').show();
					$('#bt_excluir_objeto_contrato').hide();
				} else {
					$('#submitbuttonObjetoContrato').show();
					$('#bt_excluir_objeto_contrato').show();
					$('#cd_objeto'				   ).val(retorno[0]['cd_objeto']);
					$('#cd_area_atuacao_ti_objeto_contrato').val(retorno[0]['cd_area_atuacao_ti']);
				}

				$('#tx_objeto_contrato'			   ).val(retorno[0]['tx_objeto']);

				switch (retorno[0]['st_objeto_contrato']) {
					case 'P':
						$('#st_objeto_contrato-P').attr('checked','checked');
						$('#st_objeto_contrato-D').removeAttr('checked');
						$('#st_objeto_contrato-S').removeAttr('checked');

						break;
					case 'D':
						$('#st_objeto_contrato-D').attr('checked','checked');
						$('#st_objeto_contrato-P').removeAttr('checked');
						$('#st_objeto_contrato-S').removeAttr('checked');

						break;
					case 'S':
						$('#st_objeto_contrato-S').attr('checked','checked');
						$('#st_objeto_contrato-P').removeAttr('checked');
						$('#st_objeto_contrato-D').removeAttr('checked');

						break;
				}

				if (retorno[0]['st_parcela_orcamento'] == 'S') {
					$('#st_parcela_orcamento').attr('checked','checked');
					$('#ni_porcentagem_parc_orcamento').val(retorno[0]['ni_porcentagem_parc_orcamento']);
					$('#div_porcentagem_orcamento').show();
				}else{
					$('#st_parcela_orcamento').removeAttr('checked');
					$('#ni_porcentagem_parc_orcamento').val('');
					$('#div_porcentagem_orcamento').hide();
				}

				if (retorno[0]['st_necessita_justificativa'] == 'S') {
					$('#st_necessita_justificativa').attr('checked','checked');
					$('#ni_minutos_justificativa').val(retorno[0]['ni_minutos_justificativa']);
					$('#tx_hora_inicio_just_periodo_1').val(retorno[0]['tx_hora_inicio_just_periodo_1']);
					$('#tx_hora_fim_just_periodo_1').val(retorno[0]['tx_hora_fim_just_periodo_1']);
					$('#tx_hora_inicio_just_periodo_2').val(retorno[0]['tx_hora_inicio_just_periodo_2']);
					$('#tx_hora_fim_just_periodo_2').val(retorno[0]['tx_hora_fim_just_periodo_2']);
					$('#div_justificativa').show();
				}else{
					$('#st_necessita_justificativa').removeAttr('checked');
					$('#ni_minutos_justificativa').val('');
					$('#tx_hora_inicio_just_periodo_1').val('');
					$('#tx_hora_fim_just_periodo_1').val('');
					$('#tx_hora_inicio_just_periodo_2').val('');
					$('#tx_hora_fim_just_periodo_2').val('');
					$('#div_justificativa').hide();
				}

				$('#bt_excluir_objeto_contrato'	   ).show();
			} else {
				limpaDadosObjetoContrato();
			}
		}
	});
}

function limpaDadosObjetoContrato()
{

    $('#cd_area_atuacao_ti_objeto_contrato').val("");

    $('#cd_objeto'					).val("");
	$('#tx_objeto_contrato'			).val("");
	$('#st_objeto_contrato-P'		).removeAttr('checked');
	$('#st_objeto_contrato-D'		).removeAttr('checked');
	$('#st_objeto_contrato-S'		).removeAttr('checked');
	$('#bt_excluir_objeto_contrato'	).hide();
	
	comboObjetoContratoAtivo('cd_objeto_nivel_servico','D');
	comboContratoAtivoObjeto('cd_contrato_penalidade');
	comboGroupContrato('P', 'cd_contrato_projeto_previsto');
	$("#config_hidden_metrica_contrato"			).val('N');
	$("#config_hidden_projeto_contrato"			).val('N');
	$("#config_hidden_perfil_papel_profissional").val('N');
	$("#config_hidden_perfil_objeto"			).val('N');
	$("#config_hidden_etapa_atividade"			).val('N');
	$("#config_hidden_item_questao_risco"		).val('N');

	$('#st_parcela_orcamento').removeAttr('checked');
	$('#ni_porcentagem_parc_orcamento').val('');
	$('#div_porcentagem_orcamento').hide();

	$('#st_necessita_justificativa').removeAttr('checked');
	$('#ni_minutos_justificativa').val('');
	$('#tx_hora_inicio_just_periodo_1').val('');
	$('#tx_hora_fim_just_periodo_1').val('');
	$('#tx_hora_inicio_just_periodo_2').val('');
	$('#tx_hora_fim_just_periodo_2').val('');
	$('#div_justificativa').hide();
}

function salvarObjetoContrato()
{
	if(!validaForm("#objeto_contrato")){return false;}

	$.ajax({
		type    : "POST",
		url     : systemName+"/objeto-contrato/salvar",
		data    : $('#objeto_contrato :input').serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type'],'limpaDadosObjetoContrato()');
				$('#cd_contrato_objeto_contrato').val(-1);
			}
		}
	});
}

function excluirObjetoContrato()
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
         $.ajax({
			type	: "POST",
			url		: systemName+"/objeto-contrato/excluir",
			data	: "cd_objeto="+$("#cd_objeto").val(),
			success	: function(retorno){
				alertMsg(retorno,'',"limpaDadosObjetoContrato()");
				$('#cd_contrato_objeto_contrato').val(-1);
			}
        });
    });
}