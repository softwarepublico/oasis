$(document).ready(function(){

	$("#cd_contrato_controle_parcela").change(function() {
		executaControleParcelaAjax();
		apresentaData($('#mesControleParcela').val(),$('#anoControleParcela').val(),'mesAnoControleParcela');
	});
	
	$("#mesControleParcela").change(function() {
		if ($("#mesControleParcela").val() != "0") {
			executaControleParcelaAjax();
			apresentaData($('#mesControleParcela').val(),$('#anoControleParcela').val(),'mesAnoControleParcela');
		}
	});
	
	$("#anoControleParcela").change(function() {
		if ($("#anoControleParcela").val() != "0") {
			executaControleParcelaAjax();
			apresentaData($('#mesControleParcela').val(),$('#anoControleParcela').val(),'mesAnoControleParcela');
		}
	});
});


function executaControleParcelaAjax() {
	$.ajax({
		type: "POST",
		url: systemName+"/controle-parcela/pesquisa-controle-parcela",
		data: "mes="+$("#mesControleParcela").val()+
		"&ano="+$("#anoControleParcela").val()+
		"&cd_contrato="+$("#cd_contrato_controle_parcela").val(),
		success: function(retorno){
			// atualiza a grid
			$("#gridControleParcela").html(retorno);
		}
	});
}

/**
 * Abre pop-up para registro da autorização da parcela
 */
function abreModalAutorizacaoParcela(cd_projeto, cd_proposta, cd_parcela, tx_sigla_projeto, ni_parcela) {

	var jsonData = {'cd_projeto':cd_projeto,
					'cd_proposta': cd_proposta,
					'cd_parcela': cd_parcela,
					'ni_parcela': ni_parcela,
					'tx_sigla_projeto': tx_sigla_projeto
				   };
	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog( 'dialog_autorizacao_parcela' );}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarAutorizacaoParcela();}+'};');
    loadDialog({
        id       : 'dialog_autorizacao_parcela',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_AUTORIZAR_PARCELA,// titulo do pop-up
        url      : systemName + '/autorizacao-parcela',	// url onde encontra-se o phtml
        data     : jsonData,							// parametros para serem transferidos para o pop-up
        height   : 300,									// altura do pop-up
        buttons  : buttons
    });
}

/**
 * Salva a autorização da parcela
 */
function salvarAutorizacaoParcela() {

	$.ajax({
		type	: 'POST',
		url		: systemName+'/autorizacao-parcela/salvar',
		data	: $('#formAutorizacaoParcela :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			executaControleParcelaAjax();
			closeDialog('dialog_autorizacao_parcela');
		}
	});
}

/**
 * Abre pop-up de consulta aos detalhes da autorização da parcela
 */
function abreModalAutorizacaoParcelaDetalhe(cd_projeto, cd_proposta, cd_parcela, tx_sigla_projeto, ni_parcela) {

	var jsonData = {'cd_projeto':cd_projeto,
					'cd_proposta': cd_proposta,
					'cd_parcela': cd_parcela,
					'ni_parcela': ni_parcela,
					'tx_sigla_projeto': tx_sigla_projeto
				   };
    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog( 'dialog_detalhe_autorizacao_parcela' );}+'};');
	loadDialog({
        id       : 'dialog_detalhe_autorizacao_parcela',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_AUTORIZAR_PARCELA,	    // titulo do pop-up
        url      : systemName + '/controle-parcela/autorizacao-parcela',	// url onde encontra-se o phtml
        data     : jsonData,							// parametros para serem transferidos para o pop-up
        height   : 300,									// altura do pop-up
        buttons  : buttons
    });
}

/**
 * Abre pop-up de consulta ao registro de fechamento da parcela
 */
function abreModalFechamentoParcelaDetalhe(cd_projeto, cd_proposta, cd_parcela, tx_sigla_projeto, ni_parcela) {

	var jsonData = {'cd_projeto':cd_projeto,
					'cd_proposta': cd_proposta,
					'cd_parcela': cd_parcela,
					'ni_parcela': ni_parcela,
					'tx_sigla_projeto': tx_sigla_projeto
				   };
	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog( 'dialog_detalhe_fechamento_parcela' );}+'};');
    loadDialog({
        id       : 'dialog_detalhe_fechamento_parcela',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_FECHAR_PARCELA,	// titulo do pop-up
        url      : systemName + '/controle-parcela/fechamento-parcela',	// url onde encontra-se o phtml
        data     : jsonData,							// parametros para serem transferidos para o pop-up
        height   : 230,									// altura do pop-up
        buttons  : buttons
    });
}

