$(document).ready(function() {

	var objJQueryCmbRequisito = $('#cmb_requisitos_associar_requisito_regra');
	
	$('#btn_associar_regra_versao_anterior').click(function(){
		if(objJQueryCmbRequisito.val() != 0 && objJQueryCmbRequisito.val() != null){
			if( $('#regras_2').text() == "" ){
				showToolTip(i18n.L_VIEW_SCRIPT_SEM_REGRA_ASSOCIADA_REQUISITO,$('#regras_2'));
			}else{
				associarRegraVersaoAnterior();	
			}
		}else{
			showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,objJQueryCmbRequisito);
		}
	});
	
	//carrega os dados associativos caso ocorrer um reload e o combo estiver selecionado
	if(objJQueryCmbRequisito.val() != 0 && objJQueryCmbRequisito.val() != null){
		pesquisaAssociacaoRegraRequisito();
	}
	
	objJQueryCmbRequisito.change(function(){
		if($(this).val() != 0){
			pesquisaAssociacaoRegraRequisito();
		}else{
			limpaSeletores();
		}
	});
	
	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#add_associacao_requisito").click(function() {
		
		//verifica se foi selecionado alguma opção no combo 
		if( objJQueryCmbRequisito.val() == 0 ){
			return false;
		}
		
		var count = 0;
		var regras = "[\""; 
		$('#regras_1 option:selected').each(function() {
			regras += (regras == "[\"") ? $(this).val() : "\",\"" + $(this).val();
			count++;
		});
		regras += "\"]";

		if(count==0){
			alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_REGRA_ASSOCIAR);
			return false;
		}
		
		$.ajax({
			type: "POST",
			url: systemName+"/associar-requisito-regra-negocio/associa-regra-requisito",
			data: $("#form_associa_requisito_regra :input").not('#regras_1').not('#regras_2').serialize()+
				  "&cd_projeto="+$('#cd_projeto').val()+"&regras="+regras,
			success: function(retorno){
				// apos atualizar as tabelas, atualiza os selects
				pesquisaAssociacaoRegraRequisito();
			}
		});
	});

	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#remove_associacao_requisito").click(function() {
		//verifica se foi selecionado alguma opção no combo 
		if( objJQueryCmbRequisito.val() == 0 ){
			return false;
		}
		
		var count = 0;
		var regras = "[\""; 
		$('#regras_2 option:selected').each(function() {
			regras += (regras == "[\"") ? $(this).val() : "\",\"" + $(this).val();
			count++;
		});
		regras += "\"]";

		if(count==0){
			alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_REGRA_DESASSOCIAR);
			return false;
		}
		
		$.ajax({
			type: "POST",
			url: systemName+"/associar-requisito-regra-negocio/desassocia-regra-requisito",
			data: $("#form_associa_requisito_regra :input").not('#regras_1').not('#regras_2').serialize()+
				  "&cd_projeto="+$('#cd_projeto').val()+"&regras="+regras,
			success: function(retorno){
				// apos atualizar as tabelas, atualiza os selects
				pesquisaAssociacaoRegraRequisito();
			}
		});
	});	
});

function pesquisaAssociacaoRegraRequisito()
{
	var dadosCombo  = $('#cmb_requisitos_associar_requisito_regra').val();
	var cd_projeto  = $('#cd_projeto').val();
	
	var arr 				= dadosCombo.split("|");
	var cd_requisito 		= $.trim(arr[0]); 
	var dt_versao_requisito = $.trim(arr[1]); 
	var ni_versao_requisito	= $.trim(arr[2]);
	
	$.ajax({
		type     : 'POST',
		url      : systemName+"/associar-requisito-regra-negocio/pesquisa-associacao-regra-requisito",
		data     : "cd_projeto="+cd_projeto+"&cd_requisito="+cd_requisito+"&dt_versao="+dt_versao_requisito,
		dataType : 'json',
		success  : function(retorno){
			var naoAssociada = retorno[0];
			var associada	 = retorno[1];

			if(associada.length == 0 ){
				pesquisaAssociacaoVersaoAnteriorRequisito( cd_requisito, dt_versao_requisito, ni_versao_requisito );
			}
			
			$("#regras_1").html(naoAssociada);
			$("#regras_2").html(associada);
		}
	});
}

function pesquisaAssociacaoVersaoAnteriorRequisito( cd_requisito, dt_versao_requisito, ni_versao_requisito )
{
	$.ajax({
		type     : 'POST',
		url      : systemName+"/associar-requisito-regra-negocio/pesquisa-associacao-versao-anterior-requisito",
		data     : "cd_requisito="+cd_requisito+"&dt_versao="+dt_versao_requisito+"&cd_projeto="+$('#cd_projeto').val(),
		dataType : 'json',
		success  : function(retorno){
			
			if(retorno['comMsg']){
				confirmMsg(retorno['msg'],function(){
					atualizaDadosVersaoRequisito( retorno['arrDados'], cd_requisito, dt_versao_requisito, ni_versao_requisito );
				});
			}
		}
	});
}

function atualizaDadosVersaoRequisito( arrAtualizacao, cd_requisito, dt_versao_requisito, ni_versao_requisito )
{
	$.ajax({
		type     : 'POST',
		url      : systemName+"/associar-requisito-regra-negocio/atualiza-dados-versao-requisito",
		data     : "cd_requisito="+cd_requisito+"&dt_versao="+dt_versao_requisito+"&ni_versao_requisito="+ni_versao_requisito+
					"&cd_projeto="+$('#cd_projeto').val()+"&arrAtualizacao="+arrAtualizacao,
		dataType : 'json',
		success  : function(retorno){
			if(retorno['error'] == true){
	            alertMsg(retorno['msg'],retorno['errorType']);
	        }else{
	            alertMsg(retorno['msg']);
	            pesquisaAssociacaoRegraRequisito();
	        }
		}
	});
}

function associarRegraVersaoAnterior()
{
	var dadosCombo  = $('#cmb_requisitos_associar_requisito_regra').val();
	var cd_projeto  = $('#cd_projeto').val();
	
	var arr 				= dadosCombo.split("|");
	var cd_requisito 		= $.trim(arr[0]); 
	var dt_versao_requisito = $.trim(arr[1]); 
	
	$.ajax({
		type     : 'POST',
		url      : systemName+"/associar-requisito-regra-negocio/atualiza-dados-versao-regra-negocio",
		data     : "cd_requisito="+cd_requisito+"&dt_versao="+dt_versao_requisito+"&cd_projeto="+cd_projeto,
		dataType : 'json',
		success  : function(retorno){
			if(retorno['error'] == true){
	            alertMsg(retorno['msg'],retorno['errorType']);
	        }else{
	            alertMsg(retorno['msg']);
	            pesquisaAssociacaoRegraRequisito();
	        }
		}
	});
}

function limpaSeletores()
{
	$('#regras_1').empty();
	$('#regras_2').empty();
}