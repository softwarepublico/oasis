$(document).ready(function() {

	atualizaTela();
	$('#bt_cancelar_solicitacao_tipo_demanda').hide();
	
	$('#bt_designar_profissional_encaminhar_solicitacao_tipo_demanda').click(function () {
		salvarSolicitacaoTipoDemanda();
	});
	
	$('#bt_cancelar_solicitacao_tipo_demanda').click(function(){
		atualizaTela();
	});	
	
	$('#bt_cancelar_encaminhar_demanda').click(function () {
		window.location.href = systemName+"/painel-demanda";
	});	
	
	$('#tx_demanda_editor').blur(function () {
		window.location.href = systemName+"/painel-demanda";
	});	
	
	// pega evento no onclick do botao
	$("#nova_demanda").click(function(){
		window.location.href = systemName+"/demanda";
	});

    $('#btn_salvar_demanda').click(function(){
        salvarDemanda();
    });
    $('#btn_alterar_demanda').click(function(){
        salvarDemanda();
    });
    $('#comboProfissional').change(function(){
        qtdDemandasProfissional();
    });
});

function salvarDemanda()
{
    if($('#tx_solicitante_demanda').val() == ""){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#tx_solicitante_demanda'));
		var t = setTimeout('removeTollTip()',5000);
		return false
	}
	if($('#cd_unidade').val() == "0"){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_unidade'));
		var t = setTimeout('removeTollTip()',5000);
		return false
	}
	if($('#tx_demanda').wysiwyg('getContent').val() == ""){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#tx_demanda_editor'));
		var t = setTimeout('removeTollTip()',5000);
		return false
	}
	if($('#cd_status_atendimento').val() == "0"){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_status_atendimento'));
		var t = setTimeout('removeTollTip()',5000);
		return false
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
            "cd_status_atendimento"     : $('#cd_status_atendimento').val(),
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
                    $('#btn_salvar_demanda' ).hide();
                    $('#btn_alterar_demanda').show();
                }

            }
		}
	});
}

function salvarSolicitacaoTipoDemanda()
{
    if($('#cd_demanda').val() == ''){
        alertMsg(i18n.L_VIEW_SCRIPT_CRIACAO_DEMANDA_OBRIGATORIO_PARA_DESIGNACAO_PROFISSIONAL);
        return false;
    }

	if($('#cd_nivel_servico').val() == "0" && ($('#comboNivelServico').attr('style') !== 'display: none;')){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_nivel_servico'));
		var t = setTimeout('removeTollTip()',5000);
		return false
	}
	if($('#cd_profissional').val() == "-1" && ($('#comboProfissional').attr('style') !== 'display: none;')){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_profissional'));
		var t = setTimeout('removeTollTip()',5000);
		return false
	}

    $.ajax({
		type	: "POST",
		url		: systemName+"/demanda/salvar-designacao",
		data	: $('#formEncaminharSolicitacaoTipoDemanda :input').serialize(),
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

function montaComboNivelServico()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/encaminhar-solicitacao-tipo-demanda/monta-combo-nivel-servico",
		data	: "cd_objeto="+$('#cd_objeto').val()+
		          "&cd_demanda="+$('#cd_demanda').val(),
		success	: function(retorno){
			$('#cd_nivel_servico').html(retorno);
		}
	});	
}

function montaComboProfissional()
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

function montaGridDemandaProfissional()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/encaminhar-solicitacao-tipo-demanda/grid-encaminhar-solicitacao-demanda",
		data	: "cd_demanda="+$('#cd_demanda').val(),
		success	: function(retorno){
			$('#profissionaisAlocados').html(retorno);
		}
	});	
}

function atualizaTela()
{
	$('#cd_profissional_hidden' ).val("");
	$('#cd_nivel_servico_hidden').val("");
	$('#tx_obs_nivel_servico'   ).val("");
	$('#bt_cancelar_solicitacao_tipo_demanda').attr('style',false);
	$('#bt_cancelar_solicitacao_tipo_demanda').hide();
	$('#bt_designar_profissional_encaminhar_solicitacao_tipo_demanda').show();
	$('#descNivelServico' ).html("");
	$('#descProfissional' ).html("");
	$('#comboNivelServico').show();
	$('#comboProfissional').show();
	montaComboNivelServico();
	montaComboProfissional();
	montaGridDemandaProfissional();

    if($('#cd_demanda').val() == ''){
        $('#btn_salvar_demanda').show();
    }else{
        $('#btn_alterar_demanda').show();
    }

}

function excluirProfissionalDesignado(cd_demanda, cd_profissional, cd_nivel_servico)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR,function(){
        $.ajax({
            type	: "POST",
            url		: systemName+"/encaminhar-solicitacao-tipo-demanda/excluir-profissional-designado",
            data	: "cd_demanda="+cd_demanda
                     +"&cd_profissional="+cd_profissional
                     +"&cd_nivel_servico="+cd_nivel_servico,
            success	: function(retorno){
                alertMsg(retorno);
                montaComboNivelServico();
                montaGridDemandaProfissional();
            }
        });
    });
}

function confirmaDesignacaoProfissional(cd_demanda, cd_profissional, cd_nivel_servico)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/encaminhar-solicitacao-tipo-demanda/confirma-demanda-profissional",
		data	: "cd_demanda="+cd_demanda+"&cd_profissional="+cd_profissional+"&cd_nivel_servico="+cd_nivel_servico,
		success	: function(retorno){
			alertMsg(retorno);
			$('#bt_confirmacao_profissional_'+cd_profissional).hide();
			$('#imgExcluir_'+cd_profissional).hide();
		}
	});
}

function recuperarDesignacaoProfissional(cd_demanda, cd_profissional, cd_nivel_servico)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/encaminhar-solicitacao-tipo-demanda/recupera-designacao-profissional",
		data	: "cd_demanda="+cd_demanda+"&cd_profissional="+cd_profissional+"&cd_nivel_servico="+cd_nivel_servico,
		dataType: 'json',
		success	: function(retorno){
			if(retorno['dataDesignacao'] != null){
				$('#bt_designar_profissional_encaminhar_solicitacao_tipo_demanda').hide();
				$('#bt_cancelar_solicitacao_tipo_demanda').attr('style','margin-right: 55px;');
			}
		
			$('#comboNivelServico').hide();
			$('#comboProfissional').hide();
			$('#descNivelServico' ).html(retorno['nomeNivelServico']);
			$('#descProfissional' ).html(retorno['nomeProfissional']);
			$('#tx_obs_nivel_servico'                ).val(retorno['strObservacao']);
			$('#bt_cancelar_solicitacao_tipo_demanda').show();
			$('#cd_profissional_hidden'              ).val(cd_profissional);
			$('#cd_nivel_servico_hidden'             ).val(cd_nivel_servico);
		}
	});	
}

 function qtdDemandasProfissional()
    {
        
       cd_profissional = $('#cd_profissional').val();
      
       $.ajax({
            type	: "POST",
            url		: systemName+"/demanda/qtd-demanda-profissional",
            data	: "&cd_profissional="+cd_profissional,
     		dataType: 'json',
            success	: function(retorno){
                $('#demanda_aberta').text(retorno['demandaAberta']);
                $('#demanda_fechada').text(retorno['demandaFechada']);
            }
        }); 
    }