/**
 * Abre pop-up para registro de parecer técnico da parcela
 */
function abreModalParecerTecnicoParcela(cd_projeto, cd_proposta, cd_parcela, tx_sigla_projeto, ni_parcela, st_pendente) {

	var jsonData = {'cd_projeto':cd_projeto,
					'cd_proposta': cd_proposta,
					'cd_parcela': cd_parcela,
					'tx_sigla_projeto': tx_sigla_projeto,
					'ni_parcela': ni_parcela,
					'st_pendente': st_pendente
				   };
	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog( 'dialog_parecer_tecnico_parcela' );}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarParecerTecnicoParcela();}+'};');
    loadDialog({
        id       : 'dialog_parecer_tecnico_parcela',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_PARCER_TECNICO_PARCELA,			// titulo do pop-up
        url      : systemName + '/parecer-tecnico-parcela',	// url onde encontra-se o phtml
        data     : jsonData,							// parametros para serem transferidos para o pop-up
        height   : 400,									// altura do pop-up
        buttons  : buttons
    });
}

/**
 * Salva o parecer técnico da parcela
 */
function salvarParecerTecnicoParcela() {

	if( !validaForm("#formParecerTecnicoParcela", true) ){return false;}

	$.ajax({
		type	: 'POST',
		url		: systemName+'/parecer-tecnico-parcela/salvar',
		data	: $('#formParecerTecnicoParcela :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			executaControleParcelaAjax();
			closeDialog('dialog_parecer_tecnico_parcela');
		}
	});
}

/**
 * Abre pop-up de consulta aos detalhes do parecer técnico da parcela
 */
function abreModalParecerTecnicoParcelaDetalhe(cd_projeto, cd_proposta, cd_parcela, tx_sigla_projeto, ni_parcela) {

	var jsonData = {'cd_projeto':cd_projeto,
					'cd_proposta': cd_proposta,
					'cd_parcela': cd_parcela,
					'tx_sigla_projeto': tx_sigla_projeto,
					'ni_parcela': ni_parcela
				   };
	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog( 'dialog_detalhe_parecer_tecnico_parcela' );}+'};');
    loadDialog({
        id       : 'dialog_detalhe_parecer_tecnico_parcela',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_PARCER_TECNICO_PARCELA,			// titulo do pop-up
        url      : systemName + '/controle-parcela/parecer-tecnico-parcela',	// url onde encontra-se o phtml
        data     : jsonData,							// parametros para serem transferidos para o pop-up
        height   : 400,									// altura do pop-up
        buttons  : buttons
    });
}

/**
 * Abre pop-up para aceite da parcela
 */
function abreModalAceiteParcela(cd_projeto, cd_proposta, cd_parcela, tx_sigla_projeto, ni_parcela) {

	var jsonData = {'cd_projeto':cd_projeto,
					'cd_proposta': cd_proposta,
					'cd_parcela': cd_parcela,
					'ni_parcela': ni_parcela,
					'tx_sigla_projeto': tx_sigla_projeto
				   };
	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog( 'dialog_aceite_parcela' );}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarAceiteParcela();}+'};');
    loadDialog({
        id       : 'dialog_aceite_parcela',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_ACEITE_PARCELA,			// titulo do pop-up
        url      : systemName + '/aceite-parcela',	// url onde encontra-se o phtml
        data     : jsonData,							// parametros para serem transferidos para o pop-up
        height   : 300,									// altura do pop-up
        buttons  : buttons
    });
}

/**
 * Salva o registro de aceite da parcela
 */
function salvarAceiteParcela() {
	
	if($("input[name=st_aceite_parcela]:checked").val() == undefined ){
		alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_ACEITE);
		return false;
	}
	if($("input[name=st_aceite_parcela]:checked").val() == 'N' && $("#tx_obs_aceite_parcela").val() == ''){
		alertMsg(i18n.L_VIEW_SCRIPT_INDIQUE_MOTIVO_ACEITE_NEGATIVO);
		return false;
	}

	$.ajax({
		type	: "POST",
		url		: systemName+"/aceite-parcela/salvar",
		data	: $('#formAceiteParcela :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			closeDialog('dialog_aceite_parcela');
			executaControleParcelaAjax();
		}
	});
}

