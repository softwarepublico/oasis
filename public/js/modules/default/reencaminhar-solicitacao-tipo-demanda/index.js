$(document).ready(function() {
	atualizaTela();
	
	$('#bt_designar_profissional_reencaminhar_solicitacao_tipo_demanda').click(function () {
		verificaNivelServico();
	});

	$('#bt_cancelar_reencaminhar_demanda').click(function () {
		window.location.href = systemName+"/solicitacao-tipo-demanda";
        $('#cd_status_atendimento').val("0");
	});
	
	$('#bt_cancelar_solicitacao_tipo_demanda').click(function(){
		atualizaTela();
	});
	
	$('#bt_cancelar_encaminhar_solicitacao_tipo_demanda').click(function(){
		window.location.href = systemName+"/solicitacao-tipo-demanda";
	});

    $('#btn_alterar_prioridade_demanda').click(function(){
        salvarDemanda();
    });

    $('#bt_salvar_msg_reencaminhamento_demanda').click(function(){
        salvarObservacaoReencaminhamento();
    });
});

function salvarDemanda()
{
    if($('#cd_status_atendimento').val() == '0'){
        showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO, $('#cd_status_atendimento'));
        return false;
    }
	$.ajax({
		type	: "POST",
		url		: systemName+"/demanda/salvar-demanda",
		data	: {
            "cd_demanda"        : $('#cd_demanda').val(),
            "cd_objeto"         : $('#cd_objeto').val(),
            "ni_ano_solicitacao": $('#ni_ano_solicitacao').val(),
            "ni_solicitacao"    : $('#ni_solicitacao').val(),
            "tx_demanda"        : $('#tx_demanda').val(),
            "cd_status_atendimento" : $('#cd_status_atendimento').val(),
            "tx_solicitante_demanda": $('#tx_solicitante_demanda').val(),
            "cd_unidade"            : $('#cd_unidade').val()
            },
		dataType: 'json',
		success	: function(retorno){
            if(retorno['error'] == true ){
                alertMsg(retorno['msg'], retorno['typeMsg']);
            }else{
                alertMsg(retorno['msg'], retorno['typeMsg']);

                //quando for uma nova demanda retornará o seu
                //código para as posteriores designações
                if(retorno['cd_demanda'] != ''){
                    $('#cd_demanda').val(retorno['cd_demanda']);
                }
            }
		}
	});
}

function verificaNivelServico()
{
	if( ($('#cd_nivel_servico').val() == '0') && ($('#comboNivelServico').attr('style') !== 'display: none;')){
        showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_nivel_servico'));
		var t = setTimeout('removeTollTip()',5000);
        return false;
    }
	if( ($('#cd_profissional').val() == '-1') && ($('#comboProfissional').attr('style') !== 'display: none;')){
        showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_profissional'));
		var t = setTimeout('removeTollTip()',5000);
        return false;
    }
	$.ajax({
		type	: "POST",
		url		: systemName+"/reencaminhar-solicitacao-tipo-demanda/verifica-nivel-servico-associado",
		data	: $('#formReencaminharSolicitacaoTipoDemanda :input').serialize(),
		success	: function(retorno){
			if(retorno == "S"){
				ajaxLoaded();
				if(confirm(i18n.L_VIEW_SCRIPT_NIVEL_SERVICO_ASSOCIADO_PROFISSIONAL_CONTINUAR)){
					salvarSolicitacaoTipoDemanda();
				}
			} else if(retorno != "S" && retorno != "N"){
				salvarSolicitacaoTipoDemanda();
			} else {
				alertMsg(i18n.L_VIEW_SCRIPT_PROFISSIONAL_JA_ASSOCIADO_NIVEL_SERVICO);
			}
		}
	});
}

function salvarSolicitacaoTipoDemanda()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/demanda/salvar-designacao",
		data	: $('#formReencaminharSolicitacaoTipoDemanda :input').serialize(),
		dataType: 'json',
		success	: function(retorno){
            if(retorno['error'] == true ){
                alertMsg(retorno['msg'], retorno['typeMsg']);
            }else{
                alertMsg(retorno['msg'], retorno['typeMsg'], 'atualizaTela()');
            }
		}
	});
}

