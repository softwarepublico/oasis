
$(document).ready(function() {
	var objJQueryCmbRequisito = $('#cmb_requisitos_associar_requisito_caso_de_uso');
	
	$('#btn_associar_caso_de_uso_versao_anterior').click(function(){
		if(objJQueryCmbRequisito.val() != 0 && objJQueryCmbRequisito.val() != null){
			if( $('#caso_de_uso_2').text() == "" ){
				showToolTip(i18n.L_VIEW_SCRIPT_SEM_CASO_DE_USO_ASSOCIADO_REQUISITO, $('#caso_de_uso_2'));
			}else{
				associarCasoDeUsoVersaoAnterior();
			}
		}else{
			showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,objJQueryCmbRequisito);
		}
	});

	//carrega os dados associativos caso ocorrer um reload e o combo estiver selecionado
	if(objJQueryCmbRequisito.val() != 0 && objJQueryCmbRequisito.val() != null){
		pesquisaAssociacaoCasoDeUsoRequisito();
	}

	objJQueryCmbRequisito.change(function(){
		if($(this).val() != 0){
			pesquisaAssociacaoCasoDeUsoRequisito();
		}else{
			limpaSeletoresCasoDeUso();
		}
	});

	
	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#add_associacao_requisito_caso_de_uso").click(function() {
		
		//verifica se foi selecionado alguma opção no combo 
		if( objJQueryCmbRequisito.val() == 0 ){
			return false;
		}
		
		var count 	   = 0;
		var casosDeUso = "[\""; 
		$('#caso_de_uso_1 option:selected').each(function() {
			casosDeUso += (casosDeUso == "[\"") ? $(this).val() : "\",\"" + $(this).val();
			count++;
		});
		casosDeUso += "\"]";

		if(count==0){
			alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_CASO_DE_USO_ASSOCIAR);
			return false;
		}
		
		$.ajax({
			type: "POST",
			url: systemName+"/associar-requisito-caso-de-uso/associa-caso-de-uso-requisito",
			data: $("#form_associa_requisito_caso_de_uso :input").not('#caso_de_uso_1').not('#caso_de_uso_2').serialize()+
				  "&cd_projeto="+$('#cd_projeto').val()+"&casos_de_uso="+casosDeUso,
			success: function(retorno){
				// apos atualizar as tabelas, atualiza os selects
				pesquisaAssociacaoCasoDeUsoRequisito();
			}
		});
	});


	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#remove_associacao_requisito_caso_de_uso").click(function() {
		//verifica se foi selecionado alguma opção no combo 
		if( objJQueryCmbRequisito.val() == 0 ){
			return false;
		}
		
		var count = 0;
		var casosDeUso = "[\""; 
		$('#caso_de_uso_2 option:selected').each(function() {
			casosDeUso += (casosDeUso == "[\"") ? $(this).val() : "\",\"" + $(this).val();
			count++;
		});
		casosDeUso += "\"]";

		if(count==0){
			alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_CASO_DE_USO_DESASSOCIAR);
			return false;
		}
		
		$.ajax({
			type: "POST",
			url: systemName+"/associar-requisito-caso-de-uso/desassocia-caso-de-uso-requisito",
			data: $("#form_associa_requisito_caso_de_uso :input").not('#caso_de_uso_1').not('#caso_de_uso_2').serialize()+
				  "&cd_projeto="+$('#cd_projeto').val()+"&casos_de_uso="+casosDeUso,
			success: function(retorno){
				// apos atualizar as tabelas, atualiza os selects
				pesquisaAssociacaoCasoDeUsoRequisito();
			}
		});
	});
});


