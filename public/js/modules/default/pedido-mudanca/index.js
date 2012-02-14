$(document).ready(function() { 
	$('#container-pedido-mudanca').show().tabs(
		{
			onShow: function(){
				var abaAcionada = $('#container-pedido-mudanca').activeTab();
				if (abaAcionada == qtdAbasPM){
					$('#teste_aba').val($("#id_tab_da_acao").val());
				}else{
					$('#teste_aba').val(abaAcionada);
					if(parseInt($("#teste_aba").val()) != parseInt($("#id_tab_da_acao").val())){
						$('#li_aba_cadastro_pedido').hide();
					}
				}
			}
		}
	);

	$('#config_hidden_pedido_mudanca').val('N');

	//ao selecionar uma opção no combo de projetos na aba de proposta executa o seguinte:
	$("#cmb_projeto_proposta").change(function(){
		if($(this).val() == 0 ){
			$("#gridMudancaProposta").hide();
		}
		if($(this).val() != 0 ){
			montaGridMudancaProposta();
		}
	});
	
	//ao selecionar uma opção no combo de projetos na aba de requisito executa o seguinte:
	$("#cmb_projeto_requisito").change(function(){
		if($(this).val() == 0 ){
			$("#gridMudancaRequisito").hide();
		}
		if($(this).val() != 0 ){
			montaGridMudancaRequisito();
		}
	});
	
	//ao selecionar uma opção no combo de projetos na aba de regra de negocio executa o seguinte:
	$("#cmb_projeto_regra_negocio").change(function(){
		if($(this).val() == 0 ){
			$("#gridMudancaRegraNegocio").hide();
		}
		if($(this).val() != 0 ){
			montaGridMudancaRegraNegocio();
		}
	});
	
	//ao selecionar uma opção no combo de projetos na aba de caso de uso executa o seguinte:
	$("#cmb_projeto_caso_uso").change(function(){
		if($(this).val() == 0 ){
			$("#gridMudancaCasoDeUso").hide();
			$("#cmb_projeto_caso_uso_modulo").empty();
		}
		if($(this).val() != 0 ){
			carregaComboModuloCasoDeUso();
		}
	});
	
	//ao selecionar uma opção no combo de projetos na aba de caso de uso executa o seguinte:
	$("#cmb_projeto_caso_uso_modulo").change(function(){
		if($(this).val() == 0 ){
			$("#gridMudancaCasoDeUso").hide();
		}
		if($(this).val() != 0 ){
			montaGridMudancaCasoDeUso();
		}
	});
	
	$("#btn_cancelar_mudanca").click(function(){
		var id = $("#id_tab_da_acao").val();
		$('#container-pedido-mudanca').triggerTab(parseInt(id));
		$('#li_aba_cadastro_pedido').hide();
	});
	
	$("#btn_salvar_mudanca").click(function(){
		salvarPedidoMudanca();
	});
	
	//actions dos clicks dos radio buttons
	$("#afeta_metrica").click(function(){
		$("#qtd_horas").removeAttr('disabled');
	});
	$("#nao_afeta_metrica").click(function(){
		$("#qtd_horas").attr('disabled','didabled');
	});
	$("#reuniao_realizada").click(function(){
		$("#cd_reuniao_pedido_mudanca").removeAttr('disabled');
	});
	$("#reuniao_nao_realizada").click(function(){
		$("#cd_reuniao_pedido_mudanca").attr('disabled','didabled');
	});

	//inicializa os combos
	$("#cmb_projeto_proposta"		).val(0);
	$("#cmb_projeto_requisito"		).val(0);
	$("#cmb_projeto_regra_negocio"	).val(0);
	$("#cmb_projeto_caso_uso"		).val(0);
	$("#cmb_projeto_caso_uso_modulo").empty();
});

function initAccordionPedidoMudanca()
{
	if( $('#config_hidden_pedido_mudanca').val() === 'N' ){
		$('#container-pedido-mudanca').triggerTab(1);
		$('#config_hidden_pedido_mudanca').val('S');
	}
}