/**
 * Abre pop-up de consulta ao registro de aceite da parcela
 */
function abreModalAceiteParcelaDetalhe(cd_projeto, cd_proposta, cd_parcela, tx_sigla_projeto, ni_parcela) {

	var jsonData = {'cd_projeto':cd_projeto,
					'cd_proposta': cd_proposta,
					'cd_parcela': cd_parcela,
					'tx_sigla_projeto': tx_sigla_projeto,
					'ni_parcela': ni_parcela
				   };
	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog( 'dialog_detalhe_aceite_parcela' );}+'};');
    loadDialog({
        id       : 'dialog_detalhe_aceite_parcela',					//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_ACEITE_PARCELA,	// titulo do pop-up
        url      : systemName + '/controle-parcela/aceite-parcela',	// url onde encontra-se o phtml
        data     : jsonData,							// parametros para serem transferidos para o pop-up
        height   : 250,									// altura do pop-up
        buttons  : buttons
    });
}

/**
 * Abre pop-up de geristro de homologação de parcela
 */
function abreModalHomologacaoParcela(cd_projeto, cd_proposta, cd_parcela, tx_sigla_projeto, ni_parcela) {

	var jsonData = {'cd_projeto':cd_projeto,
					'cd_proposta': cd_proposta,
					'cd_parcela': cd_parcela,
					'ni_parcela': ni_parcela,
					'tx_sigla_projeto': tx_sigla_projeto
				   };
	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog( 'dialog_homologacao_parcela' );}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarHomologacaoParcela();}+'};');
    loadDialog({
        id       : 'dialog_homologacao_parcela',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_HOMOLOGACAO_PARCELA,	// titulo do pop-up
        url      : systemName + '/homologacao-parcela',	// url onde encontra-se o phtml
        data     : jsonData,							// parametros para serem transferidos para o pop-up
        height   : 300,									// altura do pop-up
        buttons  : buttons
    });
}

/**
 * Salva os registros da homologação da parcela
 */
function salvarHomologacaoParcela() {

	if($("input[name=st_homologacao_parcela]:checked").val() == undefined ){
		alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_TIPO_HOMOLOGACAO);
		return false;
	}
	if($("input[name=st_homologacao_parcela]:checked").val() == 'N' && $("#tx_obs_homologacao_parcela").val() == ''){
		alertMsg(i18n.L_VIEW_SCRIPT_INDIQUE_MOTIVO_HOMOLOGACAO_NEGATIVO);
		return false;
	}

	$.ajax({
		type	: "POST",
		url		: systemName+"/homologacao-parcela/salvar",
		data	: $('#formHomologacaoParcela :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			closeDialog('dialog_homologacao_parcela');
			executaControleParcelaAjax();
		}
	});
}

/**
 * Abre pop-up de consulta ao registro de homologação da parcela
 */
function abreModalHomologacaoParcelaDetalhe(cd_projeto, cd_proposta, cd_parcela, tx_sigla_projeto, ni_parcela) {

	var jsonData = {'cd_projeto':cd_projeto,
					'cd_proposta': cd_proposta,
					'cd_parcela': cd_parcela,
					'tx_sigla_projeto': tx_sigla_projeto,
					'ni_parcela': ni_parcela
				   };
	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog( 'dialog_detalhe_homologacao_parcela' );}+'};');
    loadDialog({
        id       : 'dialog_detalhe_homologacao_parcela',					//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_HOMOLOGACAO_PARCELA,		// titulo do pop-up
        url      : systemName + '/controle-parcela/homologacao-parcela',	// url onde encontra-se o phtml
        data     : jsonData,							// parametros para serem transferidos para o pop-up
        height   : 250,									// altura do pop-up
        buttons  : buttons
    });
}

function fechaParcela(cd_projeto, cd_proposta, cd_parcela)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/processamento-parcela/fechar-parcela",
		data	: "cd_projeto="+cd_projeto+"&cd_proposta="+cd_proposta+"&cd_parcela="+cd_parcela,
		success	: function(retorno){
			alertMsg(retorno);
			executaControleParcelaAjax();
		}
	});
}

function abrePopUp(url)
{
	var w = '800';
    var h = '600';

    openPopup( url, w, h );
}