function salvarObservacaoReencaminhamento()
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/reencaminhar-solicitacao-tipo-demanda/salvar-observacao-reencaminhamento",
		data	: $('#formReencaminharSolicitacaoTipoDemanda :input').serialize(),
		dataType: 'json',
		success	: function(retorno){
            if(retorno['error'] == true ){
                alertMsg(retorno['msg'], retorno['typeMsg']);
            }else{
                alertMsg(retorno['msg'], retorno['typeMsg'], 'atualizaTela()');
            }
		}
	});
}


function montaComboProfissional()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/encaminhar-solicitacao-tipo-demanda/monta-combo-profissional",
		data	: {"cd_demanda":$('#cd_demanda').val(),
				   "cd_objeto" :$('#cd_objeto').val()},
		success: function(retorno){
			$('#cd_profissional').html(retorno);
		}
	});
}

function montaGridReencaminharDemandaProfissional()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/reencaminhar-solicitacao-tipo-demanda/grid-reencaminhar-solicitacao-demanda",
		data	: {"cd_demanda" : $('#cd_demanda').val()},
		success	: function(retorno){
			$('#reencaminharProfissionaisAlocados').html(retorno);
		}
	});	
}

function atualizaTela()
{
	$('#cd_nivel_servico'					   ).val("");
	$('#cd_profissional_hidden'				   ).val("");
	$('#cd_nivel_servico_hidden'			   ).val("");
	$('#tx_obs_nivel_servico'				   ).val("");
	$('#bt_cancelar_solicitacao_tipo_demanda'  ).attr('style',false).hide();
    $('#bt_salvar_msg_reencaminhamento_demanda').hide();
	$('#comboNivelServico, #comboProfissional' ).show();
	$('#bt_designar_profissional_reencaminhar_solicitacao_tipo_demanda').show();
	$('#descNivelServico'					 ).html("");
	$('#descProfissional'					 ).html("");
	
	montaComboProfissional();
	montaGridReencaminharDemandaProfissional();
}

function confirmaDesignacaoProfissional(cd_demanda, cd_profissional, cd_nivel_servico)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/encaminhar-solicitacao-tipo-demanda/confirma-demanda-profissional",
		data	: {"cd_demanda"      :cd_demanda,
                   "cd_profissional" :cd_profissional,
                   "cd_nivel_servico":cd_nivel_servico},
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
		data	: {"cd_demanda"      :cd_demanda,
                   "cd_profissional" :cd_profissional,
                   "cd_nivel_servico":cd_nivel_servico},
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
		data	: {"cd_demanda"      :cd_demanda,
                   "cd_profissional" :cd_profissional,
                   "cd_nivel_servico":cd_nivel_servico},
		dataType: 'json',
		success	: function(retorno){
			if(retorno['dataDesignacao'] != null){
				$('#bt_designar_profissional_reencaminhar_solicitacao_tipo_demanda' ).hide();
				$('#bt_cancelar_solicitacao_tipo_demanda'							).attr('style','margin-right: 55px;');
				$('#tx_obs_nivel_servico'											).val(retorno['strObservacao']);
                $('#bt_salvar_msg_reencaminhamento_demanda').show();
			}
//            else {
//				$('#observacaoAnterior'	   ).html(retorno['strObservacao']);
//				$('#observacaoProfissional').show();
//			}
            $('#tx_obs_nivel_servico'                ).val(retorno['strObservacao']);
			$('#comboNivelServico'					 ).hide();
			$('#comboProfissional'					 ).hide();
			$('#descNivelServico'					 ).html(retorno['nomeNivelServico']);
			$('#descProfissional'					 ).html(retorno['nomeProfissional']);
			$('#bt_cancelar_solicitacao_tipo_demanda').show();
			$('#cd_profissional_hidden'				 ).val(cd_profissional);
			$('#cd_nivel_servico_hidden'			 ).val(cd_nivel_servico);
		}
	});
}

function excluirProfissionalDesignado(cd_demanda, cd_profissional, cd_nivel_servico)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/encaminhar-solicitacao-tipo-demanda/excluir-profissional-designado",
		data	: {"cd_demanda"      :cd_demanda,
			       "cd_profissional" :cd_profissional,
			       "cd_nivel_servico":cd_nivel_servico},
		success: function(retorno){
			alertMsg(retorno,'','atualizaTela()');
		}
	});
}