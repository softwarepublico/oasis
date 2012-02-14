$(document).ready(function(){

	$("#cd_contrato_controle_proposta").change(function() {
		executaControlePropostaAjax();
		apresentaData($("#mesControleProposta").val(),$("#anoControleProposta").val(),'mesAnoControleProposta');
	});
	
	$("#mesControleProposta").change(function() {
		if ($("#mesControleProposta").val() != "0") {
			executaControlePropostaAjax();
			apresentaData($("#mesControleProposta").val(),$("#anoControleProposta").val(),'mesAnoControleProposta');
		}
	});
	
	$("#anoControleProposta").change(function() {
		if ($("#anoControleProposta").val() != "0") {
			executaControlePropostaAjax();
			apresentaData($("#mesControleProposta").val(),$("#anoControleProposta").val(),'mesAnoControleProposta');
		}
	});
});

/**
 * Carrega a grid de etapas de controle
 */
function executaControlePropostaAjax() {
	$.ajax({
		type	: "POST",
		url		: systemName+"/controle-proposta/pesquisa-propostas",
		data	: "mes="+$("#mesControleProposta").val()+
				  "&ano="+$("#anoControleProposta").val()+
				  "&cd_contrato="+$("#cd_contrato_controle_proposta").val(),
		success	: function(retorno){
			// atualiza a grid
			$("#gridControleProposta").html(retorno);
		}
	});
}

/**
 * Abre a pop-up de registro do parecer técnico da proposta
 */
function abreModalParecerTecnicoProposta(cd_projeto, cd_proposta, tx_sigla_projeto) {
	
	var jsonData = {'cd_projeto_parecer_tecnico_proposta':cd_projeto,
					'cd_proposta_parecer_tecnico_proposta': cd_proposta,
					'projeto_proposta_parecer_tecnico': tx_sigla_projeto
				   };

    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_parecer_tecnico_proposta');}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarParecerTecnicoProposta();}+'};');
    loadDialog({
        id       : 'dialog_parecer_tecnico_proposta',			//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_PARCER_TECNICO_PROPOSTA,	// titulo do pop-up
        url      : systemName + '/parecer-tecnico-proposta',	// url onde encontra-se o phtml
        data     : jsonData,									// parametros para serem transferidos para o pop-up
        height   : 320,											// altura do pop-up
        buttons  : buttons
    });
}

/**
 * Salva o registro do parecer técnico da proposta
 */
function salvarParecerTecnicoProposta() {

	if( !validaForm("#formParecerTecnicoProposta",true) ){return false;}
	$.ajax({
		type	: "POST",
		url		: systemName+"/parecer-tecnico-proposta/salvar",
		data	: $('#formParecerTecnicoProposta :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			closeDialog('dialog_parecer_tecnico_proposta');
			executaControlePropostaAjax();
		}
	});
}

/**
 * Abre a pop-up de consulta ao registro do parecer técnico da proposta
 */
function abreModalParecerTecnicoPropostaDetalhe(cd_projeto, cd_proposta, tx_sigla_projeto, cd_processamento_proposta) {

	var jsonData = {'cd_projeto':cd_projeto,
					'cd_proposta': cd_proposta,
					'cd_processamento_proposta': cd_processamento_proposta,
					'tx_sigla_projeto': tx_sigla_projeto
				   };
	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_detalhe_parecer_tecnico_proposta');}+'};');
    loadDialog({
        id       : 'dialog_detalhe_parecer_tecnico_proposta',					//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_PARCER_TECNICO_PROPOSTA,		// titulo do pop-up
        url      : systemName + '/controle-proposta/parecer-tecnico-proposta',	// url onde encontra-se o phtml
        data     : jsonData,													// parametros para serem transferidos para o pop-up
        height   : 330,															// altura do pop-up
        buttons  : buttons
    });
}

/**
 * Abre a pop-up de registro de aceite da proposta
 */