function montaGridMudancaProposta()
{
	if( $("#cmb_projeto_proposta").val() != 0 ){
		$.ajax({
			type	: "POST",
			url		: systemName+"/pedido-mudanca-proposta/grid-mudanca-proposta",
			data	: "cd_projeto="+$("#cmb_projeto_proposta").val(),
			success	: function(retorno){
				// atualiza a grid
				$("#gridMudancaProposta").html(retorno);
				$("#gridMudancaProposta").show();
			}
		});
	}
}

function montaGridMudancaRequisito()
{
	if( $("#cmb_projeto_requisito").val() != 0 ){
		$.ajax({
			type	: "POST",
			url		: systemName+"/pedido-mudanca-requisito/grid-mudanca-requisito",
			data	: "cd_projeto="+$("#cmb_projeto_requisito").val(),
			success	: function(retorno){
				// atualiza a grid
				$("#gridMudancaRequisito").html(retorno);
				$("#gridMudancaRequisito").show();
			}
		});
	}
}

function montaGridMudancaRegraNegocio()
{
	if( $("#cmb_projeto_regra_negocio").val() != 0 ){
		$.ajax({
			type	: "POST",
			url		: systemName+"/pedido-mudanca-regra-de-negocio/grid-mudanca-regra-negocio",
			data	: "cd_projeto="+$("#cmb_projeto_regra_negocio").val(),
			success	: function(retorno){
				// atualiza a grid
				$("#gridMudancaRegraNegocio").html(retorno);
				$("#gridMudancaRegraNegocio").show();
			}
		});
	}
}

function montaGridMudancaCasoDeUso()
{
	if( $("#cmb_projeto_caso_uso").val() != 0 && $("#cmb_projeto_caso_uso_modulo").val() != 0){
		$.ajax({
			type	: "POST",
			url		: systemName+"/pedido-mudanca-caso-de-uso/grid-mudanca-caso-de-uso",
			data	: "cd_projeto="+$("#cmb_projeto_caso_uso").val()+
					  "&cd_modulo="+$("#cmb_projeto_caso_uso_modulo").val(),
			success	: function(retorno){
				// atualiza a grid
				$("#gridMudancaCasoDeUso").html(retorno);
				$("#gridMudancaCasoDeUso").show();
			}
		});
	}
}

/**
 * Função utilizada para salvar todos os tipos de Pedido de Mudança
 */
function salvarPedidoMudanca()
{
	if(!validaFormularioPedidoMudanca()){return false;}

	$.ajax({
		type	: "POST",
		url		: systemName+"/pedido-mudanca/salvar-pedido-mudanca",
		data	: $("#form_cadastro_pedido_mudanca :input").serialize()+
				  "&cd_projeto="	 			 +$("#cd_projeto_corrente"			).val()+
				  "&dt_versao_item_conrolado="	 +$("#dt_versao_item_corrente"		).val()+
				  "&cd_item_controlado_corrente="+$("#cd_item_controlado_corrente"	).val(),
		success	: function(retorno){
			alertMsg(retorno);
			$('#container-pedido-mudanca').triggerTab(parseInt($('#id_tab_da_acao').val()));
			$('#li_aba_cadastro_pedido').hide();
			limpaInputsAbaPedidoMudana();
			montaGridMudancaProposta();
			montaGridMudancaRequisito();
			montaGridMudancaRegraNegocio();
			montaGridMudancaCasoDeUso();

			//atualiza o valor do campo hidden para
			//quando clicar no accordion de analise do pedido
			//a grid seja recarregada
			$('#config_hidden_gerenciamento_mudanca').val('N');
		}
	});
}

/**
 * Função manual para validar o formulario do pedido de mudança
 */
