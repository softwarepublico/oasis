$(document).ready(function() { 
	$("#mesAnaliseMudancaRequisito").change(function() {
		if ($("#mesAnaliseMudancaRequisito").val() != "0") {
			montaGridGerenciamentoMudancaRequisito();
			apresentaData($("#mesAnaliseMudancaRequisito").val(),$("#anoAnaliseMudancaRequisito").val(),"mesAnoAnaliseMudancaRequisito");
		} else {
			apresentaData($("#mesAnaliseMudancaRequisito").val(),$("#anoAnaliseMudancaRequisito").val(),"mesAnoAnaliseMudancaRequisito");
		}
	});
	
	$("#anoAnaliseMudancaRequisito").change(function() {
		if ($("#anoAnaliseMudancaRequisito").val() != "0") {
			montaGridGerenciamentoMudancaRequisito();
			apresentaData($("#mesAnaliseMudancaRequisito").val(),$("#anoAnaliseMudancaRequisito").val(),"mesAnoAnaliseMudancaRequisito");
		} else {
			apresentaData($("#mesAnaliseMudancaRequisito").val(),$("#anoAnaliseMudancaRequisito").val(),"mesAnoAnaliseMudancaRequisito");
		}
	});
});


function montaGridGerenciamentoMudancaRequisito()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/gerenciamento-mudanca-requisito/grid-gerenciamento-mudanca-requisito",
		data	: "mes="+$("#mesAnaliseMudancaRequisito").val()+"&ano="+$("#anoAnaliseMudancaRequisito").val(),
		success : function(retorno){
			// atualiza a grid
			$("#gridGerenciamentoMudancaRequisito").html(retorno);
			$("#gridGerenciamentoMudancaRequisito").show();
		}
	});
}

function cadastrarDecisaoMudancaRequisito(cd_projeto, cd_item_controle_baseline, dt_gerencia_mudanca, cd_item_controlado, dt_versao_item_controlado, tx_projeto, tx_requisito)
{
	$("#desc_requisito_gerenciamento_mudanca").html(tx_requisito);
	
	getDadosGerenciaMudanca('requisito',cd_projeto, cd_item_controle_baseline, dt_gerencia_mudanca, cd_item_controlado, dt_versao_item_controlado, tx_projeto);

	//captura o nr da aba que está em uso e guarda no hidden
	$('#id_tab_da_acao_gerenciamento_mudanca').val($('#container-gerenciamento-mudanca').activeTab());

	//habilita os campos hiddens de acordo com a aba escolhida
	habilitaCamposAbaCadastroDecisao('requisito');
}

function geraNovaVersaoRequisito(ni_versao_requisito, cd_projeto, cd_item_controle_baseline, dt_gerencia_mudanca, cd_item_controlado, dt_versao_item_controlado )
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/gerenciamento-mudanca-requisito/salva-nova-versao-requisito",
		data	: "cd_projeto="					+cd_projeto+
				  "&cd_requisito="				+cd_item_controlado+
				  "&dt_versao_requisito="		+dt_versao_item_controlado+
				  "&ni_versao_requisito="		+ni_versao_requisito+
				  "&cd_item_controle_baseline="	+cd_item_controle_baseline+
				  "&dt_gerencia_mudanca="		+dt_gerencia_mudanca+
				  "&cd_item_controlado="		+cd_item_controlado+
				  "&dt_versao_item_controlado="	+dt_versao_item_controlado,
		success : function(retorno){
			// atualiza a grid
			alertMsg(retorno);
			montaGridGerenciamentoMudancaRequisito();
		}
	});
}