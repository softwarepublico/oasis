$(document).ready(function() {
	$('#cd_projeto_qualidade').change(function(){
		getPropostaQualidade();
        $('#gridGerenciaQualidade').hide();
	});
	$('#cd_proposta_qualidade').change(function(){
		gridGerenciaQualidade();
	});
	$('#btn_salvar_gerencia_qualidade').click(function(){
		salvarGerenciamentoQualidade();
	});
	$('#btn_alterar_gerencia_qualidade').click(function(){
		salvarGerenciamentoQualidade();
	});
	$('#btn_cancelar_gerencia_qualidade').click(function(){
		cancelaGerenciamentoQualidade();
	});
});

/**
 * Configuração da inicialização das informações da tela
 */
function configGerenciaQualidade()
{
	cancelaGerenciamentoQualidade();
	$('#config_hidden_gerencia_qualidade').val('S');
}

/*
 * Função que salva e altera os dados da gerencia de qualidade
 * @author: Wunilberto.Melo
 * @since: 30/06/2009
 */
function salvarGerenciamentoQualidade()
{
    if( !validaForm('#formGerenciaQualidade') ){ return false; }
	$.ajax({
		type	: "POST",
		url		: systemName+"/gerencia-qualidade/salvar",
		data	:  $('#formGerenciaQualidade :input').serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type'],function(){gridGerenciaQualidade();cancelaGerenciamentoQualidade();});
			}
		}
	});
    return true;
}

function excluirGerenciamentoQualidade(cd_gerencia_qualidade)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR,function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/gerencia-qualidade/excluir",
			data	: "cd_projeto="+$('#cd_projeto_qualidade').val()
					 +"&cd_proposta="+$('#cd_proposta_qualidade').val()
					 +"&cd_gerencia_qualidade="+cd_gerencia_qualidade,
			success	: function(retorno){
				alertMsg(retorno,1,function(){gridGerenciaQualidade();cancelaGerenciamentoQualidade();});
			}
		});
	});
}

function cancelaGerenciamentoQualidade()
{
	$('#btn_salvar_gerencia_qualidade').show();
	$('#btn_alterar_gerencia_qualidade, #btn_cancelar_gerencia_qualidade').hide();
	$('#formGerenciaQualidade :input').not('#cd_projeto_qualidade, #cd_proposta_qualidade').val("");
}

function gridGerenciaQualidade()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/gerencia-qualidade/grid-gerencia-qualidade",
		data	: "cd_projeto="+$('#cd_projeto_qualidade').val()
				 +"&cd_proposta="+$('#cd_proposta_qualidade').val(),
		success: function(retorno){
			$('#gridGerenciaQualidade').html(retorno);
			$('#gridGerenciaQualidade').show();
		}
	});
}

/**
 * Recupera os dados da grid para serem alterados
 */
function recuperaGerenciamentoQualidade(cd_gerencia_qualidade)
{
 	$.ajax({
		type	: "POST",
		url		: systemName+"/gerencia-qualidade/recupera-gerenciamento-qualidade",
		data	: "cd_projeto="+$('#cd_projeto_qualidade').val()
				 +"&cd_proposta="+$('#cd_proposta_qualidade').val()
				 +"&cd_gerencia_qualidade="+cd_gerencia_qualidade,
		dataType: 'json',
		success	: function(retorno){
			$('#cd_gerencia_qualidade'        ).val(retorno['cd_gerencia_qualidade']);
			$('#tx_fase_projeto'              ).val(retorno['tx_fase_projeto']);
			$('#dt_auditoria_qualidade'       ).val(retorno['dt_auditoria_qualidade']);
			$('#cd_profissional_qualidade'    ).val(retorno['cd_profissional']);
			$('#btn_salvar_gerencia_qualidade').hide();
			$('#btn_alterar_gerencia_qualidade, #btn_cancelar_gerencia_qualidade').show();
		}
	});
}

function getPropostaQualidade()
{
	$.ajax({
		type: "POST",
		url: systemName+"/proposta/pesquisa-proposta",
		data: "cd_projeto="+$("#cd_projeto_qualidade").val(),
		success: function(retorno){
			$("#cd_proposta_qualidade").html(retorno);
		}
	});
}