function pesquisaAssociacaoCasoDeUsoRequisito()
{
	var dadosCombo  		= $('#cmb_requisitos_associar_requisito_caso_de_uso').val();
	var cd_projeto  		= $('#cd_projeto').val();
	
	var arrDadosCombo 		= dadosCombo.split("|");
	var cd_requisito 		= $.trim(arrDadosCombo[0]); 
	var dt_versao_requisito = $.trim(arrDadosCombo[1]); 
	var ni_versao_requisito	= $.trim(arrDadosCombo[2]); 
	
	$.ajax({
		type     : 'POST',
		url      : systemName+"/associar-requisito-caso-de-uso/pesquisa-associacao-caso-de-uso",
		data     : "cd_projeto="+cd_projeto+"&cd_requisito="+cd_requisito+"&dt_versao="+dt_versao_requisito,
		dataType : 'json',
		success  : function(retorno){
			var naoAssociado = retorno[0];
			var associado	 = retorno[1];

			if(associado.length == 0 ){
				pesquisaAssociacaoRequisitoVersaoAnterior( cd_requisito, dt_versao_requisito, ni_versao_requisito );
			}
			$("#caso_de_uso_1").html(naoAssociado);
			$("#caso_de_uso_2").html(associado);
		}
	});
}


function pesquisaAssociacaoRequisitoVersaoAnterior( cd_requisito, dt_versao_requisito, ni_versao_requisito )
{
	$.ajax({
		type     : 'POST',
		url      : systemName+"/associar-requisito-caso-de-uso/pesquisa-associacao-versao-anterior-requisito",
		data     : "cd_requisito="+cd_requisito+"&dt_versao="+dt_versao_requisito+"&cd_projeto="+$('#cd_projeto').val(),
		dataType : 'json',
		success  : function(retorno){
			
			if(retorno['comMsg']){
				confirmMsg(retorno['msg'],function(){
					atualizaDadosVersaoRequisitoCasoDeUso( retorno['arrDados'], cd_requisito, dt_versao_requisito, ni_versao_requisito )
				});
			}
		}
	});
}


function atualizaDadosVersaoRequisitoCasoDeUso( arrAtualizacao, cd_requisito, dt_versao_requisito, ni_versao_requisito )
{
	$.ajax({
		type     : 'POST',
		url      : systemName+"/associar-requisito-caso-de-uso/atualiza-dados-versao-requisito",
		data     : "cd_requisito="+cd_requisito+"&dt_versao="+dt_versao_requisito+"&ni_versao_requisito="+ni_versao_requisito+
					"&cd_projeto="+$('#cd_projeto').val()+"&arrAtualizacao="+arrAtualizacao,
		dataType : 'json',
		success  : function(retorno){
			if(retorno['error'] == true){
	            alertMsg(retorno['msg'],retorno['errorType']);
	        }else{
	            alertMsg(retorno['msg']);
	            pesquisaAssociacaoCasoDeUsoRequisito();
	        }
		}
	});
}

function associarCasoDeUsoVersaoAnterior()
{
	var dadosCombo  = $('#cmb_requisitos_associar_requisito_caso_de_uso').val();
	var cd_projeto  = $('#cd_projeto').val();
	
	var arr 				= dadosCombo.split("|");
	var cd_requisito 		= $.trim(arr[0]); 
	var dt_versao_requisito = $.trim(arr[1]); 
	
	$.ajax({
		type     : 'POST',
		url      : systemName+"/associar-requisito-caso-de-uso/atualiza-dados-versao-caso-de-uso",
		data     : "cd_requisito="+cd_requisito+"&dt_versao="+dt_versao_requisito+"&cd_projeto="+cd_projeto,
		dataType : 'json',
		success  : function(retorno){
			
			if(retorno['error'] == true){
	            alertMsg(retorno['msg'],retorno['errorType']);
	        }else{
	            alertMsg(retorno['msg']);
	            pesquisaAssociacaoCasoDeUsoRequisito();
	        }
		}
	});
}

function limpaSeletoresCasoDeUso()
{
	$('#caso_de_uso_1').empty();
	$('#caso_de_uso_2').empty();
}