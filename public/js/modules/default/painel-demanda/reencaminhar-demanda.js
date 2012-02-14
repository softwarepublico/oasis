$(document).ready(function() {
	atualizaTela1();
	
	$('#bt_designar_profissional_reencaminhar_demanda').click(function () {
		verificaNivelServico1();
	});

	$('#bt_cancelar_reencaminhar_demanda').click(function () {
		window.location.href = systemName+"/painel-demanda\#demanda-executada";
	});
	
	$('#bt_cancelar_demanda').click(function(){
		atualizaTela1();
	});
});

function salvarDemanda1()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/demanda/salvar-designacao1",
		data	: $('#formReencaminharDemanda :input').serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['cd_demanda'] != null){
				alertMsg(retorno['msg'],'',function(){redirecionaDemanda1(retorno['cd_demanda'])});
			} else {
				alertMsg(retorno['msg'],'',"atualizaTela1()");
			}
		}
	});
}

function redirecionaDemanda1(cd_demanda)
{
    
    window.location.href = window.location //+"/cd_demanda/"+cd_demanda;
}

function montaComboProfissional1()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/encaminhar-solicitacao-tipo-demanda/monta-combo-profissional",
		data	: "cd_demanda="+$('#cd_demanda').val()
				 +"&cd_objeto="+$('#cd_objeto').val(),
		success	: function(retorno){
			$('#cd_profissional').html(retorno);
		}
	});
}

function montaGridReencaminharDemandaProfissional1()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/reencaminhar-solicitacao-tipo-demanda/grid-reencaminhar-solicitacao-demanda",
		data	: "cd_demanda="+$('#cd_demanda').val(),
		success	: function(retorno){
			$('#reencaminharProfissionaisAlocados').html(retorno);
		}
	});	
}

function atualizaTela1()
{
	$('#cd_profissional_hidden'	).val("");
	$('#cd_nivel_servico_hidden').val("");
	$('#tx_obs_nivel_servico'	).val("");
	$('#bt_cancelar_demanda'	).attr('style',false);
	$('#bt_cancelar_demanda'	).hide();
	$('#observacaoProfissional'	).hide();
	$('#bt_designar_profissional_reencaminhar_demanda').show();
	$('#descNivelServico'		).html("");
	$('#descProfissional'		).html("");
	$('#comboNivelServico'		).show();
	$('#comboProfissional'		).show();
	montaComboProfissional1();
	montaGridReencaminharDemandaProfissional1();
}

function confirmaDesignacaoProfissional(cd_demanda, cd_profissional, cd_nivel_servico)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/encaminhar-solicitacao-tipo-demanda/confirma-demanda-profissional",
		data	: "cd_demanda="+cd_demanda+
				  "&cd_profissional="+cd_profissional+
				  "&cd_nivel_servico="+cd_nivel_servico,
		success	: function(retorno){
			alertMsg(retorno);
			$('#bt_confirmacao_profissional_'+cd_profissional).hide();
			$('#imgExcluir_'+cd_profissional).hide();
			confirmaReencaminhamentoProfissional(cd_demanda, cd_profissional, cd_nivel_servico);
		}
	});
}

function confirmaReencaminhamentoProfissional(cd_demanda, cd_profissional, cd_nivel_servico)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/reencaminhar-solicitacao-tipo-demanda/reencaminhar-demanda-profissional",
		data	: "cd_demanda="+cd_demanda+
				  "&cd_profissional="+cd_profissional+
				  "&cd_nivel_servico="+cd_nivel_servico,
		success	: function(retorno){
			alertMsg(retorno);
			$('#bt_reencaminhar_profissional_'+cd_profissional).hide();
		}
	});	
}

function recuperarDesignacaoProfissional(cd_demanda, cd_profissional, cd_nivel_servico)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/encaminhar-solicitacao-tipo-demanda/recupera-designacao-profissional",
		data	: "cd_demanda="+cd_demanda+
				  "&cd_profissional="+cd_profissional+
				  "&cd_nivel_servico="+cd_nivel_servico,
		dataType: 'json',
		success	: function(retorno){

			if(retorno['dataDesignacao'] != null){
				$('#bt_designar_profissional_reencaminhar_demanda').hide();
				$('#bt_cancelar_demanda'	).attr('style','margin-right: 55px;');
				$('#tx_obs_nivel_servico'	).val(retorno['strObservacao']);
			} else {
				$('#observacaoProfissional'	).show();
				$('#observacaoAnterior'		).val(retorno['strObservacao']);
			}
			$('#comboNivelServico'		).hide();
			$('#comboProfissional'		).hide();
			$('#descNivelServico'		).html(retorno['nomeNivelServico']);
			$('#descProfissional'		).html(retorno['nomeProfissional']);
			$('#bt_cancelar_demanda'	).show();
			$('#cd_profissional_hidden'	).val(cd_profissional);
			$('#cd_nivel_servico_hidden').val(cd_nivel_servico);
		}
	});
}

function excluirProfissionalDesignado(cd_demanda, cd_profissional, cd_nivel_servico)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/encaminhar-solicitacao-tipo-demanda/excluir-profissional-designado",
		data	: "cd_demanda="+cd_demanda
				 +"&cd_profissional="+cd_profissional
				 +"&cd_nivel_servico="+cd_nivel_servico,
		success	: function(retorno){
			alertMsg(retorno,'','atualizaTela1()');
		}
	});
}

function verificaNivelServico1()
{
	if( !validaForm() ){ return false; }
	$.ajax({
		type	: "POST",
		url		: systemName+"/reencaminhar-solicitacao-tipo-demanda/verifica-nivel-servico-associado",
		data	: $('#formReencaminharDemanda :input').serialize(),
		success	: function(retorno){
			if(retorno == "S"){
				ajaxLoaded();
				if(confirm(i18n.L_VIEW_SCRIPT_NIVEL_SERVICO_ASSOCIADO_PROFISSIONAL_CONTINUAR)){
					salvarDemanda1();
				}
			} else if(retorno != "S" && retorno != "N"){
				salvarDemanda1();
			} else {
				alertMsg(i18n.L_VIEW_SCRIPT_PROFISSIONAL_JA_ASSOCIADO_NIVEL_SERVICO);
			}
		}
	});
}