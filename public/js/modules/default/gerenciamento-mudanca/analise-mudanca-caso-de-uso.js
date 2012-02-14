$(document).ready(function() { 
	$("#mesAnaliseMudancaCasoDeUso").change(function() {
		if ($("#mesAnaliseMudancaCasoDeUso").val() != "0") {
			montaGridGerenciamentoMudancaCasoDeUso();
			apresentaData($("#mesAnaliseMudancaCasoDeUso").val(),$("#anoAnaliseMudancaCasoDeUso").val(),"mesAnoAnaliseMudancaCasoDeUso");
		} else {
			apresentaData($("#mesAnaliseMudancaCasoDeUso").val(),$("#anoAnaliseMudancaCasoDeUso").val(),"mesAnoAnaliseMudancaCasoDeUso");
		}
	});
	
	$("#anoAnaliseMudancaCasoDeUso").change(function() {
		if ($("#anoAnaliseMudancaCasoDeUso").val() != "0") {
			montaGridGerenciamentoMudancaCasoDeUso();
			apresentaData($("#mesAnaliseMudancaCasoDeUso").val(),$("#anoAnaliseMudancaCasoDeUso").val(),"mesAnoAnaliseMudancaCasoDeUso");
		} else {
			apresentaData($("#mesAnaliseMudancaCasoDeUso").val(),$("#anoAnaliseMudancaCasoDeUso").val(),"mesAnoAnaliseMudancaCasoDeUso");
		}
	});
});


function montaGridGerenciamentoMudancaCasoDeUso()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/gerenciamento-mudanca-caso-de-uso/grid-gerenciamento-mudanca-caso-de-uso",
		data	: "mes="+$("#mesAnaliseMudancaCasoDeUso").val()+"&ano="+$("#anoAnaliseMudancaCasoDeUso").val(),
		success : function(retorno){
			// atualiza a grid
			$("#gridGerenciamentoMudancaCasoDeUso").html(retorno);
			$("#gridGerenciamentoMudancaCasoDeUso").show();
		}
	});
}

function cadastrarDecisaoMudancaCasoDeUso(cd_projeto, cd_item_controle_baseline, dt_gerencia_mudanca, cd_item_controlado, dt_versao_item_controlado, tx_projeto, tx_caso_de_uso)
{
	$("#desc_caso_de_uso_gerenciamento_mudanca").html(tx_caso_de_uso);
	
	getDadosGerenciaMudanca('caso_de_uso',cd_projeto, cd_item_controle_baseline, dt_gerencia_mudanca, cd_item_controlado, dt_versao_item_controlado, tx_projeto);

	//captura o nr da aba que está em uso e guarda no hidden
	$('#id_tab_da_acao_gerenciamento_mudanca').val($('#container-gerenciamento-mudanca').activeTab());

	//habilita os campos hiddens de acordo com a aba escolhida
	habilitaCamposAbaCadastroDecisao('caso_de_uso');
}


function geraNovaVersaoCasoDeUso( cd_projeto, cd_item_controle_baseline, dt_gerencia_mudanca, cd_item_controlado, dt_versao_item_controlado )
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/gerenciamento-mudanca-caso-de-uso/salva-nova-versao-caso-de-uso",
		data	: "cd_projeto="					+cd_projeto+
				  "&cd_caso_de_uso="			+cd_item_controlado+
				  "&dt_versao_caso_de_uso="		+dt_versao_item_controlado+
				  "&cd_item_controle_baseline="	+cd_item_controle_baseline+
				  "&dt_gerencia_mudanca="		+dt_gerencia_mudanca+
				  "&cd_item_controlado="		+cd_item_controlado+
				  "&dt_versao_item_controlado="	+dt_versao_item_controlado,
		dataType: 'json',
		success : function(retorno){
            if(retorno['error'] == true){
	            alertMsg(retorno['msg'],retorno['type']);
	        }else{
	            alertMsg(retorno['msg'],retorno['type']);
                // atualiza a grid
                montaGridGerenciamentoMudancaCasoDeUso();
	        }
		}
	});
}