function abreModalAceiteProposta(cd_projeto, cd_proposta, tx_sigla_projeto) {

	var jsonData = {'cd_projeto_aceite_proposta':cd_projeto,
					'cd_proposta_aceite_proposta': cd_proposta,
					'projeto_proposta_aceite': tx_sigla_projeto};

        eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_aceite_proposta');}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarAceiteProposta();}+'};');
    loadDialog({
        id       : 'dialog_aceite_proposta',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_ACEITE_PROPOSTA, // titulo do pop-up
        url      : systemName + '/aceite-proposta',	// url onde encontra-se o phtml
        data     : jsonData,						// parametros para serem transferidos para o pop-up
        height   : 300,								// altura do pop-up
        buttons  : buttons
    });
}

/**
 * Salva o registro de aceite da proposta
 */
function salvarAceiteProposta() {

	if($("input[name=st_aceite_proposta]:checked").val() == undefined ){
		alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_ACEITE);
		return false;
	}

	if($("input[name=st_aceite_proposta]:checked").val() == 'N' && $("#tx_obs_aceite_proposta").val() == ''){
		alertMsg(i18n.L_VIEW_SCRIPT_INDIQUE_MOTIVO_ACEITE_NEGATIVO);
		return false;
	}

	$.ajax({
		type	: "POST",
		url		: systemName+"/aceite-proposta/salvar",
		data	: $('#formAceiteProposta :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			closeDialog('dialog_aceite_proposta');
			executaControlePropostaAjax();
		}
	});
}

/**
 * Abre a pop-up de consulta ao registro de aceite da proposta
 */
function abreModalAceitePropostaDetalhe(cd_projeto, cd_proposta, tx_sigla_projeto, cd_processamento_proposta) {

	var jsonData = {'cd_projeto':cd_projeto,
					'cd_proposta': cd_proposta,
					'cd_processamento_proposta': cd_processamento_proposta,
					'tx_sigla_projeto': tx_sigla_projeto
				   };
	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_detalhe_aceite_proposta');}+'};');
    loadDialog({
        id       : 'dialog_detalhe_aceite_proposta',				  //id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_ACEITE_PROPOSTA,   // titulo do pop-up
        url      : systemName + '/controle-proposta/aceite-proposta', // url onde encontra-se o phtml
        data     : jsonData,										  // parametros para serem transferidos para o pop-up
        height   : 250,												  // altura do pop-up
        buttons  : buttons
    });
}

/**
 * Abre a pop-up de registro do parecer da homologaão
 */
function abreModalHomologacaoProposta(cd_projeto, cd_proposta, tx_sigla_projeto) {

	var jsonData = {'cd_projeto_homologacao_proposta':cd_projeto,
					'cd_proposta_homologacao_proposta': cd_proposta,
					'projeto_proposta_homologacao': tx_sigla_projeto};

    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_homologacao_proposta');}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarHomologacaoProposta();}+'};');
    loadDialog({
        id       : 'dialog_homologacao_proposta',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_HOMOLOGACAO_PROPOSTA,			// titulo do pop-up
        url      : systemName + '/homologacao-proposta',	// url onde encontra-se o phtml
        data     : jsonData,						// parametros para serem transferidos para o pop-up
        height   : 300,								// altura do pop-up
        buttons  : buttons
    });
}

/**
 * Salva o registro da homologação da proposta
 */
function salvarHomologacaoProposta(){

	if($("input[name=st_homologacao_proposta]:checked").val() == undefined ){
		alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_TIPO_HOMOLOGACAO);
		return false;
	}
	if($("input[name=st_homologacao_proposta]:checked").val() == 'N' && $("#tx_obs_homologacao_proposta").val() == ''){
		alertMsg(i18n.L_VIEW_SCRIPT_INDIQUE_MOTIVO_HOMOLOGACAO_NEGATIVO);
		return false;
	}
	$.ajax({
		type	: "POST",
		url		: systemName+"/homologacao-proposta/salvar",
		data	: $('#formHomologacaoProposta :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			closeDialog('dialog_homologacao_proposta');
			executaControlePropostaAjax();
		}
	});
}

