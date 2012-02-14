$(document).ready(function(){
	//Função que são inicializadas
	carregaBoxSituacaoProjeto();
	validaAccordion();
	openAccordionElaboracaoProposta();

	/*
	* Modulo de proposta
	*/
	$("#btn_salvar_descricao_projeto").click(function() {
		salvarObjetivoProjeto();
	});
	
	$('#bt_salvar_objetivo_proposta').click(function () {
		salvarObjetivoProposta();
	});
	
	//  inicializando componente accordion
	$('.accordion-closed').each(function(){
		accordionToggle($(this));
	});

	/*
	*Fim dos Accordions
	*/
	$("#bt_confirmar_metrica").click(function() {
		confirmaStatusElaboracao('st_metrica');	
	});	
	$("#bt_confirmar_produtos").click(function() {
		//valida a confirmação do accordion
		verificaAcrescimoProduto();
	});
	
	$("#bt_confirmar_profissional").click(function(){
		confirmaStatusElaboracao('st_profissional');
	});
	$("#bt_confirmar_modulo").click(function() {
		//valida a confirmação do accordion
		verificaModulosProjeto();
	});
		
	$("#bt_confirmar_parcela").click(function() {
		//valida a confirmação do accordion
		verificaParcelasProposta();
	});
	$("#bt_confirmar_documento_proposta").click(function() {
		confirmaStatusElaboracao('st_documentacao');
	});
	$("#bt_confirmar_requisito").click(function() {
		verificaRequisitosProjeto();
	});

	if ($("#st_parcela_orcamento").val() == 'S'){
		$("#porcentagemParcelaOrcamento").text('('+$("#ni_porcentagem_parc_orcamento").val()+'% do total do projeto)');
	} else {
		$("#porcentagemParcelaOrcamento").text('Sem Parcela de Orçamento');
		$("#porcentagemHorasProposta").hide();
	}
});


function salvarObjetivoProjeto()
{
	if( !validaForm('#projeto') ){ return false; }
	var impacto = '';
	$(":radio").each(function() {
		if (this.id == 'st_impacto_projeto-I' && this.checked == true) {
			impacto = 'I';
		} else if (this.id == 'st_impacto_projeto-E' && this.checked == true) {
			impacto = 'E';
		} else if (this.id == 'st_impacto_projeto-A' && this.checked == true) {
			impacto = 'A';
		}
	});

	var dadosProjeto  = 'tx_projeto='+$("#tx_projeto").val();
		dadosProjeto += '&tx_sigla_projeto='+$("#tx_sigla_projeto").val();
		dadosProjeto += '&tx_contexto_geral_projeto='+$("#tx_contexto_geral_projeto").wysiwyg('getContent').val();
		dadosProjeto += '&tx_escopo_projeto='+$("#tx_escopo_projeto").wysiwyg('getContent').val();
		dadosProjeto += '&cd_unidade='+$("#cd_unidade").val();
		dadosProjeto += '&tx_gestor_projeto='+$("#tx_gestor_projeto").val();
		dadosProjeto += '&cd_profissional_gerente='+$("#cd_profissional_gerente").val();
		dadosProjeto += '&cd_status='+$("#cd_status").val();
		dadosProjeto += '&tx_obs_projeto='+$("#tx_obs_projeto").val();
		dadosProjeto += '&st_impacto_projeto='+impacto;
		dadosProjeto += '&st_prioridade_projeto='+$("#st_prioridade_projeto").val();
		dadosProjeto += '&cd_projeto='+$("#cd_projeto").val();
		dadosProjeto += '&tx_publico_alcancado='+$("#tx_publico_alcancado").val();
		dadosProjeto += '&ni_mes_inicio_previsto='+$("#ni_mes_inicio_previsto").val();
		dadosProjeto += '&ni_ano_inicio_previsto='+$("#ni_ano_inicio_previsto").val();
		dadosProjeto += '&ni_mes_termino_previsto='+$("#ni_mes_termino_previsto").val();
		dadosProjeto += '&ni_ano_termino_previsto='+$("#ni_ano_termino_previsto").val();
		dadosProjeto += '&tx_co_gestor_projeto='+$("#tx_co_gestor_projeto").val();
		dadosProjeto += '&cd_proposta='+$("#cd_proposta").val();

	$.ajax({
		type	: "POST",
		url		: systemName+'/projeto/editar',
		data	: dadosProjeto,
		success	: function(retorno){
			alertMsg(retorno);
			$("#descricao_proposta").addClass('accordion-ok');
		}
	});
}

function salvarObjetivoProposta()
{
	var postData = '';
		postData  = 'tx_objetivo_proposta='+$("#tx_objetivo_proposta").val();
		postData += '&cd_projeto='+$("#cd_projeto").val();
		postData += '&cd_proposta='+$("#cd_proposta").val();

	$.ajax({
		type	: "POST",
		url		: systemName+"/proposta/salvar-objetivo-proposta",
		data	: postData,
		success	: function(retorno){
			alertMsg(retorno);
			$("#objetivo_proposta").addClass('accordion-ok');
		}
	});
}

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

/*
 * REFERENTE À AREA DE CRIACAO/EDICAO DE POSICIONAMENTO ATUAL
 */