function validaFormularioPedidoMudanca()
{
    var msg = i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO;

	if($("#tx_motivo_mudanca").val() == ''){
		showToolTip(msg,$("#tx_motivo_mudanca"));
		return false;
	}
	if( !$("#qtd_horas").attr('disabled') && $("#qtd_horas").val() == ''){
	    showToolTip(msg, $("#qtd_horas"));
		return false;
	}
	if( !$("#cd_reuniao_pedido_mudanca").attr('disabled') && $("#cd_reuniao_pedido_mudanca").val() == 0){
	    showToolTip(msg, $("#cd_reuniao_pedido_mudanca"));
		return false;
	}
	return true;
}

/**
 * Função que recebe os parametros da grid de mudança de proposta para 
 * habilitar o cadastramento da mudança de proposta
 */
function cadastrarMudancaProposta( cd_projeto, cd_proposta, dt_fechamento_proposta )
{
	$("#acao_mudanca").val('proposta');
	
	$("#desc_proposta"				).html(cd_proposta);
	$("#cd_item_controlado_corrente").val(cd_proposta);
	$("#dt_versao_item_corrente"	).val(dt_fechamento_proposta);

	//captura o nr da aba que está em uso e guarda no hidden
	$('#id_tab_da_acao').val($('#container-pedido-mudanca').activeTab());
	
	//habilita os campos hiddens de acordo com id do combo de projeto
	habilitaCamposAbaCadastro('cmb_projeto_proposta');
	
	//carrega o combo com as datas das reuniões para o projeto corrente
	carregaComboDataReuniao();
}

/**
 * Função que recebe os parametros da grid de mudança de requisito para 
 * habilitar o cadastramento da mudança de requisito
 */
function cadastrarMudancaRequisito( tx_requisito, cd_requisito, dt_versao_requisito)
{
	$("#acao_mudanca").val('requisito');
	
	$("#desc_requisito"				).html(tx_requisito);
	$("#cd_item_controlado_corrente").val(cd_requisito);
	$("#dt_versao_item_corrente"	).val(dt_versao_requisito);

	//captura o nr da aba que está em uso e guarda no hidden
	$('#id_tab_da_acao').val($('#container-pedido-mudanca').activeTab());
	
	//habilita os campos hiddens de acordo com id do combo de projeto
	habilitaCamposAbaCadastro('cmb_projeto_requisito');
	
	//carrega o combo com as datas das reuniões para o projeto corrente
	carregaComboDataReuniao();
}

/**
 * Função que recebe os parametros da grid de mudança de regra de negocio para 
 * habilitar o cadastramento da mudança de regra de negocio
 */
function cadastrarMudancaRegraNegocio( tx_regra_negocio, cd_regra_negocio, dt_regra_negocio)
{
	$("#acao_mudanca").val('regra_negocio');
	
	$("#desc_regra_negocio"			).html(tx_regra_negocio);
	$("#cd_item_controlado_corrente").val(cd_regra_negocio);
	$("#dt_versao_item_corrente"	).val(dt_regra_negocio);

	//captura o nr da aba que está em uso e guarda no hidden
	$('#id_tab_da_acao').val($('#container-pedido-mudanca').activeTab());
	
	//habilita os campos hiddens de acordo com id do combo de projeto
	habilitaCamposAbaCadastro('cmb_projeto_regra_negocio');
	
	//carrega o combo com as datas das reuniões para o projeto corrente
	carregaComboDataReuniao();
}

/**
 * Função que recebe os parametros da grid de mudança de caso de uso para 
 * habilitar o cadastramento da mudança de caso de uso
 */
function cadastrarMudancaCasoDeUso( tx_caso_de_uso, cd_caso_de_uso, dt_versao_caso_de_uso)
{
	$("#acao_mudanca").val('caso_de_uso');
	
	$("#desc_caso_de_uso"			).html(tx_caso_de_uso);
	$("#cd_item_controlado_corrente").val(cd_caso_de_uso);
	$("#dt_versao_item_corrente"	).val(dt_versao_caso_de_uso);

	//captura o nr da aba que está em uso e guarda no hidden
	$('#id_tab_da_acao').val($('#container-pedido-mudanca').activeTab());
	
	//habilita os campos hiddens de acordo com id do combo de projeto
	habilitaCamposAbaCadastro('cmb_projeto_caso_uso');
	
	//carrega o combo com as datas das reuniões para o projeto corrente
	carregaComboDataReuniao();
}

