$(document).ready(function() { 
	$("#mesAnaliseMudancaRegraDeNegocio").change(function() {
		if ($("#mesAnaliseMudancaRegraDeNegocio").val() != "0") {
			montaGridGerenciamentoMudancaRegraDeNegocio();
			apresentaData($("#mesAnaliseMudancaRegraDeNegocio").val(),$("#anoAnaliseMudancaRegraDeNegocio").val(),"mesAnoAnaliseMudancaRegraDeNegocio");
		} else {
			apresentaData($("#mesAnaliseMudancaRegraDeNegocio").val(),$("#anoAnaliseMudancaRegraDeNegocio").val(),"mesAnoAnaliseMudancaRegraDeNegocio");
		}
	});
	
	$("#anoAnaliseMudancaRegraDeNegocio").change(function() {
		if ($("#anoAnaliseMudancaRegraDeNegocio").val() != "0") {
			montaGridGerenciamentoMudancaRegraDeNegocio();
			apresentaData($("#mesAnaliseMudancaRegraDeNegocio").val(),$("#anoAnaliseMudancaRegraDeNegocio").val(),"mesAnoAnaliseMudancaRegraDeNegocio");
		} else {
			apresentaData($("#mesAnaliseMudancaRegraDeNegocio").val(),$("#anoAnaliseMudancaRegraDeNegocio").val(),"mesAnoAnaliseMudancaRegraDeNegocio");
		}
	});
});


function montaGridGerenciamentoMudancaRegraDeNegocio()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/gerenciamento-mudanca-regra-de-negocio/grid-gerenciamento-mudanca-regra-de-negocio",
		data	: "mes="+$("#mesAnaliseMudancaRegraDeNegocio").val()+"&ano="+$("#anoAnaliseMudancaRegraDeNegocio").val(),
		success : function(retorno){
			// atualiza a grid
			$("#gridGerenciamentoMudancaRegraDeNegocio").html(retorno);
			$("#gridGerenciamentoMudancaRegraDeNegocio").show();
		}
	});
}

function cadastrarDecisaoMudancaRegraDeNegocio(cd_projeto, cd_item_controle_baseline, dt_gerencia_mudanca, cd_item_controlado, dt_versao_item_controlado, tx_projeto, tx_regra_de_negocio)
{
	$("#desc_regra_negocio_gerenciamento_mudanca").html(tx_regra_de_negocio);
	
	getDadosGerenciaMudanca('regra_de_negocio',cd_projeto, cd_item_controle_baseline, dt_gerencia_mudanca, cd_item_controlado, dt_versao_item_controlado, tx_projeto);

	//captura o nr da aba que está em uso e guarda no hidden
	$('#id_tab_da_acao_gerenciamento_mudanca').val($('#container-gerenciamento-mudanca').activeTab());

	//habilita os campos hiddens de acordo com a aba escolhida
	habilitaCamposAbaCadastroDecisao('regra_de_negocio');

}

function geraNovaVersaoRegraDeNegocio(cd_projeto, cd_item_controle_baseline, dt_gerencia_mudanca, cd_item_controlado, dt_versao_item_controlado )
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/gerenciamento-mudanca-regra-de-negocio/salva-nova-versao-regra-de-negocio",
		data	: "cd_projeto="					+cd_projeto+
				  "&cd_regra_negocio="			+cd_item_controlado+
				  "&dt_regra_negocio="			+dt_versao_item_controlado+
				  "&cd_item_controle_baseline="	+cd_item_controle_baseline+
				  "&dt_gerencia_mudanca="		+dt_gerencia_mudanca+
				  "&cd_item_controlado="		+cd_item_controlado+
				  "&dt_versao_item_controlado="	+dt_versao_item_controlado,
		success : function(retorno){
			// atualiza a grid
			alertMsg(retorno);
			montaGridGerenciamentoMudancaRegraDeNegocio();
		}
	});
}