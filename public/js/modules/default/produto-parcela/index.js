$(document).ready(function(){
	$("#salvarProdutoParcela"	).hide();
	$("#alterarProdutoParcela"	).hide();
	$("#cancelarProdutoParcela"	).hide();
});

/**
 * Configuração da inicialização da tela
 */
function configAcrescentarProdutos()
{
	limparTela();
	$("#numeroParcela").html('XX');
	$("#quantHoras"	  ).html('XX');
	$("#config_hidden_acrescentar_produto").val("S");
}

function verificaStatusAccordionProdutos()
{
	if( $("#config_hidden_produtos" ).val() === "N" ){
		$("#numeroParcela"			).html('XX');
		$("#quantHoras"				).html('XX');
		limparTela();
	}
}

function ajaxParcelaComProduto()
{
	limparTelaSemSalvar();
	$.ajax({
		type	: "POST",
		url		: systemName+"/produto-parcela/parcela-com-produtos",
		data	: "cd_projeto="+$("#cd_projeto").val()
				 +"&cd_proposta=" + $("#cd_proposta").val(),
		success	: function(retorno){
			$("#parcelaComProduto").html(retorno);
			
			if( $("#config_hidden_produtos").val() === "N" ){
				$("#config_hidden_produtos").val("S");
			}
		}
	});
}

function ajaxParcelaSemProduto()
{
	limparTelaSemSalvar();
	$.ajax({
		type	: "POST",
		url		: systemName+"/produto-parcela/parcela-sem-produtos",
		data	: "cd_projeto=" + $("#cd_projeto").val()
				 +"&cd_proposta=" + $("#cd_proposta").val(),
		success	: function(retorno){
			$("#parcelaSemProduto").html(retorno);
		}
	});
}

function ajaxCriarProdutoParcela(cd_parcela,ni_parcela,ni_horas_parcela)
{
	$("#gridProdutoParcela"		).show();
	$("#numeroParcela"			).html(ni_parcela);
	$("#quantHoras"				).html(ni_horas_parcela);
	$("#cd_parcela_produto_cad"	).val(cd_parcela);
	$.ajax({
		type	: "POST",
		url		: systemName+"/produto-parcela/pesquisa-produto-parcela",
		data	: "cd_projeto=" + $("#cd_projeto").val()
				 +"&cd_proposta=" + $("#cd_proposta").val()
				 +"&cd_parcela="+cd_parcela
				 +"&escondeExclusao="+$('#escondeExclusao').val(),
		success: function(retorno){
			$("#gridProdutoParcela").html(retorno);
			//limparTela();
		}
	});
}

function excluirProdutoParcela(cd_produto_parcela)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/produto-parcela/excluir-produto-parcela",
		data	: "cd_produto_parcela=" + cd_produto_parcela,
		success	: function(retorno){
			alertMsg(retorno);
			ajaxCriarProdutoParcela($("#cd_parcela_produto_cad").val());
			limparTela();
		}
	});	
}

function validaProdutoParcela()
{
	if ($("#descProdutoParcela").val() == ""){
		showToolTip(i18n.L_VIEW_SCRIPT_INSIRA_PRODUTO, $("#descProdutoParcela"));
		return false;
	}
	
	if ($("#cd_tipo_produto").val() == 0){
		showToolTip(i18n.L_VIEW_SCRIPT_SELECIONE_TIPO_PRODUTO, $("#cd_tipo_produto"));
		return false;
	}
	return true;
}

function salvarProdutoParcela()
{
	if( !validaProdutoParcela() ){ return false}
	$.ajax({
		type	: "POST",
		url		: systemName+"/produto-parcela/salvar-produto-parcela",
		data	: "tx_produto_parcela=" + $("#descProdutoParcela").val()
				 +"&cd_projeto=" + $("#cd_projeto").val()
			     +"&cd_proposta=" + $("#cd_proposta").val()
			     +"&cd_parcela="+$("#cd_parcela_produto_cad").val()
			     +"&cd_tipo_produto="+$("#cd_tipo_produto").val(),
		success	: function(retorno){
			alertMsg(retorno);
			ajaxCriarProdutoParcela($("#cd_parcela_produto_cad").val());
			limparTela();			
		}
	});
}

function recuperarProdutoParcela(cd_produto_parcela)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/produto-parcela/recupera-produto-parcela",
		data	: "cd_produto_parcela=" + cd_produto_parcela,
		dataType:'json',
		success	: function(retorno){
			var retorno1 = (retorno[1] == "" || retorno[1] == null)? 0 : retorno[1];
			$("#descProdutoParcela"		).val(retorno[0]);
			$("#cd_tipo_produto"		).val(retorno1);
			$("#cd_produto_parcela"		).val(retorno[2]);
			$("#salvarProdutoParcela"	).hide();
			$("#alterarProdutoParcela"	).show();
			$("#cancelarProdutoParcela"	).show();
		}
	});	
}

function alterarProdutoParcela()
{
	if( !validaProdutoParcela() ){ return false}
	
	$.ajax({
		type: "POST",
		url: systemName+"/produto-parcela/alterar-produto-parcela",
		data: "tx_produto_parcela=" + $("#descProdutoParcela").val()
		      +"&cd_produto_parcela=" + $("#cd_produto_parcela").val()
			  +"&cd_projeto=" + $("#cd_projeto").val()
		      +"&cd_proposta=" + $("#cd_proposta").val()
		      +"&cd_parcela=" + $("#cd_parcela_produto_cad").val()
		      +"&cd_tipo_produto="+$("#cd_tipo_produto").val(),
		success: function(retorno){
			alertMsg(retorno);
			ajaxCriarProdutoParcela($("#cd_parcela_produto_cad").val());
			limparTela();
			$("#salvarProdutoParcela").show();
		}
	});	
}

function cancelarProdutoParcela()
{
	limparTela();
}

function limparTela()
{
	$("#descProdutoParcela"	).val("");
	$("#cd_tipo_produto"	).val("0");

	if ($("#escondeExclusaoBotoes").val() == "S"){
		$("#salvarProdutoParcela").hide();
	} else {
		$("#salvarProdutoParcela").show();
	}
	
	$("#alterarProdutoParcela" ).hide();
	$("#cancelarProdutoParcela").hide();
	ajaxParcelaComProduto();
	ajaxParcelaSemProduto();		
}

function limparTelaSemSalvar()
{
	$("#descProdutoParcela"		).val("");
	$("#cd_tipo_produto"		).val("0");
	$("#alterarProdutoParcela"	).hide();
	$("#cancelarProdutoParcela"	).hide();
}