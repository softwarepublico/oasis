$(document).ready(function(){
	
    executaExecucaoPropostaAjax();
	apresentaData($("#mesExec").val(),$("#anoExec").val(),"mesAnoProjetoExecucao");
	
	$("#mesExec").change(function() {
		if ($("#mesExec").val() != "0") {
			executaExecucaoPropostaAjax();
			apresentaData($("#mesExec").val(),$("#anoExec").val(),"mesAnoProjetoExecucao");
		} else {
			apresentaData($("#mesExec").val(),$("#anoExec").val(),"mesAnoProjetoExecucao");
		}
	});

	$("#anoExec").change(function() {
		if ($("#anoExec").val() != "0") {
			executaExecucaoPropostaAjax();
			apresentaData($("#mesExec").val(),$("#anoExec").val(),"mesAnoProjetoExecucao");
		} else {
			apresentaData($("#mesExec").val(),$("#anoExec").val(),"mesAnoProjetoExecucao");
		}
	});

/*
	//REFERENTE � AREA DE CRIACAO/EDICAO DE POSICIONAMENTO ATUAL
	abrir_novo_posicionamento = function (operacao) {	
		
		if (operacao == 'N') {
			$("#tx_situacao_projeto").val(' ');
		}
		
		$('#novo_posicionamento_projeto').modal({
			containerCss:{height:'177px'}
		});
		
	}
*/
});

function abreModalParcelas(cd_parcela) {

    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_produtos_parcela_projeto');}+'};');

	loadDialog({
        id       : 'dialog_produtos_parcela_projeto',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_PRODUTO_PARCELA,	// titulo do pop-up
        url      : systemName+"/gerenciar-projetos-execucao-proposta/produtos-parcela",	// url onde encontra-se o phtml
        data     : {"cd_parcela":cd_parcela},											// parametros para serem transferidos para o pop-up
        height   : 250,																	// altura do pop-up
        width    : 400,																	// largura do pop-up
        buttons  : buttons
    });
}

function abreModalProfissional(cd_projeto)
{
    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_FECHAR+'": '+function(){closeDialog('dialog_profissionais_projeto');}+'};');

	loadDialog({
        id       : 'dialog_profissionais_projeto',	//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_PROFISSIONAL_PROJETO,	// titulo do pop-up
        url      : systemName+"/gerenciar-projetos-execucao-proposta/profissional-projeto",	// url onde encontra-se o phtml
        data     : {"cd_projeto":cd_projeto},												// parametros para serem transferidos para o pop-up
        height   : 250,																		// altura do pop-up
        width    : 400,																		// largura do pop-up
        buttons  : buttons
    });
}

function executaExecucaoPropostaAjax()
{
	$.ajax({
		type: "POST",
		url: systemName+"/gerenciar-projetos-execucao-proposta/pesquisa-propostas-em-execucao",
		data: "mes="+$("#mesExec").val()+"&ano="+$("#anoExec").val(),
		success: function(retorno){
			// atualiza a grid
			$("#gridExecucaoProposta").html(retorno);
		}
	});
}

function fechaParcela(cd_projeto, cd_proposta, cd_parcela)
{
	$.ajax({
		type: "POST",
		url: systemName+"/processamento-parcela/fechar-parcela",
		data: "cd_projeto="+cd_projeto+"&cd_proposta="+cd_proposta+"&cd_parcela="+cd_parcela,
		success: function(retorno){
			alertMsg(retorno);
			executaExecucaoPropostaAjax();
		}
	});
}

function gerarRelatorioHistorico(cd_projeto, cd_proposta)
{
   geraHistorico(systemName+'/relatorioProjeto/historico-projeto/generate/cd_projeto/'+cd_projeto+'/cd_proposta/'+cd_proposta);
}

function geraHistorico(url)
{
	var w = '800';
    var h = '600';
    
    openPopup( url, w, h );		
}