function abrir_novo_posicionamento(operacao)
{
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

/*
 * Carrega box de situacao do projeto
 */
function carregaBoxSituacaoProjeto()
{
	$.ajax({
		type	: "POST",
		url		: systemName+'/situacao-projeto/ultima-situacao-projeto',
		data	: "cd_projeto="+$("#cd_projeto").val(),
		success	: function(retUltimaSituacao){
			$("#box_posicionamento").html(retUltimaSituacao);
		}
	});
}

/*MÉTODOS PARA VERIFICAÇÃO DAS EXIGÊNCIA DA CONFIRMAÇÃO DOS ACCORDIONS*/
/**
 * Método para verificar se as condições necessárias à confirmação do accordion de 
 * Acrescimo de Produto foram atendidas
 */
function verificaAcrescimoProduto()
{
	var dadosProposta  = 'cd_projeto='+$("#cd_projeto").val();	
		dadosProposta += '&cd_proposta='+$("#cd_proposta").val();
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/elaboracao-proposta/valida-confirmacao-acrescimo-produto",
		data	: dadosProposta,
		dataType: 'json',
		success	: function(retorno){
			if(retorno['error'] == true){
	            alertMsg(retorno['msg'],retorno['errorType']);
	        }else{
				confirmaStatusElaboracao('st_produto');
	        }
		}
	});
}

/**
 * Método para verificar se as condições necessárias à confirmação do accordion de 
 * Gerencia de Módulo foram atendidas
 */
function verificaModulosProjeto()
{
	var dadosProposta  = 'cd_projeto='+$("#cd_projeto").val();	
		dadosProposta += '&cd_proposta='+$("#cd_proposta").val();
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/elaboracao-proposta/valida-confirmacao-gerencia-modulo",
		data	: dadosProposta,
		dataType: 'json',
		success	: function(retorno){
			if(retorno['error'] == true){
	            alertMsg(retorno['msg'],retorno['errorType']);
	        }else{
				confirmaStatusElaboracao('st_modulo');
	        }
		}
	});
}

/**
 * Método para verificar se as condições necessárias à confirmação do accordion
 * Criar Parcela foram atendidas
 */
function verificaParcelasProposta()
{
	var dadosProposta  = 'cd_projeto='+$("#cd_projeto").val();	
		dadosProposta += '&cd_proposta='+$("#cd_proposta").val();
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/elaboracao-proposta/valida-confirmacao-criar-parcela",
		data	: dadosProposta,
		dataType: 'json',
		success	: function(retorno){
			if(retorno['error'] == true){
	            alertMsg(retorno['msg'],retorno['errorType']);
	        }else{
				confirmaStatusElaboracao('st_parcela');
	        }
		}
	});
}

/**
 * Método para verificar se as condições necessárias à confirmação do accordion
 * Requisitos foram atendidas
 */
function verificaRequisitosProjeto()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/elaboracao-proposta/valida-confirmacao-requisito",
		data	: 'cd_projeto='+$("#cd_projeto").val(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['error'] == true){
	            alertMsg(retorno['msg'],retorno['errorType']);
	        }else{
				confirmaStatusElaboracao('st_requisito');
	        }
		}
	});
}

/*FIM VERIFICAÇÃO DAS EXIGÊNCIAS*/
function openAccordionElaboracaoProposta()
{
	if(openAccordionElaboracao != ""){
		$("#"+openAccordionElaboracao+"").each(function(){
			$(this).show('slow');
			$(this).prev().addClass('accordion-open');
		});
	}
}

function validaAccordion()
{
    var cd_proposta = parseInt($("#cd_proposta").val());

	var dadosProposta  = 'cd_projeto='+$("#cd_projeto").val();	
	dadosProposta     += '&cd_proposta='+cd_proposta;
	$.ajax({
		type	: "POST",
		url		: systemName+"/elaboracao-proposta/valida-accordion",
		data	: dadosProposta,
		dataType: 'json',
		success	: function(retorno){
            //este accordion só aparece se a proposta for maior que 1
            if(cd_proposta > 1){
                if(retorno[0]['st_objetivo_proposta'] == 1){
                    $("#objetivo_proposta").addClass('accordion-ok');
                }
            }
            
			if(retorno[0]['st_descricao'] == 1){
				$("#descricao_proposta").addClass('accordion-ok');
			}
			if(retorno[0]['st_profissional'] == 1 ){
				$("#alocar_profissionais").addClass('accordion-ok');
			}
			if(retorno[0]['st_metrica'] == 1 ){
				$("#metrica_dinamica").addClass('accordion-ok');
			}
			if(retorno[0]['st_modulo'] == 1 ){
				$("#gerenciar_modulos").addClass('accordion-ok');
			}
			if(retorno[0]['st_parcela'] == 1 ){
				$("#criar_parcelas").addClass('accordion-ok');
			}
			if(retorno[0]['st_produto'] == 1 ){
				$("#acrescentar_produtos").addClass('accordion-ok');
			}
			if(retorno[0]['st_documentacao'] == 1 ){
				$("#arquivos_proposta").addClass('accordion-ok');
			}
			if(retorno[0]['st_requisito'] == 1 ){
				$("#requisitos_proposta").addClass('accordion-ok');
			}
		}
	});
}

function confirmaStatusElaboracao(campo)
{
	var dadosProposta  = 'cd_projeto='+$("#cd_projeto").val();
		dadosProposta += '&cd_proposta='+$("#cd_proposta").val();
		dadosProposta += '&campo='+campo;
		
	$.ajax({
		type	: "POST",
		url		: systemName+"/elaboracao-proposta/confirma-elaboracao",
		data	: dadosProposta,
		success	: function(retorno){
			validaAccordion();
			alertMsg(retorno);
		}
	});
}

function abrePopupResumoSolicitacaoServico()
{
    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_FECHAR+'": '+function(){closeDialog('dialog_detalhe_solicitacao_servico');}+'};');
    loadDialog({
        id       : 'dialog_detalhe_solicitacao_servico',	//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_DETALHE_SOLICITACAO_SERVICO, // titulo do pop-up
        objQuery : $('#div_desc_solicitacao_servico'),      //obj a ser renderizado
        height   : 300,                                     // altura do pop-up
        buttons  : buttons
    });
}