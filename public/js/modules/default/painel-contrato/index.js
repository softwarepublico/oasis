$(document).ready(function(){

	$('#novo_contrato').click(function(){
		$('#li-contrato' ).show();
		$('#aba_contrato').show();
		$('#container_painel_contrato').triggerTab(2);
		comboEmpresa('cd_empresa_contrato');
	});
	
	$('#st_situacao_lista_contrato').change(function(){
		montaGridContrato();
	});
	
	$('#cd_empresa_lista_contrato').change(function(){
		montaGridContrato();
	});

	$('.lb_combo_sigla_metrica_unidade_prevista_contrato'		 ).html('');
	$('.lb_combo_sigla_metrica_unidade_prevista_projeto_previsto').html('');

});

function montaGridContrato()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/painel-contrato/grid-painel-contrato",
		data	: "cd_empresa="+$("#cd_empresa_lista_contrato").val()
		+"&st_situacao="+$("#st_situacao_lista_contrato").val(),
		success	: function(retorno){
			// atualiza a grid
			$("#grid_painel_contrato").html(retorno);
		}
	});
}

function recuperaContrato(cd_contrato)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/contrato/recupera-contrato",
		data	: "cd_contrato="+cd_contrato,
		dataType:'json',
		success	: function(retorno){
			$('#cd_contrato'				).val(retorno[0]['cd_contrato']);
			
			comboEmpresa('cd_empresa_contrato',
				"$('#cd_empresa_contrato').val("+retorno[0]['cd_empresa']+");getPreposto('$(\"#cd_contato_empresa_contrato\").val("+retorno[0]['cd_contato_empresa']+")')"
				);

			$('#tx_numero_contrato'			).val(retorno[0]['tx_numero_contrato']);
			if (retorno[0]['st_contrato'] == 'I') {
				$('#st_contrato').attr('checked', 'checked');
			} else {
				$('#st_contrato').removeAttr('checked');
			}
			$('#dt_inicio_contrato'			).val(retorno[0]['dt_inicio_contrato']);
			$('#dt_fim_contrato'			).val(retorno[0]['dt_fim_contrato']);
			$('#tx_numero_processo'			).val(retorno[0]['tx_numero_processo']);
			if (retorno[0]['st_aditivo'] == 'S') {
				$('#st_aditivo').attr('checked', 'checked');
			} else {
				$('#st_aditivo').removeAttr('checked');
			}
			$('#tx_cpf_gestor'				).val(retorno[0]['tx_cpf_gestor']);
			$('#nf_valor_contrato'			).val(converteFloatMoeda(retorno[0]['nf_valor_contrato']));
			$('#tx_gestor_contrato'			).val(retorno[0]['tx_gestor_contrato']);
			$('#tx_fone_gestor_contrato'	).val(retorno[0]['tx_fone_gestor_contrato']);
			$("#tx_objeto"					).wysiwyg('value',retorno[0]['tx_objeto']);
			$('#tx_obs_contrato'			).val(retorno[0]['tx_obs_contrato']);
			$('#tx_localizacao_arquivo'		).val(retorno[0]['tx_localizacao_arquivo']);
			$('#tx_co_gestor'				).val(retorno[0]['tx_co_gestor']);
			$('#tx_cpf_co_gestor'			).val(retorno[0]['tx_cpf_co_gestor']);
			$('#tx_fone_co_gestor_contrato'	).val(retorno[0]['tx_fone_co_gestor_contrato']);
			$('#nf_valor_unitario_hora'		).val(converteFloatMoeda(retorno[0]['nf_valor_unitario_hora']));
			$('#ni_horas_previstas'			).val(retorno[0]['ni_horas_previstas']);

			if(retorno[0]['ni_horas_previstas']){
				$('.lb_combo_sigla_metrica_unidade_prevista_contrato').addClass('required');
			}else{
				$('.lb_combo_sigla_metrica_unidade_prevista_contrato').removeClass('required');
			}
			(retorno[0]['cd_definicao_metrica']) ? $('#cd_metrica_unidade_prevista_contrato').val(retorno[0]['cd_definicao_metrica']) : $('#cd_metrica_unidade_prevista_contrato').val('');
			
			//utilizados se estiver na tab
			$('#submitbuttonContrato').show();
			$('#bt_excluir_contrato' ).show();
			$('#bt_cancelar_contrato').show();

			$('#li-contrato'		 ).show();
			$('#aba_contrato'		 ).show();
			$('#container_painel_contrato').triggerTab(2);
		}
	});
}

function validaConteudoUnidadeMetricaPrevistaContrato(valueInput){

	if(valueInput != '' && valueInput != undefined ){
		$('.lb_combo_sigla_metrica_unidade_prevista_contrato').addClass('required');
	}else{
		$('.lb_combo_sigla_metrica_unidade_prevista_contrato').removeClass('required');
		$('#cd_metrica_unidade_prevista_contrato'			 ).val('');
	}
}