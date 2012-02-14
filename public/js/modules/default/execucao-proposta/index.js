$(document).ready(function(){
	/*
	* Accordions
	*/
	function hideAllAccordions(){
		$(".accordion").each(function(){
			$(this).hide('slow');
			$(this).prev().removeClass('accordion-open').addClass('accordion-closed');
		});
	}
	
	function accordionToggle(objTitulo){
		objTitulo.click(function() {
		    if ( objTitulo.next().css('display') == 'none') {
		    	hideAllAccordions();
		    	objTitulo.next().show('slow');
		    	objTitulo.removeClass('accordion-closed').addClass('accordion-open');
		    	
		    } else {
		    	objTitulo.next().hide('slow');
		   		objTitulo.removeClass('accordion-open').addClass('accordion-closed');
		    }
		});
	}
	
	//  inicializando componente accordion
	$('.accordion-closed').each(function(){
		accordionToggle($(this));
	});

	/*
	* Carrega box de situacao do projeto
	*/
	carregaBoxSituacaoProjeto = function() {
		$.ajax({
			type	: "POST",
			url		: systemName+'/situacao-projeto/ultima-situacao-projeto/cd_projeto/'+$("#cd_projeto").val(),
			//data	: dadosProjeto,
			success	: function(retUltimaSituacao){
				$("#box_posicionamento").html(retUltimaSituacao)
			}
		});
	}
	
	//REFERENTE � AREA DE CRIACAO/EDICAO DE POSICIONAMENTO ATUAL
	abrir_novo_posicionamento = function (operacao) {
		
        eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_posicionamento_projeto');}+', "'+i18n.L_VIEW_SCRIPT_BTN_GRAVAR_POSICIONAMENTO+'": '+function(){gravaPosicionamentoSituacao();}+'};');

		loadDialog({
			id       : 'dialog_posicionamento_projeto',			//id do pop-up
			title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_NOVO_POSICIONAMENTO,			// titulo do pop-up
			url      : systemName + '/situacao-projeto/editar-posicionamento',		// url onde encontra-se o phtml
			data     : {"operacao": operacao, "cd_projeto":$("#cd_projeto").val()},	// parametros para serem transferidos para o pop-up
			height   : 300,															// altura do pop-up
			buttons  : buttons
		});
	}
	
	carregaBoxSituacaoProjeto();
	
	/*
	* Fim do script do accordion
	*/
	
	$('#btn_confirmar_caso_de_uso').click(function(){
		confirmaStatus('proposta','st_caso_de_uso');
	});
	$('#btn_confirmar_dicionario_de_dados').click(function(){
		confirmaStatus('projeto','st_dicionario_dados');
	});
	$('#btn_confirmar_dados_tecnicos').click(function(){
		confirmaStatus('projeto','st_informacoes_tecnicas');
	});
	validaAccordionExecucaoProposta();
	openAccordionExecucaoProposta();
});

function validaAccordionExecucaoProposta()
{
	var dadosProposta  = 'cd_projeto='+$("#cd_projeto").val();	
		dadosProposta     += '&cd_proposta='+$("#cd_proposta").val();
		
	$.ajax({
		type	: "POST",
		url		: systemName+"/execucao-proposta/valida-accordion",
		data	: dadosProposta,
		dataType: 'json',
		success	: function(retorno){
			if(retorno[0]['st_caso_de_uso'] == 1){
				$("#caso_de_uso").addClass('accordion-ok');
			}
			if(retorno[0]['st_dicionario_dados'] == 1 ){
				$("#dicionario_de_dados").addClass('accordion-ok');
			}
			if(retorno[0]['st_informacoes_tecnicas'] == 1 ){
				$("#dados_tecnicos").addClass('accordion-ok');
			}
		}
	});
}

function openAccordionExecucaoProposta()
{
	if(openAccordionExecucao != ""){
		$("#"+openAccordionExecucao+"").each(function(){
			$(this).show('slow');
			$(this).prev().addClass('accordion-open');
            var execScript = 'config_'+openAccordionExecucao+'()';
            eval(execScript);
		});
	}
}

function confirmaStatus(cond, campo)
{
	var dadosProposta  = 'cd_projeto='+$("#cd_projeto").val();
		dadosProposta += '&cd_proposta='+$("#cd_proposta").val();
		dadosProposta += '&condicao='+cond;
		dadosProposta += '&campo='+campo;
		
	$.ajax({
		type	: "POST",
		url		: systemName+"/execucao-proposta/confirma-execucao",
		data	: dadosProposta,
		success	: function(retorno){
			validaAccordionExecucaoProposta();
			alertMsg(retorno);
		}
	});
}

function abrePopupResumoSolicitacaoServico()
{
    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_FECHAR+'": '+function(){closeDialog('dialog_detalhe_solicitacao_servico');}+'};');
    
    loadDialog({
        id       : 'dialog_detalhe_solicitacao_servico',	//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_DETALHE_SOLICITACAO_SERVICO,// titulo do pop-up
        objQuery : $('#div_desc_solicitacao_servico'),      //obj a ser renderizado
        height   : 300,                                     // altura do pop-up
        buttons  : buttons
    });
}