/**
 * Abre a pop-up de consulta ao registro da homologação da proposta
 */
function abreModalHomologacaoPropostaDetalhe(cd_projeto, cd_proposta, tx_sigla_projeto, cd_processamento_proposta) {

var jsonData = {'cd_projeto':cd_projeto,
					'cd_proposta': cd_proposta,
					'cd_processamento_proposta': cd_processamento_proposta,
					'tx_sigla_projeto': tx_sigla_projeto
				   };
	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_detalhe_homologacao_proposta');}+'};');
    loadDialog({
        id       : 'dialog_detalhe_homologacao_proposta',					//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_HOMOLOGACAO_PROPOSTA,		// titulo do pop-up
        url      : systemName + '/controle-proposta/homologacao-proposta',	// url onde encontra-se o phtml
        data     : jsonData,												// parametros para serem transferidos para o pop-up
        height   : 250,														// altura do pop-up
        buttons  : buttons
    });
}

/**
 * Redireciona para o registro de alocação de recurso da proposta
 */
function abreModalAlocacaoRecursoProposta(cd_projeto, cd_proposta, tx_sigla_projeto) {

	$.ajax({
		type	: "POST",
		url		: systemName+"/alocacao-recurso-proposta/verifica-objeto-contrato",
		data	: "cd_projeto="+cd_projeto+
				  "&cd_proposta="+cd_proposta+
				  "&tx_sigla_projeto="+tx_sigla_projeto,
		success	: function(retorno){
			var cond = parseInt(retorno);
			if(cond == 1 ){
				window.location.href = systemName+"/alocacao-recurso-proposta/index/cd_projeto/"+cd_projeto+"/cd_proposta/"+cd_proposta+"/tx_sigla_projeto/"+tx_sigla_projeto;	
			} else {
				alertMsg(i18n.L_VIEW_SCRIPT_ASSOCIE_OBJETO_CONTROTO);
			}
		}
	});
}

/**
 * Abre a pop-up de consulta ao registro da homologação da proposta
 */
function abreModalAlocacaoRecursoPropostaDetalhe(cd_projeto, cd_proposta, tx_sigla_projeto, cd_processamento_proposta) {

var jsonData = {'cd_projeto':cd_projeto,
                'cd_proposta': cd_proposta,
                'cd_processamento_proposta': cd_processamento_proposta,
                'tx_sigla_projeto': tx_sigla_projeto
               };
	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_detalhe_alocacao_recurso_proposta');}+'};');
    loadDialog({
        id       : 'dialog_detalhe_alocacao_recurso_proposta',				    //id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_ALOCACAO_PROPOSTA,	        // titulo do pop-up
        url      : systemName + '/controle-proposta/alocacao-recurso-proposta',	// url onde encontra-se o phtml
        data     : jsonData,												    // parametros para serem transferidos para o pop-up
        height   : 250,														    // altura do pop-up
        buttons  : buttons
    });
}

/**
 * Abre a pop-up de consulta ao registro de fechamento da proposta
 */
function abreModalFechamentoPropostaDetalhe(cd_projeto, cd_proposta, tx_sigla_projeto, cd_processamento_proposta) {

	var jsonData = {'cd_projeto':cd_projeto,
					'cd_proposta': cd_proposta,
					'cd_processamento_proposta':cd_processamento_proposta,
					'tx_sigla_projeto': tx_sigla_projeto};
	
    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_detalhe_fechamento_proposta');}+'};');
    loadDialog({
        id       : 'dialog_detalhe_fechamento_proposta',                    //id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_FECHAR_PROPOSTA,			// titulo do pop-up
        url      : systemName + '/controle-proposta/fechamento-proposta',	// url onde encontra-se o phtml
        data     : jsonData,						// parametros para serem transferidos para o pop-up
        height   : 200,								// altura do pop-up
        buttons  : buttons
    });
}

/**
 * Abre pop-up para visualização de relatorios
 */
function abrePopUp(url){
	var w = '800';
    var h = '600';
    
    openPopup( url, w, h );		
}