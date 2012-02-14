$(document).ready(function(){

	var objJQueryCmbRequisito = $('#cmb_requisitos_dependencia');
	
	$('#btn_atualizar_versao_requisito_dependente').click(function(){
		
		if(objJQueryCmbRequisito.val() != 0 && objJQueryCmbRequisito.val() != null){
			if( $('#requisitos_2').text() == "" ){
				showToolTip(i18n.L_VIEW_SCRIPT_SEM_DEPENDENCIA_PARA_ATUALIZAR, $('#requisitos_2'));
			}else{
				associarRequisitoVersaoAnterior();
			}
		}else{
			showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO, objJQueryCmbRequisito);
		}
	});
	
	//carrega os dados associativos caso ocorrer um reload e o combo estiver selecionado
	if(objJQueryCmbRequisito.val() != 0 && objJQueryCmbRequisito.val() != null){
		pesquisaAssociacaoDependenciaRequisito();
	}
	
	objJQueryCmbRequisito.change(function(){
		if($(this).val() != 0){
			pesquisaAssociacaoDependenciaRequisito();
		}else{
			limpaSeletoresAbaDependenciaRequisito()
		}
	});


	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#add_dependencia").click(function() {
		
		//verifica se foi selecionado alguma opção no combo 
		if( objJQueryCmbRequisito.val() == 0 ){
			return false;
		}
		
		var count = 0;
		var requisitos = "[\""; 
		$('#requisitos_1 option:selected').each(function() {
			requisitos += (requisitos == "[\"") ? $(this).val() : "\",\"" + $(this).val();
			count++;
		});
		requisitos += "\"]";

		if(count==0){
			alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_REQUISITO_ASSOCIAR);
			return false;
		}
		
		$.ajax({
			type	: "POST",
			url		: systemName+"/requisito-proposta/associa-dependencia-requisito",
			data	: $("#div_dependencia_requisito :input").not('#requisitos_1').not('#requisitos_2').serialize()+
					   "&cd_projeto="+$('#cd_projeto').val()+
                       "&requisitos="+requisitos,
			success: function(retorno){
				// apos atualizar as tabelas, atualiza os selects
				pesquisaAssociacaoDependenciaRequisito();
			}
		});
	});

	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#remove_dependencia").click(function() {
		//verifica se foi selecionado alguma opção no combo 
		if( objJQueryCmbRequisito.val() == 0 ){
			return false;
		}
		
		var count = 0;
		var requisitos = "[\""; 
		$('#requisitos_2 option:selected').each(function() {
			requisitos += (requisitos == "[\"") ? $(this).val() : "\",\"" + $(this).val();
			count++;
		});
		requisitos += "\"]";

		if(count==0){
			alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_REQUISITO_DESASSOCIAR);
			return false;
		}
		
		$.ajax({
			type	: "POST",
			url		: systemName+"/requisito-proposta/desassocia-dependencia-requisito",
			data	: $("#div_dependencia_requisito :input").not('#requisitos_1').not('#requisitos_2').serialize()+
					  "&cd_projeto="+$('#cd_projeto').val()+
                      "&requisitos="+requisitos,
			success	: function(retorno){
				// apos atualizar as tabelas, atualiza os selects
				pesquisaAssociacaoDependenciaRequisito();
			}
		});
	});	
});

function pesquisaAssociacaoDependenciaRequisito()
{
	var dadosCombo  = $('#cmb_requisitos_dependencia').val();
	var cd_projeto  = $('#cd_projeto').val();
	
	var arr 				= dadosCombo.split("|");
	var cd_requisito 		= $.trim(arr[0]); 
	var dt_versao_requisito = $.trim(arr[1]); 
	var ni_versao_requisito = $.trim(arr[2]); 
	
	$.ajax({
		type     : 'POST',
		url      : systemName+"/requisito-proposta/pesquisa-associacao-dependencia-requisito",
		data     : "cd_projeto="+cd_projeto+"&cd_requisito="+cd_requisito+"&dt_versao="+dt_versao_requisito,
		dataType : 'json',
		success  : function(retorno){
			var naoAssociado = retorno[0];
			var associado	 = retorno[1];

			if(associado.length == 0 && ni_versao_requisito > 1 ){
				pesquisaAssociacaoVersaoAnteriorRequisito( cd_requisito, dt_versao_requisito, ni_versao_requisito );
			}
			
			$("#requisitos_1").html(naoAssociado);
			$("#requisitos_2").html(associado);
		}
	});
}

function pesquisaAssociacaoVersaoAnteriorRequisito( cd_requisito, dt_versao_requisito, ni_versao_requisito )
{
	$.ajax({
		type     : 'POST',
		url      : systemName+"/requisito-proposta/pesquisa-associacao-versao-anterior-requisito",
		data     : "cd_requisito="+cd_requisito+
                   "&dt_versao="+dt_versao_requisito+
                   "&ni_versao_requisito="+ni_versao_requisito+
                   "&cd_projeto="+$('#cd_projeto').val(),
		dataType : 'json',
		success  : function(retorno){
			if(retorno['comMsg']){
				confirmMsg(retorno['msg'],function(){
					atualizaDadosVersaoRequisito( retorno['arrDados'], cd_requisito, dt_versao_requisito );
				});
			}
		}
	});
}

function atualizaDadosVersaoRequisito( arrAtualizacao, cd_requisito, dt_versao_requisito )
{
	$.ajax({
		type     : 'POST',
		url      : systemName+"/requisito-proposta/atualiza-dados-versao-requisito-pai",
		data     : "cd_requisito="+cd_requisito+
                   "&dt_versao="+dt_versao_requisito+
                   "&cd_projeto="+$('#cd_projeto').val()+
                   "&arrAtualizacao="+arrAtualizacao,
		dataType : 'json',
		success  : function(retorno){}
	});
	pesquisaAssociacaoDependenciaRequisito();
}

function associarRequisitoVersaoAnterior()
{
	var dadosCombo  = $('#cmb_requisitos_dependencia').val();
	var cd_projeto  = $('#cd_projeto').val();
	
	var arr 				= dadosCombo.split("|");
	var cd_requisito 		= $.trim(arr[0]); 
	var dt_versao_requisito = $.trim(arr[1]); 
	var ni_versao_requisito = $.trim(arr[2]); 
	
	$.ajax({
		type     : 'POST',
		url      : systemName+"/requisito-proposta/atualiza-dados-versao-requisito-dependente",
		data     : "cd_requisito="+cd_requisito+
                   "&dt_versao="+dt_versao_requisito+
                   "&ni_versao_requisito="+ni_versao_requisito+
                   "&cd_projeto="+cd_projeto,
		success  : function(retorno){
			alertMsg(retorno);
			pesquisaAssociacaoDependenciaRequisito();
		}
	});	
}

function limpaSeletoresAbaDependenciaRequisito()
{
	$('#cmb_requisitos_dependencia'	).val(0);
	$('#requisitos_1'				).empty();
	$('#requisitos_2'				).empty();
}