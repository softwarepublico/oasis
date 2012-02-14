$(document).ready(function() { 
	$('#container-gerenciamento-mudanca').show().tabs(
		{
			onShow: function(){
				var abaAcionada = $('#container-gerenciamento-mudanca').activeTab();
				if (abaAcionada == qtdAbas){
					$('#teste_aba_gerenciamento_mudanca').val($("#id_tab_da_acao_gerenciamento_mudanca").val());
				}else{
					$('#teste_aba_gerenciamento_mudanca').val(abaAcionada);
					if(parseInt($("#teste_aba_gerenciamento_mudanca").val()) != parseInt($("#id_tab_da_acao_gerenciamento_mudanca").val())){
						$('#li_aba_cadastro_decisao').hide();
					}
				}
			}
		}
	);

	$("#config_hidden_gerenciamento_mudanca").val('N');

	$('#container-gerenciamento-mudanca').triggerTab(1);
	
	$("#btn_cancelar_analise_mudanca").click(function(){
		limpaFormCadastroDecisaoMudanca();
	});
	
	$("#btn_salvar_analise_mudanca").click(function(){
		salvarDecisaoAnaliseDaMudanca();
	});
	
});

/**
 * Método que configura, no click, a inicialização do accordion
 */
function initAccordionAnaliseMudanca()
{
	$('#container-gerenciamento-mudanca').triggerTab(1);

	if( $("#config_hidden_gerenciamento_mudanca").val() === 'N' ){
		if(permissaoProposta == 'S'){
			apresentaData($("#mesAnaliseMudancaProposta").val(),$("#anoAnaliseMudancaProposta").val(),"mesAnoAnaliseMudancaProposta");
			montaGridGerenciamentoMudancaProposta();
		}
		if(permissaoRequisito == 'S'){
			apresentaData($("#mesAnaliseMudancaRequisito").val(),$("#anoAnaliseMudancaRequisito").val(),"mesAnoAnaliseMudancaRequisito");
			montaGridGerenciamentoMudancaRequisito();
		}
		if(permissaoRegraDeNegocio == 'S'){
			apresentaData($("#mesAnaliseMudancaRegraDeNegocio").val(),$("#anoAnaliseMudancaRegraDeNegocio").val(),"mesAnoAnaliseMudancaRegraDeNegocio");
			montaGridGerenciamentoMudancaRegraDeNegocio();
		}
		if(permissaoCasoDeUso == 'S'){
			apresentaData($("#mesAnaliseMudancaCasoDeUso").val(),$("#anoAnaliseMudancaCasoDeUso").val(),"mesAnoAnaliseMudancaCasoDeUso");
			montaGridGerenciamentoMudancaCasoDeUso();
		}

		$("#config_hidden_gerenciamento_mudanca").val('S');
	}
}

/**
 * Método para salvar a decisão tomada na análise da mudança
 */
function salvarDecisaoAnaliseDaMudanca()
{
	if(!validaFormularioDecisaoMudanca()){
		return false;
	}	
	$.ajax({
		type: "POST",
		url: systemName+"/gerenciamento-mudanca/salvar-decisao-analise-da-mudanca",
		data: $("#form_cadastro_decisao_mudanca :input").serialize()+
			  "&cd_projeto="	 			+$("#cd_projeto_gerenciamento_mudanca"					).val()+
			  "&cd_item_controle_baseline=" +$("#cd_item_controle_baseline_gerenciamento_mudanca"	).val()+
			  "&dt_gerencia_mudanca="		+$("#dt_gerencia_mudanca_gerenciamento_mudanca"			).val()+
			  "&cd_item_controlado="		+$("#cd_item_controlado_gerenciamento_mudanca"			).val()+
			  "&dt_versao_item_controlado=" +$("#dt_versao_item_controlado_gerenciamento_mudanca"	).val(),
		success: function(retorno){
			alertMsg(retorno);
			limpaFormCadastroDecisaoMudanca();
			
			switch($('#acao_gerenciamento_mudanca').val()){
				case 'proposta':
					montaGridGerenciamentoMudancaProposta();
				break;
				case 'requisito':
					montaGridGerenciamentoMudancaRequisito();
				break;
				case 'regra_de_negocio':
					montaGridGerenciamentoMudancaRegraDeNegocio();
				break;
				case 'caso_de_uso':
					montaGridGerenciamentoMudancaCasoDeUso();
				break;
			}
		}
	});
}

/**
 * Método para validar o formulario de cadastro da decisão da mudança
 */
