$(document).ready(function() {
	
	var cd_projeto_global;
	var cd_item_controle_baseline_global;
	var dt_gerencia_mudanca_global;
	var cd_item_controlado_global;
	var dt_versao_item_controlado_global;
 
	$("#mesAnaliseMudancaProposta").change(function() {
		if ($("#mesAnaliseMudancaProposta").val() != "0") {
			montaGridGerenciamentoMudancaProposta();
			apresentaData($("#mesAnaliseMudancaProposta").val(),$("#anoAnaliseMudancaProposta").val(),"mesAnoAnaliseMudancaProposta");
		} else {
			apresentaData($("#mesAnaliseMudancaProposta").val(),$("#anoAnaliseMudancaProposta").val(),"mesAnoAnaliseMudancaProposta");
		}
	});
	
	$("#anoAnaliseMudancaProposta").change(function() {
		if ($("#anoAnaliseMudancaProposta").val() != "0") {
			montaGridGerenciamentoMudancaProposta();
			apresentaData($("#mesAnaliseMudancaProposta").val(),$("#anoAnaliseMudancaProposta").val(),"mesAnoAnaliseMudancaProposta");
		} else {
			apresentaData($("#mesAnaliseMudancaProposta").val(),$("#anoAnaliseMudancaProposta").val(),"mesAnoAnaliseMudancaProposta");
		}
	});
});

/**
 * Método para atualizar o status da execução da proposta
 */
function atualizaExecucaoProposta()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/gerenciamento-mudanca-proposta/atualiza-execucao-proposta",
		data	: "cd_projeto="						+cd_projeto_global+
				  "&cd_item_controle_baseline="		+cd_item_controle_baseline_global+
				  "&dt_gerencia_mudanca="			+dt_gerencia_mudanca_global+
				  "&cd_item_controlado="			+cd_item_controlado_global+
				  "&dt_versao_item_controlado="		+dt_versao_item_controlado_global,
		success : function(retorno){
			alertMsg(retorno);
			// atualiza a grid e fecha modal
			montaGridGerenciamentoMudancaProposta();
		}
	});
}

/**
 * Método para montar a grid de propostas
 */
function montaGridGerenciamentoMudancaProposta()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/gerenciamento-mudanca-proposta/grid-gerenciamento-mudanca-proposta",
		data	: "mes="+$("#mesAnaliseMudancaProposta").val()+"&ano="+$("#anoAnaliseMudancaProposta").val(),
		success : function(retorno){
			// atualiza a grid
			$("#gridGerenciamentoMudancaProposta").html(retorno);
			$("#gridGerenciamentoMudancaProposta").show();
		}
	});
}

/**
 * Método para abrir a aba de cadastramento de analise de mudança da proposta
 * 
 * @param int 	 cd_projeto
 * @param int 	 cd_proposta
 * @param int 	 cd_item_controle_baseline
 * @param string dt_gerencia_mudanca
 * @param string dt_versao_item_controlado
 * @param string tx_projeto
 */
function cadastrarDecisaoMudancaProposta(cd_projeto, cd_item_controle_baseline, dt_gerencia_mudanca, cd_item_controlado, dt_versao_item_controlado, tx_projeto)
{
	$("#desc_proposta_gerenciamento_mudanca").html(cd_item_controlado);
	
	getDadosGerenciaMudanca('proposta',cd_projeto, cd_item_controle_baseline, dt_gerencia_mudanca, cd_item_controlado, dt_versao_item_controlado, tx_projeto);

	//captura o nr da aba que está em uso e guarda no hidden
	$('#id_tab_da_acao_gerenciamento_mudanca').val($('#container-gerenciamento-mudanca').activeTab());

	//habilita os campos hiddens de acordo com a aba escolhida
	habilitaCamposAbaCadastroDecisao('proposta');
}

/**
 * Método para abrir a Modal de alteração de proposta e setar variaveis globais
 *  
 * @param int 	 cd_projeto
 * @param int 	 cd_proposta
 * @param int 	 cd_item_controle_baseline
 * @param string dt_gerencia_mudanca
 * @param string dt_versao_item_controlado
 */
function abreModalAlteracaoPropostaAnaliseMudanca( cd_projeto, cd_proposta, cd_item_controle_baseline, dt_gerencia_mudanca, dt_versao_item_controlado )
{
	if(permissaoAlteracoProposta === 'N'){
		alertMsg(i18n.L_VIEW_SCRIPT_SEM_PERMISSAO_ABRIR_PROPOSTA);
		return false;	
	}

	cd_projeto_global					= cd_projeto;
	cd_item_controle_baseline_global	= cd_item_controle_baseline; 
	dt_gerencia_mudanca_global			= dt_gerencia_mudanca;
	cd_item_controlado_global			= cd_proposta;
	dt_versao_item_controlado_global	= dt_versao_item_controlado;
	
	var jsonData = {'cd_projeto':cd_projeto,
					'cd_proposta':cd_proposta
				   };

    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_alteracao_proposta');}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarAlteracaoProposta();}+'};');

    loadDialog({
        id       : 'dialog_alteracao_proposta',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_ALTERACAO_PROPOSTA,// titulo do pop-up
        url      : systemName + '/alteracao-proposta',	// url onde encontra-se o phtml
        data     : jsonData,							// parametros para serem transferidos para o pop-up
        height   : 250,									// altura do pop-up
        buttons  : buttons
    });
}

