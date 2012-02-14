$(document).ready(function() {
	
	//pega o click do botão cancelar decisao
	$("#btn_cancelar_decisao").click(function() {
		desabilitaAbaDecisao();
	});
	//pega o click do botão salvar decisao
	$("#btn_salvar_decisao").click(function() {
		salvarDecisao();
	});
});

function montaGridDecisaoMedicao()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/medicao/grid-decisao-medicao",
		success	: function(retorno){
			// atualiza a grid
			$("#gridDecisaoMedicao").html(retorno);
		}
	});
}

function gerarDecisao(dt_analise, cd_medicao, cd_box_inicio)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/medicao/recupera-dados-analise-medicao",
		data	: "cd_medicao="+cd_medicao+"&cd_box_inicio="+cd_box_inicio+"&dt_analise="+dt_analise,
		dataType: 'json',
		success	: function(retorno){
			$('#cd_medicao'		    ).val(retorno['cd_medicao']);
			$('#cd_box_inicio'	    ).val(retorno['cd_box_inicio']);
			$('#hidden_dt_analise'  ).val(retorno['dt_analise_medicao']);
			
			$('#desc_medicao'		).html(retorno['tx_medicao']);
			$('#desc_box'			).html(retorno['tx_titulo_box_inicio']);
			$('#desc_resultado'		).html(retorno['tx_resultado_analise_medicao']);
			$('#desc_dados_medicao'	).html(retorno['tx_dados_medicao']);

			$('#tx_decisao'			).val(retorno['tx_decisao']);
			$('#dt_decisao'			).val(retorno['dt_decisao_mask']);
			
			habilitaAbaDecisao();
		}
	});
}

function salvarDecisao()
{
	if( !validaForm('#conteiner_decisao') ){return false;}
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/medicao/salvar-decisao",
		data	: $("#conteiner_decisao :input").serialize()+
                  "&dt_analise="+$('#hidden_dt_analise').val()+
                  "&cd_medicao="+$('#cd_medicao').val()+
                  "&cd_box_inicio="+$('#cd_box_inicio').val(),
		success	: function(retorno){
			alertMsg(retorno);
			montaGridAnaliseMedicao();
			montaGridDecisaoMedicao();
			limpaInputsAbaDecisao();
		}
	});
}

function habilitaAbaDecisao()
{
	$("#li_aba_decisao_medicao").show();
	$('#container-medicao').triggerTab(5);
}

function desabilitaAbaDecisao()
{
	$("#li_aba_decisao_medicao").hide();
	$('#container-medicao').triggerTab(3);
	$('#tx_decisao').val('');
	$('#dt_decisao').val('');
}

function limpaInputsAbaDecisao()
{
	$('#desc_medicao'		).html('');
	$('#desc_box'			).html('');
	$('#desc_resultado'		).html('');
	$('#desc_dados_medicao'	).html('');
	$('#tx_decisao'			).val('');
	$('#dt_decisao'			).val('');
	desabilitaAbaDecisao();
}