function validaFormularioDecisaoMudanca()
{
	if($("#nao_aceita_mudanca").attr('checked') && $('#tx_decisao_mudanca').val() == ''){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$("#tx_decisao_mudanca"));
		return false;
	}
	return true;
}

/**
 * Método para buscar os dados referentes ao item de mudança para efetivar a decisão da mudança e preencher o form de cadastro
 * 
 * @param string acao
 * @param int 	 cd_projeto
 * @param int 	 cd_item_controle_baseline
 * @param string dt_gerencia_mudanca
 * @param int 	 cd_item_controlado
 * @param string dt_versao_item_controlado
 * @param string tx_projeto
 */
function getDadosGerenciaMudanca(acao,cd_projeto, cd_item_controle_baseline, dt_gerencia_mudanca, cd_item_controlado, dt_versao_item_controlado,tx_projeto)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/gerenciamento-mudanca/get-dados-gerencia-mudanca",
		data	: "cd_projeto="+cd_projeto+"&cd_item_controle_baseline="+cd_item_controle_baseline+
				  "&dt_gerencia_mudanca="+dt_gerencia_mudanca+"&cd_item_controlado="+cd_item_controlado+
				  "&dt_versao_item_controlado="+dt_versao_item_controlado,
		dataType: 'json',
		success : function(retorno){
			
			//hiddens
			$("#cd_projeto_gerenciamento_mudanca"				).val(retorno['cd_projeto']);
			$("#cd_item_controle_baseline_gerenciamento_mudanca").val(retorno['cd_item_controle_baseline']);
			$("#dt_gerencia_mudanca_gerenciamento_mudanca"		).val(retorno['dt_gerencia_mudanca']);
			$("#cd_item_controlado_gerenciamento_mudanca"		).val(retorno['cd_item_controlado']);
			$("#dt_versao_item_controlado_gerenciamento_mudanca").val(retorno['dt_versao_item_controlado']);
			
			//labels
			$("#desc_projeto_gerenciamento_mudanca"		).html(tx_projeto);
			$("#desc_motivo_mudanca"					).html(retorno['tx_motivo_mudanca']);

			if(retorno['st_mudanca_metrica'] === 'S'){
				$("#desc_mudanca_metrica").html(i18n.L_VIEW_SCRIPT_SIM);
				$("#desc_custo_mudanca"	 ).html(retorno['ni_custo_provavel_mudanca']);
			}else{
				$("#desc_mudanca_metrica").html(i18n.L_VIEW_SCRIPT_NAO);
				$("#desc_custo_mudanca"	 ).html(i18n.L_VIEW_SCRIPT_SEM_CUSTO);
			}
			
			if(retorno['st_reuniao'] === 'S'){
				var click = "abrirReuniao("+retorno['cd_projeto']+","+retorno['cd_reuniao']+");";
				var buttonVerReuniao = "<button name=\"btn_ver_reunicao\" onclick=\""+click+"\" id=\"btn_ver_reunicao\" class=\"verde\" >"+i18n.L_VIEW_SCRIPT_VER_REUNIAO+"</button>";
				
				$("#desc_realizacao_reuniao").html(i18n.L_VIEW_SCRIPT_SIM);
				$("#desc_ver_reuniao"		).html(buttonVerReuniao);
				
			}else{
				$("#desc_realizacao_reuniao").html(i18n.L_VIEW_SCRIPT_NAO);
				$("#desc_ver_reuniao"		).html("");
			}
		}
	});
	
	$("#acao_gerenciamento_mudanca").val(acao);	
}

/**
 * Método para gerar o PDF com a Ata da Reunião
 * 
 * @param int cd_projeto
 * @param int cd_reuniao
 */
function abrirReuniao(cd_projeto, cd_reuniao)
{
	//procedimentos para abrir os dados da reunião
	window.open(systemName+"/relatorioProjeto/ata-de-reuniao/generate/cd_projeto/"+cd_projeto+"/cd_reuniao/"+cd_reuniao);
}

/**
 * Método para habilitar os campos de descrição
 * @param string acao
 */