function salvarAlteracaoProposta()
{
	if(permissaoAlteracoProposta === 'N'){
		alertMsg(i18n.L_VIEW_SCRIPT_SEM_PERMISSAO_ABRIR_PROPOSTA);
		return false;
	}

	if($("#tx_motivo_alteracao_proposta").val() == ''){
		alertMsg(i18n.L_VIEW_SCRIPT_DESCREVA_MOTIVO_ALTERACAO_PROPOSTA);
		return false;
	}

	confirmMsg(i18n.L_VIEW_SCRIPT_INFO_ABRIR_PROPOSTA_GERACAO_PRE_PROJETO,function(){
			$.ajax({
				type	: 'POST',
				url		: systemName+'/alteracao-proposta/salvar',
				data	: $('#formAlteracaoProposta :input').serialize()+"&retorno_booleano=true",
				success	: function(retorno){
					if(retorno === 'true'){
						atualizaExecucaoProposta();
						closeDialog('dialog_alteracao_proposta');
					}else{
						alertMsg(i18n.L_VIEW_SCRIPT_ERRO_ABRIR_PROPOSTA);
					}
				}
			});
		},
		null,false, 180, 350
	);
}


/**
 * Método para abrir a Modal de abertura de pré-projeto evolutivo e setar variaveis globais
 *  
 * @param int 	 cd_projeto
 * @param int 	 cd_proposta
 * @param int 	 cd_item_controle_baseline
 * @param string dt_gerencia_mudanca
 * @param string dt_versao_item_controlado
 */
function abreModalPreProjetoAnaliseMudanca( cd_projeto, cd_proposta, cd_item_controle_baseline, dt_gerencia_mudanca, dt_versao_item_controlado )
{
	if(permissaoPreProjeto === 'N'){
		alertMsg(i18n.L_VIEW_SCRIPT_SEM_PERMISSAO_ABRIR_PRE_PROJETO_EVOLUTIVO);
		return false;	
	}
	
	cd_projeto_global					= cd_projeto;
	cd_item_controle_baseline_global	= cd_item_controle_baseline; 
	dt_gerencia_mudanca_global			= dt_gerencia_mudanca;
	cd_item_controlado_global			= cd_proposta;
	dt_versao_item_controlado_global	= dt_versao_item_controlado;

	var jsonData = {'cd_projeto':cd_projeto};

    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_abertura_pre_projeto_evolutivo');}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarDadosPreProjetoEvolutivo();}+'};');

	loadDialog({
        id       : 'dialog_abertura_pre_projeto_evolutivo',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_ABRIR_PRE_PROJETO_EVOLUTIVO,  // titulo do pop-up
        url      : systemName + '/gerenciamento-mudanca-proposta/modal-pre-projeto-evolutivo',	// url onde encontra-se o phtml
        data     : jsonData,							// parametros para serem transferidos para o pop-up
        height   : 250,									// altura do pop-up
        buttons  : buttons
    });
}

function salvarDadosPreProjetoEvolutivo()
{
	if(permissaoPreProjeto === 'N'){
		alertMsg(i18n.L_VIEW_SCRIPT_SEM_PERMISSAO_ABRIR_PRE_PROJETO_EVOLUTIVO);
		return false;	
	}
	
	if( !validaFormularioPreProjeto() ){return false};
	
	confirmMsg(i18n.L_VIEW_SCRIPT_INFO_ABRIR_PRE_PROJETO_EVOLUTIVO_GERACAO_PROPOSTA,function(){
			$.ajax({
				type	: "POST",
				url		: systemName+"/pre-projeto-evolutivo/salvar-dados-pre-projeto-evolutivo",
				data	: "cd_projeto="							+$("#cd_projeto_pre_projeto_evolutivo").val()+
						  "&tx_pre_projeto_evolutivo="			+$("#tx_pre_projeto_evolutivo").val()+
						  "&tx_objetivo_pre_proj_evol="	+$("#tx_objetivo_pre_proj_evol").val()+
						  "&retorno_booleano=true", 	// parametro enviado para mudança da opção de retorno
				success	: function(retorno){
					if(retorno === 'true'){
						atualizaExecucaoProposta();
						closeDialog('dialog_abertura_pre_projeto_evolutivo');
					}else{
						alertMsg(i18n.L_VIEW_SCRIPT_ERRO_ABRIR_PRE_PROJETO_EVOLUTIVO);
					}
				}
			});
		}, null, false, 180, 350
	);
}

/**
 * Método para validar o formulário de abertura de pre projeto
 */
function validaFormularioPreProjeto()
{
	if($("#tx_pre_projeto_evolutivo").val() == ''){
		alertMsg(i18n.L_VIEW_SCRIPT_NOME_PRE_PROJETO_OBRIGATORIO);
		return false;
	}
	if($("#tx_objetivo_pre_proj_evol").val() == ''){
		alertMsg(i18n.L_VIEW_SCRIPT_OBJETIVO_PRE_PROJETO_OBRIGATORIO);
		return false;
	}
	return true;	
}
