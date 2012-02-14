$(document).ready(function() { 
	$('#container-medicao').show().tabs();
	$('#container-medicao').triggerTab(1);
});

function verificaConfigAccordionMedicao()
{
	if( $("#config_hidden_medicao"). val() === 'N' ){

		$('#container-medicao').triggerTab(1);
		escondeAbasHidden();
		//funcao encontra-se em medicoes.js
		montaGridMedicoes();
		//funcao encontra-se em analise-medicao.js
		montaGridAnaliseMedicao();
		//funcao encontra-se em decisao.js
		montaGridDecisaoMedicao();
		//funcao encontra-se em medicao-medida.js
		carregaComboMedicaoMedida();

		$("#config_hidden_medicao"). val('S');
	}
}

function escondeAbasHidden()
{
	$("#li_aba_nova_medicao").hide();
	$("#li_aba_nova_analise_medicao").hide();
}