function habilitaCamposAbaCadastroDecisao(acao)
{
	switch(acao){
		case 'proposta':
			$("#lb_proposta_gerenciamento_mudanca"	 	 ).show();
			$("#desc_proposta_gerenciamento_mudanca"	 ).show();
			$("#lb_requisito_gerenciamento_mudanca"		 ).hide();
			$("#desc_requisito_gerenciamento_mudanca"	 ).hide();
			$("#lb_regra_negocio_gerenciamento_mudanca"	 ).hide();
			$("#desc_regra_negocio_gerenciamento_mudanca").hide();
			$("#lb_caso_de_uso_gerenciamento_mudanca"	 ).hide();
			$("#desc_caso_de_uso_gerenciamento_mudanca"	 ).hide();
			break;
		case 'requisito':
			$("#lb_proposta_gerenciamento_mudanca"	 	 ).hide();
			$("#desc_proposta_gerenciamento_mudanca"	 ).hide();
			$("#lb_requisito_gerenciamento_mudanca"		 ).show();
			$("#desc_requisito_gerenciamento_mudanca"	 ).show();
			$("#lb_regra_negocio_gerenciamento_mudanca"	 ).hide();
			$("#desc_regra_negocio_gerenciamento_mudanca").hide();
			$("#lb_caso_de_uso_gerenciamento_mudanca"	 ).hide();
			$("#desc_caso_de_uso_gerenciamento_mudanca"	 ).hide();
			break;
		case 'regra_de_negocio':
			$("#lb_proposta_gerenciamento_mudanca"	 	 ).hide();
			$("#desc_proposta_gerenciamento_mudanca"	 ).hide();
			$("#lb_requisito_gerenciamento_mudanca"		 ).hide();
			$("#desc_requisito_gerenciamento_mudanca"	 ).hide();
			$("#lb_regra_negocio_gerenciamento_mudanca"	 ).show();
			$("#desc_regra_negocio_gerenciamento_mudanca").show();
			$("#lb_caso_de_uso_gerenciamento_mudanca"	 ).hide();
			$("#desc_caso_de_uso_gerenciamento_mudanca"	 ).hide();
			break;
		case 'caso_de_uso':
			$("#lb_proposta_gerenciamento_mudanca"	 	 ).hide();
			$("#desc_proposta_gerenciamento_mudanca"	 ).hide();
			$("#lb_requisito_gerenciamento_mudanca"		 ).hide();
			$("#desc_requisito_gerenciamento_mudanca"	 ).hide();
			$("#lb_regra_negocio_gerenciamento_mudanca"	 ).hide();
			$("#desc_regra_negocio_gerenciamento_mudanca").hide();
			$("#lb_caso_de_uso_gerenciamento_mudanca"	 ).show();
			$("#desc_caso_de_uso_gerenciamento_mudanca"	 ).show();
			break;
	}
	
	$('#li_aba_cadastro_decisao').show();
	
	//montra sempre a ultima tab que é a de cadastro
	//qtdAbas é o parametro calculado no index.html
	$('#container-gerenciamento-mudanca').triggerTab(qtdAbas);
}

/**
 * Função utilizada para resetar o formulario de cadastra da decisão
 */
function limpaFormCadastroDecisaoMudanca()
{
	//retorna para aba que acionou a aba de cadastro
	var id = $("#id_tab_da_acao_gerenciamento_mudanca").val();
	$('#container-gerenciamento-mudanca').triggerTab(parseInt(id));
	
	//limpa e esconde os labels e os spans dos tipos de ações possíveis
	$("#desc_projeto_gerenciamento_mudanca"		 ).html('');
	$("#lb_proposta_gerenciamento_mudanca"	 	 ).hide();
	$("#desc_proposta_gerenciamento_mudanca"	 ).hide().html('');
	$("#lb_requisito_gerenciamento_mudanca"		 ).hide();
	$("#desc_requisito_gerenciamento_mudanca"	 ).hide().html('');
	$("#lb_regra_negocio_gerenciamento_mudanca"	 ).hide();
	$("#desc_regra_negocio_gerenciamento_mudanca").hide().html('');
	$("#lb_caso_de_uso_gerenciamento_mudanca"	 ).hide();
	$("#desc_caso_de_uso_gerenciamento_mudanca"	 ).hide().html('');

	//limpa os spans das descrções do pedido de mudança
	$("#desc_motivo_mudanca"	 ).html('');
	$("#desc_mudanca_metrica"	 ).html('');
	$("#desc_custo_mudanca"		 ).html('');
	$("#desc_realizacao_reuniao" ).html('');
	$("#desc_ver_reuniao"		 ).html('');
	
	//limpa os inputs da decisão
	$('#aceita_mudanca'			).attr('checked','checked');
	$('#tx_decisao_mudanca'		).val('');
	
	//esconde o titulo da aba de cadastro
	$('#li_aba_cadastro_decisao').hide();
		
}