/**
 * Função para carregar o combo das datas das reuniões 
 */
function carregaComboDataReuniao()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/reuniao-profissional/pesquisa-reuniao-projeto",
		data	: "cd_projeto="+$("#cd_projeto_corrente").val(),
		success	: function(retorno){
			$("#cd_reuniao_pedido_mudanca").html(retorno);
		}
	});
}

/**
 * Função para carregar o combo dos módulos do caso de uso 
 */
function carregaComboModuloCasoDeUso()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/pedido-mudanca-caso-de-uso/monta-combo-modulo",
		data	: "cd_projeto="+$("#cmb_projeto_caso_uso").val(),			  
		success : function(retorno){
			// atualiza a grid
			$("#cmb_projeto_caso_uso_modulo").html(retorno);
		}
	});
}

/**
 *Função para habilitar os campos hiddens do formulario de cadastramento
 *conforme a aba escolhida.
 *id_combo_projeto é quem determina qual aba chamou esta função 
 */
function habilitaCamposAbaCadastro(id_combo_projeto)
{
	var tx_projeto = $('#'+id_combo_projeto+' :selected').text();
	
	$("#desc_projeto").html(tx_projeto);
	$("#cd_projeto_corrente").val($('#'+id_combo_projeto).val());
	
	switch(id_combo_projeto){
		case 'cmb_projeto_proposta':
			$("#lb_requisito"		).hide();
			$("#desc_requisito"		).hide();
			$("#lb_regra_negocio"	).hide();
			$("#desc_regra_negocio"	).hide();
			$("#lb_caso_de_uso"		).hide();
			$("#desc_caso_de_uso"	).hide();
			break;
		case 'cmb_projeto_requisito':
			$("#lb_proposta"		).hide();
			$("#desc_proposta"		).hide();
			$("#lb_requisito"		).show();
			$("#desc_requisito"		).show();
			$("#lb_regra_negocio"	).hide();
			$("#desc_regra_negocio"	).hide();
			$("#lb_caso_de_uso"		).hide();
			$("#desc_caso_de_uso"	).hide();
			break;
		case 'cmb_projeto_regra_negocio':
			$("#lb_proposta"		).hide();
			$("#desc_proposta"		).hide();
			$("#lb_requisito"		).hide();
			$("#desc_requisito"		).hide();
			$("#lb_regra_negocio"	).show();
			$("#desc_regra_negocio"	).show();
			$("#lb_caso_de_uso"		).hide();
			$("#desc_caso_de_uso"	).hide();
			break;
		case 'cmb_projeto_caso_uso':
			$("#lb_proposta"		).hide();
			$("#desc_proposta"		).hide();
			$("#lb_requisito"		).hide();
			$("#desc_requisito"		).hide();
			$("#lb_regra_negocio"	).hide();
			$("#desc_regra_negocio"	).hide();
			$("#lb_caso_de_uso"		).show();
			$("#desc_caso_de_uso"	).show();
			break;
	}
	
	$('#li_aba_cadastro_pedido').show();
	
	//montra sempre a ultima tab que é a de cadastro
	//qtdAbasPM é o parametro calculado no index.html
	$('#container-pedido-mudanca').triggerTab(parseInt(qtdAbasPM));
}

function limpaInputsAbaPedidoMudana()
{
	$("#tx_motivo_mudanca"			).val('');
	$("#qtd_horas"					).val('');
	$("#qtd_horas"					).attr('disabled','disabled');
	$("#nao_afeta_metrica"			).attr('checked','checked');
	$("#reuniao_nao_realizada"		).attr('checked','checked');
	$("#cd_reuniao_pedido_mudanca"	).val(0);
	$("#cd_reuniao_pedido_mudanca"	).attr('disabled','disabled');
}