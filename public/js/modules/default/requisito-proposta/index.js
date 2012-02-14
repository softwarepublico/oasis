$(document).ready(function() {
	$('#container-requisitos').show().tabs();
	$('#container-requisitos').triggerTab(1);
	
	$('#config_hidden_requisito_proposta').val('N');
	
	//pega o click do botão novo requisito
	$("#btn_novo_requisito").click(function() {
		habilitaAbaRequisito();
		limparInputsRequisito();
	});

	//pega o click do botão salvar requisito
	$("#btn_salvar_requisito").click(function() {
		salvarNovoRequisito();
	});

	//pega o click do botão alterar requisito
	$("#btn_alterar_requisito").click(function() {
		alterarRequisito();
	});
	
	//pega o click do botão cancelar requisito
	$("#btn_cancelar_requisito").click(function() {
		desabilitaAbaRequisito();
	});
});

/**
 * Configuração da inicialização dos dados da tela
 */
function configRequisitoProposta()
{
	montaGridRequisitosProposta();
	montaGridFechamentoVersaoRequisito();
	$('#config_hidden_requisito_proposta').val('S');
}

function montaGridRequisitosProposta()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/requisito-proposta/grid-requisito-proposta",
		data	: "cd_projeto="+$("#cd_projeto").val(),
		success	: function(retorno){
			// atualiza a grid
			$("#gridRequisitosProposta").html(retorno);
		}
	});
}

function salvarNovoRequisito()
{
	if( !validaForm('#div_novo_requisito') ){return false;}
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/requisito-proposta/salvar-novo-requisito",
		data	: $("#div_novo_requisito :input").serialize()+"&cd_projeto="+$("#cd_projeto").val(),
		success	: function(retorno){
			alertMsg(retorno);
			montaGridRequisitosProposta();
			montaGridFechamentoVersaoRequisito();
			limparInputsRequisito();
			montaComboRequistiosDependencia();
		}
	});
}

function alterarRequisito()
{
	if( !validaForm('#div_novo_requisito') ){return false;}
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/requisito-proposta/alterar-requisito",
		data	: $("#div_novo_requisito :input").serialize()+
				  "&cd_projeto="+$("#cd_projeto").val(),
		success	: function(retorno){
			alertMsg(retorno);
			montaGridRequisitosProposta();
			limparInputsRequisito();
			montaComboRequistiosDependencia();
			desabilitaAbaRequisito();
		}
	});
}

function recuperaDadosDoRequisito( cd_requisito, ni_versao_requisito )
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/requisito-proposta/recupera-dados-requisito",
		data	: {"cd_requisito"        : cd_requisito,
                   "ni_versao_requisito" : ni_versao_requisito},
		dataType: 'json',
		success	: function(retorno){
			
			$('#cd_requisito'			).val(retorno['cd_requisito']);
			$('#dt_versao_requisito'	).val(retorno['dt_versao_requisito']);
			$('#ni_versao_requisito'	).val(retorno['ni_versao_requisito']);
			$('#tx_usuario_solicitante'	).val(retorno['tx_usuario_solicitante']);
			$('#tx_nivel_solicitante'	).val(retorno['tx_nivel_solicitante']);
			$('#tx_requisito'			).val(retorno['tx_requisito']);
			$('#st_prioridade_requisito').val(retorno['st_prioridade_requisito']);
			$('#st_requisito'			).val(retorno['st_requisito']);
			$('#tx_descricao_requisito'	).wysiwyg('value',retorno['tx_descricao_requisito']);
			if( retorno['st_tipo_requisito'] == "N"){
				$("#st_tipo_requisito").attr('checked','checked');	
				$("#st_tipo_requisito").val('N');	
			}else{
				$("#st_tipo_requisito").removeAttr('checked');
			}
			
			$('#btn_alterar_requisito').show();
			$('#btn_salvar_requisito').hide();
			habilitaAbaRequisito();
		}
	});
}

function montaComboRequistiosDependencia()
{
	var cd_projeto	= $("#cd_projeto").val();
	var cd_proposta	= $("#cd_proposta").val();
	
	//limpa as opções do combo para receber as novas opções de acordo com a atualização dos requisitos 
	$("#cmb_requisitos_dependencia").empty();
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/requisito-proposta/get-combo-requisitos",
		data	: {"cd_projeto"  : cd_projeto,
                   "cd_proposta" : cd_proposta},
		success	: function(retorno){
			//carrega o combo com os novos options
			$("#cmb_requisitos_dependencia").html(retorno);
		}
	});
}

function limparInputsRequisito()
{
	$("#div_novo_requisito :input"	).val('');
	$('#tx_descricao_requisito'		).wysiwyg('value','');
	$('#st_tipo_requisito'			).removeAttr('checked');
	$('#btn_alterar_requisito'		).hide();
	$('#btn_salvar_requisito'		).show();
}

function habilitaAbaRequisito()
{
	$("#li_aba_novo_requisito").show();
	$('#container-requisitos' ).triggerTab(2);
}

function desabilitaAbaRequisito()
{
	$("#li_aba_novo_requisito").hide();
	$('#container-requisitos' ).triggerTab(1);
	limparInputsRequisito();
}